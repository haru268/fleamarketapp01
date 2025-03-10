<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Storage;

class ExhibitionController extends Controller
{
    public function create()
    {
        return view('exhibition.create');
    }

    public function store(Request $request)
    {
        $product = new Product;
        $product->user_id = auth()->id();

        $product->name        = $request->input('name');
        $product->description = $request->input('description');
        $product->price       = $request->input('price');

        
        $product->category    = $request->input('category'); 

       
        $product->condition   = $request->input('condition');

        
        if ($request->has('categories')) {
            $product->categories = implode(',', $request->input('categories'));
        }

        
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $product->image = Storage::disk('public')->url($path);
        }

       
        $product->is_recommended = 1;

        $product->save();

        return redirect()->route('profile.show')->with('success', '商品を出品しました。');
        
    }

    public function edit($id)
{
    $product = Product::findOrFail($id);
    
    return view('exhibition.create', compact('product'));
}

    public function update(Request $request, $id)
{
    $product = Product::findOrFail($id);

    
    $product->name = $request->input('name');
    $product->description = $request->input('description');
    $product->price = $request->input('price');
    $product->category = $request->input('category');
    $product->condition = $request->input('condition');

    if ($request->has('categories')) {
        $product->categories = implode(',', $request->input('categories'));
    }

    if ($request->hasFile('image')) {
        $path = $request->file('image')->store('images', 'public');
        $product->image = Storage::disk('public')->url($path);
    }

    $product->save();

    return redirect()->route('profile.show')->with('success', '商品情報が更新されました。');
}


}
