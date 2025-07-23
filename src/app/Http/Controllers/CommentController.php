<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\{Product, Comment};

class CommentController extends Controller
{
    /** --------------------------------------------------------------
     *  商品ページのコメント投稿
     *  -------------------------------------------------------------- */
    public function store(Request $request, Product $product)
    {
        /* --- バリデーション --- */
        $data = $request->validate([
            'body'  => 'required|string|max:400',
            'image' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

        /* --- 画像保存（あれば） --- */
        $path = $request->file('image')
              ? $request->file('image')->store('comment', 'public')
              : null;

        /* --- コメント保存 --- */
        $comment = Comment::create([
            'product_id' => $product->id,
            'user_id'    => Auth::id(),
            'body'       => $data['body'],
            'image'      => $path,
        ])->load('user');   // user を即ロード

        /* ---------- ここから下は “チャット連携” を削除 ---------- */
        // ・Trade::firstOrCreate … も
        // ・$trade->messages()->create … も全部取り除く
        /* --------------------------------------------------------- */

        /* --- レスポンス --- */
        if ($request->wantsJson()) {          // Ajax (fetch) のとき
            $avatar = $comment->user->profile_image
                   ? Storage::disk('public')->url($comment->user->profile_image)
                   : asset('images/default-user.png');

            return response()->json([
                'success' => true,
                'user'    => $comment->user->name,
                'body'    => e($comment->body),
                'avatar'  => $avatar,
            ]);
        }

        return back()->with('success', 'コメントを投稿しました');
    }
}
