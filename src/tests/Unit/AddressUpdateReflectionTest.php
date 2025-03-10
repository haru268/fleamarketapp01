<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class AddressUpdateReflectionTest extends TestCase
{
    use RefreshDatabase;
    
    /** @test */
    public function updated_address_is_reflected_on_purchase_page()
    {
        // 1. ユーザーを作成（パスワードはハッシュ化しておく）
        $user = User::factory()->create([
            'password' => Hash::make('password123'),
        ]);
        
        // 2. 商品を作成（購入画面をテストするため）
        $product = Product::factory()->create();
        
        // 3. 作成したユーザーでログイン状態をシミュレート
        $this->actingAs($user);
        
        // 4. 住所変更処理を実行（POST リクエストで住所を更新）
        $newAddressData = [
            'postal_code' => '123-4567',
            'address'     => '新しい住所',
            'building'    => '新しい建物',
        ];
        $this->post(route('purchase.address.update'), $newAddressData);
        
        // 5. 商品購入画面にアクセスして、住所が反映されているか確認
        $response = $this->get(route('purchase.form', ['id' => $product->id]));
        $response->assertStatus(200);
        $response->assertSee('123-4567');
        $response->assertSee('新しい住所');
        $response->assertSee('新しい建物');
    }
}
