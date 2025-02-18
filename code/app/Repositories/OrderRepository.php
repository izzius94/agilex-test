<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class OrderRepository
{
    public function filter(Request $request): LengthAwarePaginator
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

        if ($request->has('date') && is_array($request->get('date'))) {
            $query->where(function ($query) use ($request) {
                $date = $request->get('date');

                if (array_key_exists('start', $date)) {
                    $query->whereDate('created_at', '>=', $date['start']);
                }

                if (array_key_exists('end', $date)) {
                    $query->orWhereDate('created_at', '<=', $date['end']);
                }
            });
        }

        return $query->paginate();
    }
}
