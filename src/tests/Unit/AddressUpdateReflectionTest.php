<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class AddressUpdateReflectionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function updated_address_is_reflected_on_purchase_page()
    {
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        $product = Product::factory()->create();

        $this->actingAs($user);

        // ★ shipping ルートへ POST
        $this->post(route('purchase.shipping.update'), [
            'postal_code' => '123-4567',
            'address'     => '新しい住所',
            'building'    => '新しい建物',
        ]);

        $response = $this->get(route('purchase.form', ['id' => $product->id]));
        $response->assertStatus(200)
                 ->assertSee('123-4567')
                 ->assertSee('新しい住所')
                 ->assertSee('新しい建物');
    }
}
