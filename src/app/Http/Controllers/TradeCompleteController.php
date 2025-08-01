<?php

namespace App\Http\Controllers;

use App\Http\Requests\RatingRequest;
use App\Mail\TradeCompleted;
use App\Models\Trade;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TradeCompleteController extends Controller
{
    /**
     * 取引完了 + 評価保存 + 通知
     */
    public function store(RatingRequest $request, Trade $trade)
    {
        // 取引当事者か？
        abort_unless($trade->isParticipant(Auth::id()), 403);

        DB::transaction(function () use ($request, $trade) {

            /* 1) 評価を保存（既にあれば更新） */
            $trade->ratings()->updateOrCreate(
                ['rater_id' => Auth::id()],
                [
                    'ratee_id' => $trade->otherPartyId(Auth::id()),
                    'score'    => $request->score,
                    'comment'  => $request->comment,
                ]
            );

            /* 2) 両者評価済みかを判定して status を更新 */
            $buyerRated  = $trade->ratings()->where('rater_id', $trade->buyer_id )->exists();
            $sellerRated = $trade->ratings()->where('rater_id', $trade->seller_id)->exists();

            $trade->update([
                'status' => $buyerRated && $sellerRated ? 'done' : 'progress',
            ]);

            /* 3) “システムメッセージ” を残して出品者側に未読バッジを付与 */
            $trade->messages()->create([
                'user_id' => Auth::id(),
                'body'    => '取引を完了し、評価を送りました。',
            ]);
        });

        /* 購入者が完了したときだけ出品者へメール */
        if (Auth::id() === $trade->buyer_id) {
            Mail::to($trade->seller->email)->send(new TradeCompleted($trade));
        }

        return redirect()
            ->route('trades.index')
            ->with('success', '取引が完了しました！評価を送信しました。');
    }
}
