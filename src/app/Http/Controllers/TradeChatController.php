<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TradeChatController extends Controller
{
    
    public function show(Trade $trade)
    {
       
        abort_unless($trade->isParticipant(Auth::id()), 403);

        $me = Auth::user();

     
        $otherUser = $trade->seller_id === $me->id ? $trade->buyer : $trade->seller;

     
        $messages = $trade->messages()->oldest()->get();

     
        $trade->messages()
              ->where('user_id', '!=', $me->id)
              ->where('is_read', false)
              ->update(['is_read' => true]);

        $sidebarTrades = Trade::where(fn($q)=>$q
                                ->where('seller_id', $me->id)
                                ->orWhere('buyer_id',  $me->id))
                         ->where('status', 'progress')
                         ->with('product')
                         ->withCount([
                             'messages as unread_count' => fn($q)=>$q
                                ->where('user_id','!=',$me->id)
                                ->where('is_read',false)
                         ])
                         ->latest('updated_at')
                         ->get();

      
        $alreadyRated = $trade->ratings()
                              ->where('rater_id', $me->id)
                              ->exists();

        $showRatingModal = false;

      
        if ($me->id === $trade->buyer_id &&
            $trade->status === 'progress' &&
            !$alreadyRated) {
            $showRatingModal = true;
        }

    
        if ($me->id === $trade->seller_id &&
            $trade->status === 'done' &&
            !$alreadyRated) {
            $showRatingModal = true;
        }

    
        return view('trades.chat', compact(
            'trade',
            'messages',
            'sidebarTrades',
            'otherUser',
            'showRatingModal'
        ));
    }

    public function store(Request $request, Trade $trade)
    {
        abort_unless($trade->isParticipant(Auth::id()), 403);

        $validated = $request->validate([
            'body'  => ['required', 'string', 'max:400'],
            'image' => ['nullable', 'image', 'mimes:png,jpeg,jpg'],
        ]);

        $path = $request->file('image')
               ? $request->file('image')->store('chat', 'public')
               : null;

        $trade->messages()->create([
            'user_id' => Auth::id(),
            'body'    => $validated['body'],
            'image'   => $path,
        ]);

        return back()->with('success', 'メッセージを送信しました');
    }
}
