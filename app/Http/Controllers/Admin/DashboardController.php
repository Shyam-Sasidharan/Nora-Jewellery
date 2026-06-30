<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\ContactMessage;
use App\Models\GalleryImage;
use App\Models\Order;
use App\Models\Product;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function __invoke(): View
    {
        return view('admin.dashboard', [
            'stats' => [
                'products' => Product::count(),
                'active_products' => Product::where('is_active', true)->count(),
                'categories' => Category::count(),
                'banners' => Banner::count(),
                'gallery' => GalleryImage::count(),
                'orders' => Order::count(),
                'new_orders' => Order::where('status', 'new')->count(),
                'messages' => ContactMessage::where('is_read', false)->count(),
            ],
            'latestProducts' => Product::with('category')->latest()->take(5)->get(),
            'latestOrders' => Order::latest()->take(5)->get(),
            'messages' => ContactMessage::latest()->take(5)->get(),
        ]);
    }
}
