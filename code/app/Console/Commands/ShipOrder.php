<?php

namespace App\Console\Commands;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Contracts\Console\PromptsForMissingInput;

class ShipOrder extends Command implements PromptsForMissingInput
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:ship-order {id : The id of the order to ship}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Ship an order';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $id = $this->argument('id');

        $order = Order::find($id);

        if (!$order) {
            $this->error('Order does not exist.');
            return 1;
        }

        if ($order->shipped) {
            $this->warn('Order already shipped.');
            return 0;
        }

        $products = $order->products()->get();

        $products->each(function (Product $product) {
            $product->quantity = $product->quantity - $product->pivot->quantity;

            $product->save();
        });

        $order->shipped = true;

        $order->save();
        $this->info('Order shipped.');

        return 0;
    }
}
