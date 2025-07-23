<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatMessageRequest;
use App\Models\{Trade, Rating, Message};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TradeController extends Controller
{
    /* ─────────────────────────────── ① 取引一覧 ─────────────────────────────── */
    public function index()
    {
        $me = Auth::user();

        $trades = Trade::where(fn ($q) => $q
                                ->where('seller_id', $me->id)
                                ->orWhere('buyer_id',  $me->id))
                       ->with('product')
                       ->withCount([
                           'messages as unread_count' => fn ($q) => $q
                               ->where('user_id', '!=', $me->id)
                               ->where('is_read', false),
                       ])
                       ->latest('updated_at')
                       ->get();

        return view('trades.list', compact('trades'));
    }

    /* ─────────────────────────────── ② チャット画面 ─────────────────────────────── */
    public function chat(Trade $trade)
    {
        abort_unless($trade->isParticipant(Auth::id()), 403);              // 当事者のみ

        $messages = $trade->messages()->with('user')->oldest()->get();     // 古→新

        /* 未読 → 既読 */
        $trade->messages()
              ->where('user_id', '!=', Auth::id())
              ->where('is_read', false)
              ->update(['is_read' => true]);

        /* サイドバー用取引リスト */
        $sidebarTrades = Trade::where(fn ($q) => $q
                                    ->where('seller_id', Auth::id())
                                    ->orWhere('buyer_id',  Auth::id()))
                              ->where('status', 'progress')
                              ->with('product')
                              ->withCount([
                                  'messages as unread_count' => fn ($q) => $q
                                      ->where('user_id', '!=', Auth::id())
                                      ->where('is_read', false),
                              ])
                              ->latest('updated_at')
                              ->get();

        return view('trades.chat', compact('trade', 'messages', 'sidebarTrades'));
    }

    /* ─────────────────────────────── ③ メッセージ投稿 ─────────────────────────────── */
    public function storeMessage(ChatMessageRequest $req, Trade $trade)
    {
        $path = $req->file('image')
              ? $req->file('image')->store('chat', 'public')
              : null;

        $trade->messages()->create([
            'user_id' => Auth::id(),
            'body'    => $req->body,
            'image'   => $path,
        ]);

        return back();
    }

    /*──────────────────────────────────────────
  ④ 取引完了 & 評価送信
──────────────────────────────────────────*/
public function complete(Request $req, Trade $trade)
{
    $req->validate([
        'score' => ['required','integer','between:1,5'],
        'body'  => ['nullable','string','max:255'],
    ]);

    DB::transaction(function () use ($req, $trade) {

        /* 1) 評価を保存（1取引・1人1件） */
        Rating::updateOrCreate(
            ['trade_id' => $trade->id,'rater_id' => Auth::id()],
            [
                'ratee_id' => $trade->opponent(Auth::id())->id,
                'score'    => $req->score,
                'comment'  => $req->body,
            ]
        );

        /* 2) 買い手が初めて完了したときだけ商品を確定 */
        if (!$trade->product->is_sold && Auth::id() === $trade->buyer_id) {
            $trade->product->update([
                'buyer_id'     => $trade->buyer_id,
                'is_sold'      => true,
                'purchased_at' => now(),
            ]);
        }

        /* 3) 両者が評価済みなら status=done にする */
        $ratedCount = $trade->ratings()->count();      // max 2
        if ($ratedCount === 2) {
            $trade->update(['status' => 'done']);
        } else {
            // まだ片方だけ ⇒ progress のまま残す
            // もしすでに done になっていたら巻き戻す必要は無い
        }
    });

    return redirect('/')
        ->with('success', '評価を送信しました。取引が完了しました。');
}



    /* ─────────────────────────────── ⑤ メッセージ編集 ─────────────────────────────── */
    public function editMessage(Trade $trade, Message $message)
    {
        abort_if($message->trade_id !== $trade->id || $message->user_id !== Auth::id(), 403);
        return view('trades.edit_message', compact('trade', 'message'));
    }

    public function updateMessage(Request $req, Trade $trade, Message $message)
    {
        abort_if($message->trade_id !== $trade->id || $message->user_id !== Auth::id(), 403);

        $data = $req->validate([
            'body'  => 'required|string|max:400',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        $message->body = $data['body'];

        if ($req->hasFile('image')) {
            $message->image = $req->file('image')->store('messages', 'public');
        }
        $message->save();

        return redirect()->route('trades.chat', $trade)
                         ->with('success', 'メッセージを更新しました');
    }

    /* ─────────────────────────────── ⑥ メッセージ削除 ─────────────────────────────── */
    public function destroyMessage(Trade $trade, Message $message)
    {
        abort_if($message->trade_id !== $trade->id || $message->user_id !== Auth::id(), 403);
        $message->delete();

        return redirect()->route('trades.chat', $trade)
                         ->with('success', 'メッセージを削除しました');
    }
}
