<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\GalleryImage;
use App\Models\Product;
use App\Models\SiteContent;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class StorefrontController extends Controller
{
    public function home(): JsonResponse
    {
        return response()->json([
            'banners' => Banner::active()->orderBy('sort_order')->get()->map(fn (Banner $banner) => $this->banner($banner)),
            'categories' => Category::active()->withCount(['products' => fn ($query) => $query->active()])->orderBy('sort_order')->get()->map(fn (Category $category) => $this->category($category)),
            'featuredProducts' => Product::active()->with(['category', 'images'])->where('is_featured', true)->latest()->take(8)->get()->map(fn (Product $product) => $this->productPayload($product)),
            'newArrivals' => Product::active()->with(['category', 'images'])->where('is_new_arrival', true)->latest()->take(6)->get()->map(fn (Product $product) => $this->productPayload($product)),
            'gallery' => GalleryImage::active()->orderBy('sort_order')->take(8)->get()->map(fn (GalleryImage $image) => $this->galleryImage($image)),
            'about' => $this->content(SiteContent::byKey('about')),
            'contact' => $this->content(SiteContent::byKey('contact')),
            'testimonials' => $this->testimonials(),
        ]);
    }

    public function categories(): JsonResponse
    {
        return response()->json([
            'data' => Category::active()->withCount(['products' => fn ($query) => $query->active()])->orderBy('sort_order')->get()->map(fn (Category $category) => $this->category($category)),
        ]);
    }

    public function products(Request $request): JsonResponse
    {
        $category = null;

        if ($request->filled('category')) {
            $category = Category::active()->where('slug', $request->string('category'))->first();
        }

        $products = Product::active()
            ->with(['category', 'images'])
            ->when($category, fn ($query) => $query->whereBelongsTo($category))
            ->when($request->filled('q'), function ($query) use ($request) {
                $term = '%'.$request->string('q')->trim().'%';
                $query->where(fn ($nested) => $nested->where('name', 'like', $term)->orWhere('short_description', 'like', $term));
            })
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return response()->json([
            'data' => $products->getCollection()->map(fn (Product $product) => $this->productPayload($product))->values(),
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'total' => $products->total(),
            ],
            'category' => $category ? $this->category($category) : null,
        ]);
    }

    public function product(Product $product): JsonResponse
    {
        abort_unless($product->is_active, 404);

        $product->load(['category', 'images']);

        return response()->json([
            'data' => $this->productPayload($product, true),
            'related' => Product::active()->with(['category', 'images'])
                ->where('category_id', $product->category_id)
                ->whereKeyNot($product->id)
                ->take(4)
                ->get()
                ->map(fn (Product $related) => $this->productPayload($related)),
        ]);
    }

    public function about(): JsonResponse
    {
        return response()->json([
            'data' => $this->content(SiteContent::byKey('about')),
        ]);
    }

    public function gallery(): JsonResponse
    {
        return response()->json([
            'data' => GalleryImage::active()->orderBy('sort_order')->get()->map(fn (GalleryImage $image) => $this->galleryImage($image)),
        ]);
    }

    public function contactDetails(): JsonResponse
    {
        return response()->json([
            'data' => $this->content(SiteContent::byKey('contact')),
        ]);
    }

    public function delivery(): JsonResponse
    {
        return response()->json([
            'data' => SiteContent::byKey('delivery')?->data ?? [
                'is_free_delivery' => true,
                'delivery_charge' => 0,
            ],
        ]);
    }

    public function contact(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:4000'],
        ]);

        ContactMessage::create($validated);

        return response()->json([
            'message' => 'Thank you. Our concierge team will contact you shortly.',
        ], 201);
    }

    public function checkout(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:140'],
            'customer_email' => ['required', 'email', 'max:160'],
            'customer_phone' => ['nullable', 'string', 'max:40'],
            'shipping_address' => ['required', 'string', 'max:1200'],
            'notes' => ['nullable', 'string', 'max:1200'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_id' => ['required', 'integer', 'exists:products,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);

        try {
            $deliveryCharge = $this->deliveryCharge();

            $order = DB::transaction(function () use ($validated, $deliveryCharge) {
                $items = collect($validated['items'])
                    ->map(fn ($item) => [
                        'product_id' => (int) $item['product_id'],
                        'quantity' => (int) $item['quantity'],
                    ])
                    ->groupBy('product_id')
                    ->map(fn ($group) => $group->sum('quantity'));

                $products = Product::active()
                    ->whereIn('id', $items->keys())
                    ->lockForUpdate()
                    ->get()
                    ->keyBy('id');

                if ($products->count() !== $items->count()) {
                    throw new \RuntimeException('Some products are no longer available.');
                }

                $subtotal = 0;

                foreach ($items as $productId => $quantity) {
                    $product = $products[$productId];

                    if ($product->price_on_request || $product->price === null) {
                        throw new \RuntimeException($product->name.' is available on request only.');
                    }

                    if ($product->stock_quantity < $quantity) {
                        throw new \RuntimeException($product->name.' has only '.$product->stock_quantity.' in stock.');
                    }

                    $subtotal += (float) $product->price * $quantity;
                }

                $order = Order::create([
                    'order_number' => 'NORA-'.now()->format('Ymd').'-'.strtoupper(Str::random(6)),
                    'customer_name' => $validated['customer_name'],
                    'customer_email' => $validated['customer_email'],
                    'customer_phone' => $validated['customer_phone'] ?? null,
                    'shipping_address' => $validated['shipping_address'],
                    'notes' => $validated['notes'] ?? null,
                    'subtotal' => $subtotal,
                    'delivery_charge' => $deliveryCharge,
                    'total' => $subtotal + $deliveryCharge,
                    'status' => 'new',
                ]);

                foreach ($items as $productId => $quantity) {
                    $product = $products[$productId];

                    $order->items()->create([
                        'product_id' => $product->id,
                        'product_name' => $product->name,
                        'product_sku' => $product->sku,
                        'unit_price' => $product->price,
                        'quantity' => $quantity,
                        'line_total' => (float) $product->price * $quantity,
                    ]);

                    $product->decrement('stock_quantity', $quantity);
                }

                return $order;
            });
        } catch (\RuntimeException $exception) {
            return response()->json([
                'message' => $exception->getMessage(),
            ], 422);
        }

        return response()->json([
            'message' => 'Order placed successfully.',
            'order' => [
                'id' => $order->id,
                'order_number' => $order->order_number,
                'total' => $order->total,
            ],
        ], 201);
    }

    private function productPayload(Product $product, bool $full = false): array
    {
        $images = $product->images->map(fn ($image) => [
            'id' => $image->id,
            'url' => $this->imageUrl($image->image_path),
            'alt' => $image->alt_text ?: $product->name,
            'is_primary' => $image->is_primary,
        ])->values();

        return [
            'id' => $product->id,
            'name' => $product->name,
            'slug' => $product->slug,
            'sku' => $product->sku,
            'category' => $product->category ? $this->category($product->category) : null,
            'price' => $product->price,
            'price_label' => $product->price_label,
            'compare_at_price' => $product->compare_at_price,
            'compare_at_price_label' => $product->compare_at_price_label,
            'price_on_request' => $product->price_on_request,
            'stock_quantity' => $product->stock_quantity,
            'is_in_stock' => $product->is_in_stock,
            'is_featured' => $product->is_featured,
            'is_new_arrival' => $product->is_new_arrival,
            'short_description' => $product->short_description,
            'description' => $full ? $product->description : null,
            'images' => $images,
            'primary_image' => $images->first(),
            'url' => '/jewellery/'.$product->slug,
        ];
    }

    private function category(Category $category): array
    {
        return [
            'id' => $category->id,
            'name' => $category->name,
            'slug' => $category->slug,
            'description' => $category->description,
            'image_url' => $this->imageUrl($category->image_path),
            'products_count' => $category->products_count ?? null,
            'url' => '/collections/'.$category->slug,
        ];
    }

    private function banner(Banner $banner): array
    {
        return [
            'id' => $banner->id,
            'title' => $banner->title,
            'subtitle' => $banner->subtitle,
            'eyebrow' => $banner->eyebrow,
            'cta_label' => $banner->cta_label,
            'cta_url' => $banner->cta_url,
            'image_url' => $this->imageUrl($banner->image_path),
        ];
    }

    private function galleryImage(GalleryImage $image): array
    {
        return [
            'id' => $image->id,
            'title' => $image->title,
            'alt' => $image->alt_text ?: $image->title ?: 'Nora Jewellery gallery',
            'url' => $this->imageUrl($image->image_path),
        ];
    }

    private function content(?SiteContent $content): ?array
    {
        if (! $content) {
            return null;
        }

        return [
            'title' => $content->title,
            'content' => $content->content,
            'data' => $content->data ?? [],
            'image_url' => $this->imageUrl($content->image_path),
        ];
    }

    private function imageUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return asset('storage/'.$path);
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

    private function testimonials(): array
    {
        return [
            ['name' => 'Ananya R.', 'text' => 'The bridal consultation felt private, thoughtful, and incredibly premium.'],
            ['name' => 'Meera S.', 'text' => 'Nora pieces have a quiet glow. The finish is beautiful in person.'],
            ['name' => 'Devika M.', 'text' => 'The team helped me choose a gift that felt personal and timeless.'],
        ];
    }
}
