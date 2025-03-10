<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Product;
use App\Models\Comment;
use Illuminate\Support\Facades\Hash;

class CommentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function logged_in_users_can_post_comments()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $product = Product::factory()->create();

        $this->actingAs($user);
        $response = $this->postJson('/comment', [
            'product_id' => $product->id,
            'body' => 'Great product!',
        ]);

        $response->assertStatus(200);
        $this->assertDatabaseHas('comments', [
            'product_id' => $product->id,
            'body' => 'Great product!',
        ]);
    }

    /** @test */
    public function guests_cannot_post_comments()
    {
        $product = Product::factory()->create();

        $response = $this->postJson('/comment', [
            'product_id' => $product->id,
            'body' => 'I love it!',
        ]);

        $response->assertStatus(401); 
    }
}
