<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_displays_all_products_on_the_home_page()
    {
        $user = User::factory()->create();
        
        Product::factory()->count(3)->create([
            'is_recommended' => true,
            'user_id' => $user->id, 
        ]);

        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSeeText(Product::first()->name);
    }

    /** @test */
    public function it_searches_products_by_name()
    {
        
        Product::factory()->create(['name' => 'Fresh Apple']);
        Product::factory()->create(['name' => 'Tasty Banana']);
        Product::factory()->create(['name' => 'Orange Juice']);

        $response = $this->get('/?query=Apple');
        $response->assertStatus(200);
        $response->assertSeeText('Fresh Apple');
        $response->assertDontSeeText('Tasty Banana');
        $response->assertDontSeeText('Orange Juice');
    }
}
