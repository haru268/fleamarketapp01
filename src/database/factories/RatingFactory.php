<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class RatingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'trade_id' => \App\Models\Trade::factory(),
            'user_id'  => \App\Models\User::factory(),  
            'body'     => $this->faker->realText(40),
            'image'    => null,
            'is_read'  => false,

        ];
    }
}
