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

    public function test_cannot_retrieve_an_order_if_not_logged(): void
    {
        $response = $this->getJson('/orders/1');

        $response->assertStatus(401);
    }

    public function test_can_retrieve_an_order(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->hasAttached(
            Product::factory()->count(3),
            ['quantity' => 2]
        )->for($user)->create();
        $response = $this->actingAs($user)->getJson('/orders/' . $order->id);

        $order->load('products');
        $response->assertStatus(200)
            ->assertExactJson($order->toArray());
    }

    public function test_cannot_retrieve_orders_if_not_logged(): void
    {
        $response = $this->getJson('/orders');

        $response->assertStatus(401);
    }

    public function test_can_retrieve_orders_if_logged(): void
    {
        $user = User::factory()->create();

        Order::factory()->hasAttached(
            Product::factory()->count(3),
            ['quantity' => 2]
        );
        Order::factory()->hasAttached(
            Product::factory()->count(3),
            ['quantity' => 2]
        )->for($user)->count(3)->create();

        $response = $this->actingAs($user)->getJson('/orders');

        $response->assertStatus(200)
            ->assertJson(['total' => 3]);
    }

    public function test_can_retrieve_orders_filtered_if_logged(): void
    {
        $user = User::factory()->create();

        Order::factory(['name' => 'Test 1'])->for($user)->create();
        Order::factory(['name' => 'Test 2', 'description' => 'find me'])->for($user)->create();
        Order::factory(['name' => 'Unknown 1', 'description' => 'find me'])->for($user)->create();
        Order::factory(['name' => 'Unknown 2'])->for($user)->create();
        Order::factory(['name' => 'Test 1'])->for(User::factory()->create())->create();

        $response = $this->actingAs($user)->getJson('/orders?name=test&description=find%20me');

        $response->assertStatus(200)
            ->assertJson(['total' => 3]);
    }

    public function test_cannot_update_an_order_if_not_logged(): void
    {
        $response = $this->putJson('/orders/1');

        $response->assertStatus(401);
    }

    public function test_cannot_update_an_order_if(): void
    {
        $user = User::factory()->create();
        $order = Order::factory()->hasAttached(
            Product::factory()->count(3),
            ['quantity' => 2]
        )->for($user)->create();
        $response = $this->actingAs($user)->getJson('/orders/' . $order->id);

        $order->load('products');
        $response->assertStatus(200)
            ->assertExactJson($order->toArray());
    }

    public function test_cannot_update_an_order_if_the_product_quantity_is_not_enough_with_other_orders(): void
    {
        $product = Product::factory(['quantity' => 30])->create();

        $user = User::factory()->create();
        $order = Order::factory()->hasAttached(
            $product,
            ['quantity' => 5]
        )->for($user)->create();

        Order::factory()->for(User::factory()->create())->hasAttached($product, ['quantity' => 25])->create();

        $response = $this->actingAs($user)->putJson('/orders/' . $order->id, [
            'id' => $order->id,
            'products' => [[
                'id' => $product->id,
                'quantity' => 10
            ]]
        ]);

        $response->assertStatus(422)
            ->assertInvalid(['products.0.quantity']);
    }

    public function test_can_update_an_order(): void
    {
        $product = Product::factory(['quantity' => 30])->create();

        $user = User::factory()->create();
        $order = Order::factory()->hasAttached(
            $product,
            ['quantity' => 5]
        )->for($user)->create();

        Order::factory()->for(User::factory()->create())->hasAttached($product, ['quantity' => 25])->create();

        $response = $this->actingAs($user)->putJson('/orders/' . $order->id, [
            'id' => $order->id,
            'products' => [[
                'id' => $product->id,
                'quantity' => 5
            ]]
        ]);

        $response->assertStatus(200);
    }
}
