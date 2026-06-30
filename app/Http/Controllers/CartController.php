<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CartController extends Controller
{
    public function index(Request $request): View
    {
        return view('frontend.cart.index', [
            'cartItems' => $this->cartItems($request),
            'metaTitle' => 'Cart | Nora Jewellery',
            'metaDescription' => 'Review your Nora Jewellery cart before checkout.',
        ]);
    }

    public function add(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active, 404);

        if ($product->price_on_request || $product->price === null) {
            return back()->with('error', 'This product is available on request. Please book an appointment.');
        }

        if (! $product->is_in_stock) {
            return back()->with('error', 'This product is out of stock.');
        }

        $data = $request->validate([
            'quantity' => ['nullable', 'integer', 'min:1'],
        ]);
        $quantity = (int) ($data['quantity'] ?? 1);

        $cart = $request->session()->get('cart', []);
        $current = (int) ($cart[$product->id] ?? 0);
        $cart[$product->id] = min($current + $quantity, $product->stock_quantity);
        $request->session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Product added to cart.');
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $request->validate([
            'quantity' => ['required', 'integer', 'min:1'],
        ]);

        $cart = $request->session()->get('cart', []);
        $cart[$product->id] = min((int) $data['quantity'], max(1, $product->stock_quantity));
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Cart updated.');
    }

    public function remove(Request $request, Product $product): RedirectResponse
    {
        $cart = $request->session()->get('cart', []);
        unset($cart[$product->id]);
        $request->session()->put('cart', $cart);

        return back()->with('success', 'Product removed from cart.');
    }

    public function checkout(Request $request): View|RedirectResponse
    {
        $cartItems = $this->cartItems($request);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        return view('frontend.cart.checkout', [
            'cartItems' => $cartItems,
            'subtotal' => $subtotal = $cartItems->sum('line_total'),
            'deliveryCharge' => $deliveryCharge = $this->deliveryCharge(),
            'total' => $subtotal + $deliveryCharge,
            'metaTitle' => 'Checkout | Nora Jewellery',
            'metaDescription' => 'Complete your Nora Jewellery checkout.',
        ]);
    }

    public function placeOrder(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:140'],
            'customer_email' => ['required', 'email', 'max:160'],
            'customer_phone' => ['nullable', 'string', 'max:40'],
            'shipping_address' => ['required', 'string', 'max:1200'],
            'notes' => ['nullable', 'string', 'max:1200'],
        ]);

        $cartItems = $this->cartItems($request);

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        try {
            $deliveryCharge = $this->deliveryCharge();

            $order = DB::transaction(function () use ($cartItems, $validated, $deliveryCharge) {
                $subtotal = $cartItems->sum('line_total');

                $order = Order::create($validated + [
                    'order_number' => 'NORA-'.now()->format('Ymd').'-'.strtoupper(Str::random(6)),
                    'subtotal' => $subtotal,
                    'delivery_charge' => $deliveryCharge,
                    'total' => $subtotal + $deliveryCharge,
                    'status' => 'new',
                ]);

                foreach ($cartItems as $item) {
                    $product = Product::whereKey($item['product']->id)->lockForUpdate()->firstOrFail();

                    if ($product->stock_quantity < $item['quantity']) {
                        throw new \RuntimeException($product->name.' has only '.$product->stock_quantity.' in stock.');
                    }

                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'unit_price' => $product->price,
                        'quantity' => $item['quantity'],
                        'line_total' => (float) $product->price * $item['quantity'],
                    ]);

                    $product->decrement('stock_quantity', $item['quantity']);
                }

                return $order;
            });
        } catch (\RuntimeException $exception) {
            return redirect()->route('cart.index')->with('error', $exception->getMessage());
        }

        $request->session()->forget('cart');

        return redirect()->route('checkout.success', $order)->with('success', 'Order placed successfully.');
    }

    public function success(Order $order): View
    {
        return view('frontend.cart.success', [
            'order' => $order->load('items'),
            'metaTitle' => 'Order Placed | Nora Jewellery',
            'metaDescription' => 'Your Nora Jewellery order has been placed successfully.',
        ]);
    }

    private function cartItems(Request $request)
    {
        $cart = collect($request->session()->get('cart', []))
            ->mapWithKeys(fn ($quantity, $productId) => [(int) $productId => (int) $quantity])
            ->filter(fn ($quantity) => $quantity > 0);

        if ($cart->isEmpty()) {
            return collect();
        }

        return Product::active()
            ->with('images')
            ->whereIn('id', $cart->keys())
            ->get()
            ->map(function (Product $product) use ($cart) {
                $quantity = min($cart[$product->id], max(0, $product->stock_quantity));

                return [
                    'product' => $product,
                    'quantity' => $quantity,
                    'line_total' => (float) $product->price * $quantity,
                ];
            })
            ->filter(fn ($item) => $item['quantity'] > 0 && ! $item['product']->price_on_request && $item['product']->price !== null);
    }

    private function deliveryCharge(): float
    {
        $delivery = SiteContent::byKey('delivery');
        $data = $delivery?->data ?? [];

        if ((bool) ($data['is_free_delivery'] ?? true)) {
            return 0;
        }

        return max(0, (float) ($data['delivery_charge'] ?? 0));
    }
}
