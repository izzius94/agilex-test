<?php

namespace Database\Seeders;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $user1 = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);

        $user2 = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test2@example.com',
        ]);

        $product1 = Product::create([
            'name' => 'Test Product 1',
            'price' => 10,
            'quantity' => 100,
            'created_at' => '2025-01-01 00:00:00',
        ]);

        $product2 = Product::create([
            'name' => 'Test Product 2',
            'price' => 20,
            'quantity' => 200,
            'created_at' => '2025-01-01 00:00:00',
        ]);

        $product3 = Product::create([
            'name' => 'Test Product 3',
            'price' => 30,
            'created_at' => '2025-01-31 23:59:59',
        ]);

        $order = Order::create([
            'name' => 'Order 1',
            'user_id' => $user1->id,
            'shipped' => true,
            'created_at' => '2025-01-01 00:00:00',
        ]);

        $order->products()->attach([
            $product1->id => ['quantity' => 5],
            $product2->id => ['quantity' => 10],
        ]);

        $order = Order::create([
            'name' => 'Order 2',
            'user_id' => $user1->id,
            'created_at' => '2025-01-31 23:59:59',
        ]);

        $order->products()->attach([
            $product1->id => ['quantity' => 4],
            $product3->id => ['quantity' => 3],
        ]);

        $order = Order::create([
            'name' => 'Order 3',
            'user_id' => $user2->id,
            'created_at' => '2025-01-31 23:59:59',
        ]);

        $order->products()->attach([
            $product2->id => ['quantity' => 5],
            $product3->id => ['quantity' => 5],
        ]);
    }
}
