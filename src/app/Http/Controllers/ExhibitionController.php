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
    $product->name = $request->input('name');
    $product->description = $request->input('description');
    $product->price = $request->input('price');

    if ($request->has('categories')) {
        $product->categories = implode(',', $request->input('categories'));
    }

    // ファイルがアップロードされているかを確認
    if ($request->hasFile('image')) {
        // ファイルを保存し、パスを取得
        $path = $request->file('image')->store('images', 'public');
        $product->image = $path;

        // storageディレクトリへの相対パスではなく、URLを保存
        $product->image = Storage::disk('public')->url($path);
    }

    $product->save();

    return redirect()->route('profile.show')->with('success', '商品を出品しました。');
}

}
