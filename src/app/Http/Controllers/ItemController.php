<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class ItemController extends Controller
{
    
    public function show($id)
{
    $product = Product::findOrFail($id);
    $comments = $product->comments()->latest()->get(); 
    $commentCount = $comments->count();
    $categoriesArray = !empty($product->categories) ? explode(',', $product->categories) : [];
    
$product = Product::with('likes')->findOrFail($id);
return view('products.item', compact('product'));

    

    
    $categoriesArray = [];
    if (!empty($product->categories)) {
        $categoriesArray = explode(',', $product->categories);
    }

    return view('products.item', [
        'product'       => $product,
        'categoriesArray' => $categoriesArray,
        'comments'      => $comments,
        'commentCount'  => $commentCount,
    ]);
}


    public function edit($id)
    {
        $product = Product::findOrFail($id);
        return view('products.item', compact('product'));
    }
    
    
}
