<?php

namespace App\Http\Controllers;

use App\Models\{Product, Comment, Trade};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class CommentController extends Controller
{
    
    public function store(Request $request, Product $product)
    {
        $user   = $request->user();       
        $seller = $product->user;      

        $data = $request->validate([
            'body'  => 'required|string|max:400',
            'image' => 'nullable|image|mimes:jpg,jpeg,png',
        ]);

     
        $path = $request->file('image')
              ? $request->file('image')->store('comment', 'public')
              : null;

      
        $comment = Comment::create([
            'product_id' => $product->id,
            'user_id'    => $user->id,
            'body'       => $data['body'],
            'image'      => $path,
        ])->load('user');


        if ($user->id === $seller->id) {                         
            $trade = $product->trades()
                     ->where('status', 'progress')
                     ->latest('updated_at')
                     ->first();

            if (!$trade) {                                        
                return $this->respond($request, $comment, null,
                    'まだ購入者がいないためチャットは作成されませんでした。');
            }
        } else {        
            $trade = Trade::firstOrCreate(
                ['product_id' => $product->id,
                 'buyer_id'   => $user->id,
                 'seller_id'  => $seller->id],
                ['status'     => 'progress']
            );
        }


        $trade->messages()->create([
            'user_id' => $user->id,
            'body'    => $comment->body,
            'image'   => $comment->image,
        ]);

        return $this->respond($request, $comment, $trade);
    }

 
    private function respond(Request $request, Comment $comment,
                             ?Trade $trade, string $info = '')
    {
        if ($request->expectsJson()) {  
            $avatar = $comment->user->profile_image
                    ? Storage::disk('public')->url($comment->user->profile_image)
                    : asset('images/default-user.png');

            return response()->json([
                'success' => true,
                'user'    => $comment->user->name,
                'body'    => e($comment->body), 
                'avatar'  => $avatar,
                'tradeId' => $trade ? $trade->id : null,
                'info'    => $info,
            ]);
        }

        if ($info) return back()->with('info', $info);

        return redirect()->route('trades.chat', $trade)
                         ->with('success', 'コメントを送信し、取引チャットに転送しました。');
    }
}
