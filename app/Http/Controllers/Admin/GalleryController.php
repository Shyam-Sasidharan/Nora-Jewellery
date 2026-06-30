<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\GalleryImage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        return view('admin.gallery.index', [
            'images' => GalleryImage::orderBy('sort_order')->latest()->paginate(18),
        ]);
    }

    public function create(): View
    {
        return view('admin.gallery.form', ['image' => new GalleryImage()]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request, true);
        $data['is_active'] = $request->boolean('is_active');
        $data['image_path'] = $request->file('image')->store('gallery', 'public');

        GalleryImage::create($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery image created.');
    }

    public function show(GalleryImage $gallery): RedirectResponse
    {
        return redirect()->route('admin.gallery.edit', $gallery);
    }

    public function edit(GalleryImage $gallery): View
    {
        return view('admin.gallery.form', ['image' => $gallery]);
    }

    public function update(Request $request, GalleryImage $gallery): RedirectResponse
    {
        $data = $this->validated($request);
        $data['is_active'] = $request->boolean('is_active');

        if ($request->hasFile('image')) {
            $this->deleteFile($gallery->image_path);
            $data['image_path'] = $request->file('image')->store('gallery', 'public');
        }

        $gallery->update($data);

        return redirect()->route('admin.gallery.index')->with('success', 'Gallery image updated.');
    }

    public function destroy(GalleryImage $gallery): RedirectResponse
    {
        $this->deleteFile($gallery->image_path);
        $gallery->delete();

        return back()->with('success', 'Gallery image deleted.');
    }

    private function validated(Request $request, bool $imageRequired = false): array
    {
        return $request->validate([
            'title' => ['nullable', 'string', 'max:160'],
            'alt_text' => ['nullable', 'string', 'max:180'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => [$imageRequired ? 'required' : 'nullable', 'image', 'max:4096'],
        ]);
    }

    private function deleteFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
