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
    
    public function store(RatingRequest $request, Trade $trade)
    {
      
        abort_unless(in_array(Auth::id(), [$trade->buyer_id, $trade->seller_id]), 403);

        DB::transaction(function () use ($request, $trade) {

           
            $trade->ratings()->updateOrCreate(
                ['rater_id' => Auth::id()],
                [
                    'ratee_id' => $trade->otherPartyId(Auth::id()),
                    'score'    => $request->score,
                    'comment'  => $request->comment,
                ]
            );

           
            if ($trade->ratings()->count() === 2) {
                $trade->update(['status' => 'done']);
                
            }
        });

       
        if (Auth::id() === $trade->buyer_id) {
            Mail::to($trade->seller->email)
                ->send(new TradeCompleted($trade));
        }

        return redirect()
            ->route('trades.chat', $trade)
            ->with('success', '評価を送信しました！');
    }
}
