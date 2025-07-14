<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\RatingRequest;      
use App\Mail\TradeCompleted;           
use App\Models\Rating;                   
use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class TradeCompleteController extends Controller
{
    /**
     * 取引完了 + 評価保存 + 通知メール送信
     *
     * @param  RatingRequest  $request   validate 済み (score:1-5, comment:nullable)
     * @param  Trade          $trade     route-model-binding
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(RatingRequest $request, Trade $trade)
    {
       
        abort_unless(
            $trade->buyer_id === Auth::id() || $trade->seller_id === Auth::id(),
            403
        );

        
        DB::transaction(function () use ($request, $trade) {

            
            $trade->update(['status' => 'done']);

            
            $trade->ratings()->updateOrCreate(
                ['rater_id' => Auth::id()],
                [
                    'ratee_id' => $trade->otherPartyId(Auth::id()), 
                    'score'    => $request->score,
                    'comment'  => $request->comment,
                ]
            );
        });

       
        if (Auth::id() === $trade->buyer_id) {
            Mail::to($trade->seller->email)
                ->send(new TradeCompleted($trade));
        }

     
        return redirect()
            ->route('trades.index')
            ->with('success', '取引が完了しました！評価を送信しました。');
    }
}
