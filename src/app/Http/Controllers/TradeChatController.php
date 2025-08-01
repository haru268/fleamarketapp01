<?php

namespace App\Http\Controllers;

use App\Models\Trade;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TradeChatController extends Controller
{
    /*──────────────────────────────────
      チャット画面表示
    ──────────────────────────────────*/
    public function show(Trade $trade)
    {
        abort_unless($trade->isParticipant(Auth::id()), 403);

        $me        = Auth::user();
        $otherUser = $trade->seller_id === $me->id ? $trade->buyer : $trade->seller;

        /* メッセージ */
        $messages = $trade->messages()->oldest()->get();
        $trade->messages()
              ->where('user_id', '!=', $me->id)
              ->where('is_read', false)
              ->update(['is_read' => true]);

        /* サイドバー（progress 中だけ） */
        $sidebarTrades = Trade::where(fn ($q) => $q
                                ->where('seller_id', $me->id)
                                ->orWhere('buyer_id',  $me->id))
                         ->where('status', 'progress')
                         ->with('product')
                         ->withCount([
                             'messages as unread_count' => fn ($q) => $q
                                 ->where('user_id', '!=', $me->id)
                                 ->where('is_read', false)
                         ])
                         ->latest('updated_at')
                         ->get();

        /* ── モーダル表示判定（元の仕様に戻す） ── */
        $buyerRated  = $trade->ratings()->where('rater_id', $trade->buyer_id )->exists();
        $sellerRated = $trade->ratings()->where('rater_id', $trade->seller_id)->exists();

        $showRatingModal = false;

        // 購入者：まだ評価していない & status=progress
        if ($me->id === $trade->buyer_id &&
            $trade->status === 'progress' &&
            !$buyerRated) {
            $showRatingModal = true;
        }

        // 出品者：購入者は評価済み・自分は未評価 & status=progress
        if ($me->id === $trade->seller_id &&
            $trade->status === 'progress' &&
            $buyerRated && !$sellerRated) {
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

    /*──────────────────────────────────
      メッセージ送信（改善部分そのまま）
    ──────────────────────────────────*/
    public function store(Request $request, Trade $trade)
    {
        abort_unless($trade->isParticipant(Auth::id()), 403);

        $validated = $request->validate([
            'body'  => ['nullable', 'string', 'max:400'],
            'image' => ['nullable', 'image', 'mimes:png,jpeg,jpg'],
        ]);

        if (empty($validated['body']) && !$request->file('image')) {
            return back()
                ->withErrors(['body' => '本文を入力してください'])
                ->withInput();
        }

        $path = $request->file('image')
              ? $request->file('image')->store('chat', 'public')
              : null;

        $trade->messages()->create([
            'user_id' => Auth::id(),
            'body'    => $validated['body'] ?? '',
            'image'   => $path,
        ]);

        return back();
    }
}
