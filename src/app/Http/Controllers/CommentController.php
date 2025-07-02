<?php


namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
public function store(Request $request)
{
    if (!Auth::check()) {
        return response()->json(['error' => 'Unauthorized'], 401);
    }
    
    $comment = new Comment();
    $comment->product_id = $request->input('product_id');
    $comment->user_id = auth()->id();
    $comment->body = $request->input('body');
    $comment->save();

    
    $avatarUrl = $comment->user && $comment->user->avatar_url
        ? Storage::disk('public')->url($comment->user->avatar_url)
        : asset('images/default-user.png');

    return response()->json([
        'user'   => $comment->user->name ?? 'admin',
        'body'   => $comment->body,
        'avatar' => $avatarUrl,
    ]);
}


}
