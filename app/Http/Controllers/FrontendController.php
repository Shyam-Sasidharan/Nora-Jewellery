<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\GalleryImage;
use App\Models\Product;
use App\Models\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class FrontendController extends Controller
{
    public function home(): View
    {
        return view('frontend.home', [
            'banners' => Banner::active()->orderBy('sort_order')->get(),
            'categories' => Category::active()->withCount(['products' => fn ($query) => $query->active()])->orderBy('sort_order')->get(),
            'featuredProducts' => Product::active()->with(['category', 'images'])->where('is_featured', true)->latest()->take(8)->get(),
            'newArrivals' => Product::active()->with(['category', 'images'])->where('is_new_arrival', true)->latest()->take(6)->get(),
            'galleryImages' => GalleryImage::active()->orderBy('sort_order')->take(6)->get(),
            'about' => SiteContent::byKey('about'),
            'contact' => SiteContent::byKey('contact'),
            'metaTitle' => 'Nora Jewellery | Luxury Fine Jewellery',
            'metaDescription' => 'Discover premium handcrafted jewellery, bridal sets, fine necklaces, rings, and bespoke designs from Nora Jewellery.',
        ]);
    }

    public function about(): View
    {
        return view('frontend.about', [
            'about' => SiteContent::byKey('about'),
            'metaTitle' => 'About Nora Jewellery',
            'metaDescription' => 'Learn about Nora Jewellery, a premium jewellery house built around craftsmanship, modern luxury, and timeless detail.',
        ]);
    }

    public function categories(): View
    {
        return view('frontend.categories', [
            'categories' => Category::active()->withCount(['products' => fn ($query) => $query->active()])->orderBy('sort_order')->paginate(12),
            'metaTitle' => 'Jewellery Categories | Nora Jewellery',
            'metaDescription' => 'Browse luxury rings, necklaces, earrings, bracelets, bridal jewellery, and curated fine jewellery collections.',
        ]);
    }

    public function products(Request $request, ?Category $category = null): View
    {
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

        return view('frontend.products.index', [
            'products' => $products,
            'category' => $category,
            'categories' => Category::active()->orderBy('sort_order')->get(),
            'metaTitle' => ($category?->name ?? 'Products').' | Nora Jewellery',
            'metaDescription' => $category?->description ?? 'Explore luxury fine jewellery from Nora Jewellery with premium finishes and timeless craft.',
        ]);
    }

    public function product(Product $product): View
    {
        abort_unless($product->is_active, 404);

        $product->load(['category', 'images']);

        return view('frontend.products.show', [
            'product' => $product,
            'relatedProducts' => Product::active()->with(['category', 'images'])
                ->where('category_id', $product->category_id)
                ->whereKeyNot($product->id)
                ->take(4)
                ->get(),
            'metaTitle' => $product->meta_title ?: $product->name.' | Nora Jewellery',
            'metaDescription' => $product->meta_description ?: $product->short_description ?: 'Luxury fine jewellery by Nora Jewellery.',
        ]);
    }

    public function gallery(): View
    {
        return view('frontend.gallery', [
            'images' => GalleryImage::active()->orderBy('sort_order')->paginate(18),
            'metaTitle' => 'Gallery | Nora Jewellery',
            'metaDescription' => 'View Nora Jewellery designs, campaign imagery, bridal sets, rings, necklaces, earrings, and premium jewellery details.',
        ]);
    }

    public function contact(): View
    {
        return view('frontend.contact', [
            'contact' => SiteContent::byKey('contact'),
            'metaTitle' => 'Contact Nora Jewellery',
            'metaDescription' => 'Contact Nora Jewellery for appointments, bespoke pieces, bridal consultations, and fine jewellery enquiries.',
        ]);
    }

    public function sendContact(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'email' => ['required', 'email', 'max:160'],
            'phone' => ['nullable', 'string', 'max:40'],
            'subject' => ['nullable', 'string', 'max:160'],
            'message' => ['required', 'string', 'max:4000'],
        ]);

        ContactMessage::create($validated);

        return back()->with('success', 'Thank you. Our concierge team will contact you shortly.');
    }
}
