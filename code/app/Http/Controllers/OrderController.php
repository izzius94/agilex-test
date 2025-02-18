<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceOrder;
use App\Models\Order;

class OrderController extends Controller
{
    public function store(PlaceOrder $request)
    {
        $orderData = $request->only(['name', 'description']);
        $orderData['user_id'] = auth()->id();
        $products = $request->input('products');
        $order = Order::create($orderData);

        foreach ($products as $product) {
            $order->products()->attach([$product['id'] => ['quantity' => $product['quantity']]]);
        }

        return $order;
    }
}
