<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ExhibitionController extends Controller
{
    public function create()
    {
        return view('exhibition.create');
    }

    public function store(Request $request)
{
    $product = new Product;
    $product->name = $request->input('name');
    $product->description = $request->input('description');
    $product->price = $request->input('price');

    // カテゴリー配列を文字列に変換して保存
    if ($request->has('categories')) {
        $product->categories = implode(',', $request->input('categories'));
    }

    $product->save();

    return redirect()->route('profile.show')->with('success', '商品を出品しました。');
}
}