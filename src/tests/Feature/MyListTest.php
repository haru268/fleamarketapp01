<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class MyListTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_shows_liked_products_on_mylist_page()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $otherUser = User::factory()->create();

        $product1 = Product::factory()->create(['user_id' => $otherUser->id, 'name' => 'Product One']);
        $product2 = Product::factory()->create(['user_id' => $otherUser->id, 'name' => 'Product Two']);

        
        $user->likedProducts()->attach($product1->id);

        $this->actingAs($user);
        $response = $this->get('/?page=mylist');
        $response->assertStatus(200);
        $response->assertSeeText('Product One');
        $response->assertDontSeeText('Product Two');
    }
}
