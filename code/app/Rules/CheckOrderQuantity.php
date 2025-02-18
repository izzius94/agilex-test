<?php

namespace App\Rules;

use App\Models\Product;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Translation\PotentiallyTranslatedString;

class CheckOrderQuantity implements ValidationRule, DataAwareRule
{
    protected array $data = [];

    public function __construct(protected readonly ?int $id = null)
    {
    }

    /**
     * Set the data under validation.
     *
     * @param  array<string, mixed>  $data
     */
    public function setData(array $data): static
    {
        $this->data = $data;

        return $this;
    }

    /**
     * Run the validation rule.
     *
     * @param Closure(string, ?string=): PotentiallyTranslatedString $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $field = str_replace('products.', '', $attribute);
        $field = str_replace('.quantity', '', $field);
        $productId = $this->data['products'][$field]['id'];
        $product = Product::find($productId);

        if (!$product) {
            return;
        }

        if ($product->quantity < $value) {
            $fail("We don't have enough stock for this product.");
        }

        $ordered = $product->orders()->where('shipped', false);

        if ($this->id) {
            $ordered->where('orders.id', '!=', $this->id);
        }

        $ordered = $ordered->sum('quantity');

        if ($value > ($product->quantity - $ordered)) {
            $fail("We don't have enough stock for this product. Max allowed is: " . $product->quantity - $ordered);
        }
    }
}
