<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TradeChatController extends Controller
{
    /**
     * チャット画面
     */
    public function show(Trade $trade)
    {
        // 取引当事者チェック
        abort_unless($trade->isParticipant(Auth::id()), 403);

        $me        = Auth::user();
        $otherUser = $trade->seller_id === $me->id ? $trade->buyer : $trade->seller;

        /* ------------------ メッセージ関連 ------------------ */
        $messages = $trade->messages()->oldest()->get();

        // 自分以外の未読を既読化
        $trade->messages()
              ->where('user_id','!=',$me->id)
              ->where('is_read',false)
              ->update(['is_read'=>true]);

        /* ------------------ サイドバー ------------------ */
        $sidebarTrades = Trade::where(fn($q)=>$q
                                ->where('seller_id',$me->id)
                                ->orWhere('buyer_id', $me->id))
                         ->where('status','progress')
                         ->with('product')
                         ->withCount([
                             'messages as unread_count'=>fn($q)=>$q
                                 ->where('user_id','!=',$me->id)
                                 ->where('is_read',false)
                         ])
                         ->latest('updated_at')
                         ->get();

        /* ------------------ モーダル表示判定 ------------------
         *
         *   ① 自分がまだ評価していない   → show
         *   ② かつ（買い手なら常に / 出品者なら買い手が評価済）
         */
        $meRated     = $trade->ratings()
                        ->where('rater_id', $me->id)
                        ->exists();
        $buyerRated  = $trade->ratings()
                        ->where('rater_id', $trade->buyer_id)
                        ->exists();

        $showRatingModal = false;
        if (!$meRated) {                         // 自分が未評価
            if ($me->id === $trade->buyer_id) {  // 買い手
                $showRatingModal = true;
            } else {                             // 出品者
                $showRatingModal = $buyerRated;
            }
        }

        return view('trades.chat', [
            'trade'            => $trade->fresh(['ratings']), // ←最新評価を preload
            'messages'         => $messages,
            'sidebarTrades'    => $sidebarTrades,
            'otherUser'        => $otherUser,
            'showRatingModal'  => $showRatingModal,
        ]);
    }

    /**
     * メッセージ送信
     */
    public function store(Request $request, Trade $trade)
    {
        abort_unless($trade->isParticipant(Auth::id()), 403);

        $validated = $request->validate([
            'body'  => ['nullable','string','max:400'],
            'image' => ['nullable','image','mimes:png,jpeg,jpg'],
        ]);

        // 本文・画像ともに無ければ手動エラー
        if (empty($validated['body']) && !$request->file('image')) {
            return back()
              ->withErrors(['body'=>'本文を入力してください'])
              ->withInput();
        }

        $path = $request->file('image')
              ? $request->file('image')->store('chat','public')
              : null;

        $trade->messages()->create([
            'user_id' => Auth::id(),
            'body'    => $validated['body'] ?? '',
            'image'   => $path,
        ]);

        return back();
    }
}
