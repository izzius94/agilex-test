<?php

namespace App\Http\Requests;

use App\Rules\CheckOrderQuantity;
use Illuminate\Foundation\Http\FormRequest;

class PlaceOrder extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'integer', 'min:1', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1', 'max:255', new CheckOrderQuantity],
        ];
    }
}
