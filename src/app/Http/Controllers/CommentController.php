<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\{Product, Comment};

class CommentController extends Controller
{
    
    public function store(Request $request)
    {
    
        $data = $request->validate([
            'product_id' => ['required', 'exists:products,id'],
            'body'       => ['required', 'string', 'max:400'],
            'image'      => ['nullable', 'image', 'mimes:jpg,jpeg,png'],
        ]);

      
        $path = $request->file('image')
                ? $request->file('image')->store('comment', 'public')
                : null;

       
        $comment = Comment::create([
            'product_id' => $data['product_id'],
            'user_id'    => Auth::id(),
            'body'       => $data['body'],
            'image'      => $path,
        ])->load('user');    

   
        if ($request->wantsJson()) {        
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
