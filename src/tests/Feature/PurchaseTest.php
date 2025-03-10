<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class PurchaseTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function logged_in_user_can_purchase_a_product()
    {
        $seller = User::factory()->create();
        $buyer = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);

        $product = Product::factory()->create([
            'user_id' => $seller->id,
            'is_recommended' => true,
        ]);

        $this->actingAs($buyer);
        $response = $this->post("/purchase/{$product->id}");
        $response->assertRedirect(route('profile.show'));
        $this->assertDatabaseHas('products', [
            'id' => $product->id,
            'buyer_id' => $buyer->id,
        ]);
    }
}
