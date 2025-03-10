<?php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        return [
            // user_id を自動生成する場合は User::factory() を使用します
            'user_id' => User::factory(),
            'name' => $this->faker->words(2, true), // 2語の文字列を生成
            'price' => $this->faker->numberBetween(100, 10000),
            'is_sold' => false,
            'is_recommended' => true,
            'category' => null,   // 必要ならカテゴリ情報を設定
            'condition' => null,  // 必要なら商品の状態を設定
            'image' => null,      // 画像URLなどが必要なら設定
        ];
    }
}
