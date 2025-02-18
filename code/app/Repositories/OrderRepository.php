<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class OrderRepository
{
    public function filter(Request $request): LengthAwarePaginator
    {
        $query = Order::query();

        if ($request->has('name')) {
            $query->whereLike('name', '%' . $request->get('name') . '%');
        }

        if ($request->has('description')) {
            $query->orWhereLike('description', '%' . $request->get('description') . '%');
        }

        return $query->paginate();
    }
}
