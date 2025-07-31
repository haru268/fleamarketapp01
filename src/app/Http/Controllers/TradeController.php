<?php

namespace App\Http\Controllers;

use App\Http\Requests\ChatMessageRequest;
use App\Mail\TradeCompleted;
use App\Models\{Trade, Rating};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Models\Message;

class TradeController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $trades = Trade::where(fn ($q) => $q
                                ->where('seller_id', $user->id)
                                ->orWhere('buyer_id',  $user->id))
                       ->with('product')
                       ->withCount([

                           'messages as unread_count' => fn ($q) => $q
                               ->where('user_id', '!=', $user->id)
                               ->where('is_read', false),
                       ])
                       ->latest('updated_at')
                       ->get();

        return view('trades.list', compact('trades'));
    }

    public function chat(Trade $trade)
    {
        abort_if(! $trade->isParticipant(Auth::id()), 403);

        $messages = $trade->messages()
                          ->with('user')
                          ->latest()
                          ->get();

        $trade->messages()
              ->where('user_id', '!=', Auth::id())
              ->where('is_read', false)
              ->update(['is_read' => true]);

        $sidebarTrades = Auth::user()
            ->tradesAsSeller()
            ->get()
            ->merge(Auth::user()->tradesAsBuyer)
            ->sortByDesc('updated_at');

        return view('trades.chat', compact('trade', 'messages', 'sidebarTrades'));
    }

    public function storeMessage(ChatMessageRequest $req, Trade $trade)
{
    /* ▼ body も image も無い時だけ手動エラー */
    if (empty($req->body) && !$req->file('image')) {
        return back()
            ->withErrors(['body' => '本文を入力してください'])
            ->withInput();
    }

    $path = $req->file('image')
            ? $req->file('image')->store('chat', 'public')
            : null;

    $trade->messages()->create([
        'user_id' => Auth::id(),
        'body'    => $req->body ?: '',   // 空文字なら &nbsp; を入れるので OK
        'image'   => $path,
    ]);

    return back();
}

    public function complete(Request $req, Trade $trade)
    {
        $req->validate([
            'score' => ['required', 'integer', 'between:1,5'],
            'body'  => ['nullable', 'string', 'max:255'],
        ]);

        Rating::updateOrCreate(
            [
                'trade_id' => $trade->id,
                'rater_id' => Auth::id(),
            ],
            [
                'ratee_id' => $trade->opponent(Auth::id())->id,
                'score'    => $req->score,
                'comment'  => $req->body,
            ]
        );

        $trade->update(['status' => 'done']);

        if (Auth::id() === $trade->buyer_id) {
            Mail::to($trade->seller->email)
                ->send(new TradeCompleted($trade));
        }

        return redirect()
               ->route('trades.index')
               ->with('success', '取引を完了しました');
    }

    function editMessage(Trade $trade, Message $message)
    {
        if ($message->trade_id !== $trade->id || $message->user_id !== Auth::id()) {
            abort(403);
        }
        return view('trades.edit_message', compact('trade', 'message'));
    }

    public function updateMessage(Request $request, Trade $trade, Message $message)
    {
        if ($message->trade_id !== $trade->id || $message->user_id !== Auth::id()) {
            abort(403);
        }

        $data = $request->validate([
            'body'  => 'required|string|max:400',
            'image' => 'nullable|image|mimes:jpeg,png,jpg',
        ]);

        $message->body = $data['body'];
        if ($request->hasFile('image')) {
            $message->image = $request->file('image')->store('messages', 'public');
        }
        $message->save();

        return redirect()
            ->route('trades.chat', $trade)
            ->with('success', 'メッセージを更新しました');
    }

    public function destroyMessage(Trade $trade, Message $message)
    {
        if ($message->trade_id !== $trade->id || $message->user_id !== Auth::id()) {
            abort(403);
        }

        $message->delete();

        return redirect()
            ->route('trades.chat', $trade)
            ->with('success', 'メッセージを削除しました');
    }
}
