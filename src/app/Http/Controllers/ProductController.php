<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function index(Request $request)
    {
     
        $q = $request->input('query');
        $page = $request->query('page');

        if ($page === 'mylist' && Auth::check()) {
           
            $likedProducts = Auth::user()->likedProducts()
                ->when($q, function($query, $q) {
                    return $query->where('name', 'LIKE', '%' . $q . '%');
                })
                ->get();
            return view('products.index', compact('likedProducts', 'q'));
        } else {
           
            $recommendedProducts = Product::where('is_recommended', true)
                ->when(Auth::check(), function($query) {
                    $query->where('user_id', '<>', Auth::id());
                })
                ->when($q, function($query, $q) {
                    return $query->where('name', 'LIKE', '%' . $q . '%');
                })
                ->get();
            return view('products.index', compact('recommendedProducts', 'q'));
        }
    }
}
