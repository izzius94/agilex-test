<?php

namespace App\Http\Controllers;

use App\Http\Requests\PlaceOrder;
use App\Http\Requests\UpdateOrder;
use App\Models\Order;
use App\Repositories\OrderRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index(OrderRepository $repository, Request $request): LengthAwarePaginator
    {
        return $repository->filter($request);
    }

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

    public function get(int $id)
    {
        return Order::where('user_id', auth()->id())
            ->with('products')
            ->findOrFail($id);
    }

    public function update(int $id, UpdateOrder $request)
    {
        $order = $request->getOrder();
        $products = $request->input('products');
        $data = [];

        foreach ($products as $product) {
            $data[$product['id']] = ['quantity' => $product['quantity']];
        }

        $order->products()->sync($data);

        return ['message' => 'Order updated.'];
    }

    public function destroy(int $id)
    {
        $order = Order::where('user_id', auth()->id())->findOrFail($id);

        if ($order->shipped) {
            return response()->json(['message' => 'Order already shipped.'], 403);
        }

        $order->delete();

        return ['message' => 'Order deleted.'];
    }
}
