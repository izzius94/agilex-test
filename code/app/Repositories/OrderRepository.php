<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Contracts\Pagination\Paginator;
use Illuminate\Http\Request;

class OrderRepository
{
    public function filter(Request $request): Paginator
    {
        $query = Order::where('user_id', $request->user()->id);

        if ($request->has('name') || $request->has('description')) {
            $query->where(function ($query) use ($request) {
                if ($request->has('name')) {
                    $query->whereLike('name', '%'.$request->get('name').'%');
                }

                if ($request->has('description')) {
                    $query->orWhereLike('description', '%'.$request->get('description').'%');
                }
            });
        }

        if ($request->has('date_start') || $request->has('date_end')) {
            $query->where(function ($query) use ($request) {
                if ($request->has('date_start')) {
                    $query->whereDate('created_at', '>=', $request->get('date_start'));
                }

                if ($request->has('date_end')) {
                    $query->orWhereDate('created_at', '<=', $request->get('date_end'));
                }
            });
        }

        return $query->simplePaginate();
    }
}
