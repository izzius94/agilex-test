<?php

namespace App\Http\Requests;

use App\Models\Product;
use Closure;
use Illuminate\Foundation\Http\FormRequest;

class PlaceOrder extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:255'],
            'products' => ['required', 'array', 'min:1'],
            'products.*.id' => ['required', 'integer', 'min:1', 'exists:products,id'],
            'products.*.quantity' => ['required', 'integer', 'min:1', 'max:255', function (string $attribute, mixed $value, Closure $fail) {
                $field = str_replace('products.', '', $attribute);
                $field = str_replace('.quantity', '', $field);
                $productId = $this->get('products')[$field]['id'];
                $product = Product::find($productId);

                if (!$product) {
                    return;
                }

                if ($product->quantity < $value) {
                    $fail("We don't have enough stock for this product.");
                }

                $ordered = $product->orders()->where('shipped', false)->sum('quantity');

                if ($value > ($product->quantity - $ordered)) {
                    $fail("We don't have enough stock for this product. Max allowed is: " . $product->quantity - $ordered);
                }
            }],
        ];
    }
}
