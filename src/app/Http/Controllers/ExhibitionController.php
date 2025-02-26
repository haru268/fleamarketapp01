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

        dd($request->all());
        
        $product = new Product;
        $product->user_id = auth()->id();

        $product->name        = $request->input('name');
        $product->description = $request->input('description');
        $product->price       = $request->input('price');

        // 単一カテゴリーはここで（フォームに単一の入力がない場合、この値は null になる）
        $product->category    = $request->input('category'); 

        // 商品の状態はセレクトボックスから取得（name="condition"）
        $product->condition   = $request->input('condition');

        // 複数選択可能なカテゴリー（チェックボックス）の処理
        if ($request->has('categories')) {
            $product->categories = implode(',', $request->input('categories'));
        }

        // 画像アップロード
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('images', 'public');
            $product->image = Storage::disk('public')->url($path);
        }

        $product->save();

        return redirect()->route('profile.show')->with('success', '商品を出品しました。');
    }
}
