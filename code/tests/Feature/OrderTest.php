<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    public function test_cannot_place_an_order_if_not_logged(): void
    {
        $response = $this->postJson('/orders');

        $response->assertStatus(401);
    }

    public function test_cannot_store_an_order_with_invalid_data(): void
    {
        $user = User::factory()->create();
        $response = $this->actingAs($user)->postJson('/orders', [
            'products' => [[
                'id' => '1000',
                'quantity' => '256'
            ]]
        ]);

        $response->assertStatus(422)
            ->assertInvalid(['name', 'products.0.id', 'products.0.quantity']);
    }

    public function test_cannot_store_an_order_if_the_product_quantity_is_not_enough(): void
    {
        $user = User::factory()->create();
        $product = Product::factory(['quantity' => 30])
            ->create();

        $response = $this->actingAs($user)->postJson('/orders', [
            'name' => 'My order',
            'products' => [[
                'id' => $product->id,
                'quantity' => 31
            ]]
        ]);

        $response->assertStatus(422)
            ->assertInvalid(['products.0.quantity']);
    }

    public function test_cannot_store_an_order_if_the_product_quantity_is_not_enough_with_other_orders(): void
    {
        $user = User::factory()->create();
        $product = Product::factory(['quantity' => 30])
            ->hasAttached(
                Order::factory()->for($user),
                ['quantity' => 25]
            )
            ->create();

        $response = $this->actingAs($user)->postJson('/orders', [
            'name' => 'My order',
            'products' => [[
                'id' => $product->id,
                'quantity' => '7'
            ]]
        ]);

        $response->assertStatus(422)
            ->assertInvalid(['products.0.quantity']);
    }

    public function test_can_store_an_order(): void
    {
        $user = User::factory()->create();
        $product = Product::factory()->create();
        $response = $this->actingAs($user)->postJson('/orders', [
            'name' => 'Test Order',
            'description' => 'Order description',
            'products' => [[
                'id' => $product->id,
                'quantity' => '2'
            ]]
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'name' => 'Test Order',
                'description' => 'Order description',
            ]);
    }
}
