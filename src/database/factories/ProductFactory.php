<?php

// database/factories/ProductFactory.php

namespace Database\Factories;

use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProductFactory extends Factory
{
    protected $model = Product::class;

    public function definition()
    {
        // 既存のユーザーからランダムに選ぶ。もし存在しない場合は新しく作成してそのIDを使用
        $user = User::inRandomOrder()->first() ?? User::factory()->create();

        return [
            'user_id' => $user->id,
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'price' => $this->faker->numberBetween(1000, 10000),
            'is_sold' => false,
            'buyer_id' => null,
            'purchased_at' => null,
            'category' => $this->faker->randomElement(['ファッション', '家電', 'インテリア']),
            'condition' => $this->faker->randomElement(['良好', '目立った傷や汚れなし', 'やや傷や汚れあり', '状態が悪い']),
            'image' => $this->faker->imageUrl(640, 480, 'technics'),
            'is_recommended' => true,
        ];
    }
}
