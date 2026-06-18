<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Http\Requests\Customer\AddToCartRequest;
use App\Models\CartItem;
use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class CartController extends Controller
{
    public function __construct(private CartService $cartService) {}

    public function index(Request $request): Response
    {
        $cart  = $this->cartService->getCartWithItems($request->user());
        $total = $this->cartService->getTotal($cart);

        $items = $cart->items->map(fn ($item) => [
            'id'       => $item->id,
            'quantity' => $item->quantity,
            'product'  => [
                'id'        => $item->product->id,
                'name'      => $item->product->name,
                'slug'      => $item->product->slug,
                'price'     => $item->product->price,
                'stock'     => $item->product->stock,
                'store'     => ['name' => $item->product->store->name],
                'image'     => $item->product->images->first()?->path,
            ],
        ]);

        return Inertia::render('Customer/Cart', [
            'items' => $items,
            'total' => $total,
        ]);
    }

    public function add(AddToCartRequest $request): JsonResponse
    {
        $item = $this->cartService->addItem(
            $request->user(),
            $request->integer('product_id'),
            $request->integer('quantity'),
        );

        return response()->json(['message' => 'Added to cart', 'item_id' => $item->id]);
    }

    public function update(Request $request, CartItem $cartItem): JsonResponse
    {
        $this->authorize('update', $cartItem->cart);

        $request->validate(['quantity' => ['required', 'integer', 'min:1', 'max:99']]);

        try {
            $item = $this->cartService->updateItem($cartItem, $request->integer('quantity'));
            return response()->json(['message' => 'Cart updated', 'quantity' => $item->quantity]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(['errors' => $e->errors()], 422);
        }
    }

    public function remove(Request $request, CartItem $cartItem): JsonResponse
    {
        $this->authorize('update', $cartItem->cart);

        $this->cartService->removeItem($cartItem);

        return response()->json(['message' => 'Item removed']);
    }
}
