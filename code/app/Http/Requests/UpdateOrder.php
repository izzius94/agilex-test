<?php

namespace App\Http\Requests;

use App\Models\Order;
use App\Rules\CheckOrderQuantity;
use Illuminate\Foundation\Http\FormRequest;

/**
 * Request to update an order
 */
class UpdateOrder extends FormRequest
{
    protected Order $order;

    /**
     * Determine if the use can update the order by checking if the order is shipped or not
     */
    public function authorize(): bool
    {
        $this->order = Order::findOrFail((int) $this->route('id'));

        if (! $this->order->shipped) {
            return true;
        }

        return false;
    }

    public function rules(): array
    {
        return [
            'name' => ['filled', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'integer', 'min:1', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1', 'max:255', new CheckOrderQuantity((int) $this->route('id', $this->order))],
        ];
    }

    public function getOrder(): Order
    {
        return $this->order;
    }
}
