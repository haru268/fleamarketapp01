<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Address;
use Illuminate\Support\Facades\Hash;

class AddressChangeTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function logged_in_user_can_update_address()
    {
        $user = User::factory()->create();
$address = Address::factory()->create([
    'user_id' => $user->id,
    'postal_code' => '000-0000',
    'address' => '旧住所',
    'building' => '旧建物',
]);
$this->actingAs($user);
$response = $this->post('/purchase/address', [
    'postal_code' => '111-1111',
    'address' => '新住所',
    'building' => '新建物',
]);
$response->assertRedirect(route('profile.show'));
$this->assertDatabaseHas('addresses', [
    'user_id' => $user->id,
    'postal_code' => '111-1111',
    'address' => '新住所',
    'building' => '新建物',
]);

    }
}
