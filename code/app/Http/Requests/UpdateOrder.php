<?php

namespace App\Http\Requests;

use App\Models\Product;
use App\Rules\CheckOrderQuantity;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class UpdateOrder extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['filled', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'integer', 'min:1', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1', 'max:255', new CheckOrderQuantity($this->route('id'))],
        ];
    }
}
