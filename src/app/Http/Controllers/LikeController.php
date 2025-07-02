<?php

namespace App\Http\Controllers;

use App\Models\Like;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function toggle(Request $request)
    {
        if (!Auth::check()) {
            return response()->json(['success' => false], 401);
        }
        
        $user = Auth::user();
        $productId = $request->input('product_id');
        
        
        $product = Product::findOrFail($productId);
        
        
        $like = Like::where('user_id', $user->id)
                    ->where('product_id', $productId)
                    ->first();
        
        if ($like) {
            
            $like->delete();
            $liked = false;
        } else {
            
            Like::create([
                'user_id' => $user->id,
                'product_id' => $productId,
            ]);
            $liked = true;
        }
        
        return response()->json([
            'success' => true,
            'liked' => $liked,
            'like_count' => $product->likes()->count(),
        ]);
    }
}
