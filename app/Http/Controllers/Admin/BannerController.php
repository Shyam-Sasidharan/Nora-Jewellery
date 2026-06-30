<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class BannerController extends Controller
{
    public function index(): View
    {
        return view('admin.banners.index', [
            'banners' => Banner::orderBy('sort_order')->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.banners.form', ['banner' => new Banner()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $data['image_path'] = $request->file('image')->store('banners', 'public');
        }

        Banner::create($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner created.');
    }

    public function show(Banner $banner): RedirectResponse
    {
        return redirect()->route('admin.banners.edit', $banner);
    }

    public function edit(Banner $banner): View
    {
        return view('admin.banners.form', compact('banner'));
    }

    public function update(Request $request, Banner $banner): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $this->deleteFile($banner->image_path);
            $data['image_path'] = $request->file('image')->store('banners', 'public');
        }

        $banner->update($data);

        return redirect()->route('admin.banners.index')->with('success', 'Banner updated.');
    }

    public function destroy(Banner $banner): RedirectResponse
    {
        $this->deleteFile($banner->image_path);
        $banner->delete();

        return back()->with('success', 'Banner deleted.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'subtitle' => ['nullable', 'string', 'max:255'],
            'eyebrow' => ['nullable', 'string', 'max:120'],
            'cta_label' => ['nullable', 'string', 'max:80'],
            'cta_url' => ['nullable', 'string', 'max:255'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['nullable', 'image', 'max:4096'],
        ]);
    }

    private function deleteFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
