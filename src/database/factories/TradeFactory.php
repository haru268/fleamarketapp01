<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class TradeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
{
    return [
        'product_id' => \App\Models\Product::factory(),
        'seller_id'  => \App\Models\User::factory(),
        'buyer_id'   => \App\Models\User::factory(),
        'status'     => $this->faker->randomElement(['progress','done']),
    ];
}


} 
