<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function index(): View
    {
        return view('admin.products.index', [
            'products' => Product::with(['category', 'images'])->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.form', [
            'product' => new Product(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['price_on_request'] = $request->boolean('price_on_request');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_new_arrival'] = $request->boolean('is_new_arrival');
        $data['is_active'] = $request->boolean('is_active');

        $product = Product::create($data);
        $this->storeImages($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function show(Product $product): RedirectResponse
    {
        return redirect()->route('admin.products.edit', $product);
    }

    public function edit(Product $product): View
    {
        return view('admin.products.form', [
            'product' => $product->load('images'),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product): RedirectResponse
    {
        $data = $this->validated($request, $product->id);
        $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
        $data['price_on_request'] = $request->boolean('price_on_request');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_new_arrival'] = $request->boolean('is_new_arrival');
        $data['is_active'] = $request->boolean('is_active');

        $product->update($data);
        $this->storeImages($request, $product);

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        foreach ($product->images as $image) {
            $this->deleteFile($image->image_path);
        }

        $product->delete();

        return back()->with('success', 'Product deleted.');
    }

    public function destroyImage(ProductImage $image): RedirectResponse
    {
        $product = $image->product;
        $this->deleteFile($image->image_path);
        $image->delete();

        if ($product && ! $product->images()->where('is_primary', true)->exists()) {
            $product->images()->oldest()->first()?->update(['is_primary' => true]);
        }

        return back()->with('success', 'Product image removed.');
    }

    public function makePrimary(ProductImage $image): RedirectResponse
    {
        $image->product->images()->update(['is_primary' => false]);
        $image->update(['is_primary' => true]);

        return back()->with('success', 'Primary image updated.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:180'],
            'sku' => ['nullable', 'string', 'max:80', 'unique:products,sku,'.($ignoreId ?: 'NULL').',id'],
            'price' => ['nullable', 'numeric', 'min:0'],
            'compare_at_price' => ['nullable', 'numeric', 'min:0'],
            'stock_quantity' => ['required', 'integer', 'min:0'],
            'short_description' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'meta_title' => ['nullable', 'string', 'max:180'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'images' => ['nullable', 'array'],
            'images.*' => ['image', 'max:4096'],
        ]);
    }

    private function storeImages(Request $request, Product $product): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $hasPrimary = $product->images()->where('is_primary', true)->exists();
        $sort = (int) $product->images()->max('sort_order');

        foreach ($request->file('images') as $file) {
            $product->images()->create([
                'image_path' => $file->store('products', 'public'),
                'alt_text' => $product->name,
                'sort_order' => ++$sort,
                'is_primary' => ! $hasPrimary,
            ]);

            $hasPrimary = true;
        }
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name);
        $slug = $base ?: 'product';
        $count = 2;

        while (Product::where('slug', $slug)->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))->exists()) {
            $slug = $base.'-'.$count++;
        }

        return $slug;
    }

    private function deleteFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
