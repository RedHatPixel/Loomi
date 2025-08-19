<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductSale;
use App\Models\Status;

class SaleController extends Controller
{
    public function store(Order $order)
    {
        $items = OrderItem::where('order_id', $order->id)->get();

        foreach ($items as $item) {
            $product = Product::findOrFail($item->product_id);
            if ($product->reduceStock($item->quantity)) {
                ProductSale::create([
                    'product_id' => $item->product_id,
                    'quantity' => $item->quantity,
                    'price_at_sale' => $item->price_at_sale,
                    'purchase_by' => $order->user_id,
                ]);
            } else {
                return redirect()->back()
                    ->withErrors('Product stock is not enough');
            }
        }

        $status = Status::where('name', 'received')->first();

        $order->update([
            'status_id' => $status->id
        ]);

        return redirect()->back()
            ->with('success', 'User orders was given successfully.');
    }
}
