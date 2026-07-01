<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\SiteContent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ContentController extends Controller
{
    public function editAbout(): View
    {
        return view('admin.content.about', [
            'about' => SiteContent::firstOrCreate(['key' => 'about'], [
                'title' => 'About Nora Jewellery',
                'content' => 'Crafted for modern heirlooms, Nora Jewellery blends graceful design with meticulous hand-finishing.',
            ]),
        ]);
    }

    public function updateAbout(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'content' => ['required', 'string'],
            'image' => ['nullable', 'image', 'max:4096'],
            'data.heritage' => ['nullable', 'string', 'max:180'],
            'data.craft' => ['nullable', 'string', 'max:180'],
            'data.promise' => ['nullable', 'string', 'max:180'],
        ]);

        $about = SiteContent::firstOrCreate(['key' => 'about']);

        if ($request->hasFile('image')) {
            $this->deleteFile($about->image_path);
            $data['image_path'] = $request->file('image')->store('content', 'public');
        }

        $about->update($data);

        return back()->with('success', 'About content updated.');
    }

    public function editContact(): View
    {
        return view('admin.content.contact', [
            'contact' => SiteContent::firstOrCreate(['key' => 'contact'], [
                'title' => 'Visit Nora Jewellery',
                'content' => 'Book a private appointment or speak with our jewellery consultants.',
                'data' => [
                    'phone' => '+91 8848254420',
                    'email' => 'norajewels0523@gmail.com',
                    'address' => 'Nora Jewels nss college(po) Nemmara, palakkad 678508',
                    'hours' => 'Monday to Saturday, 10:00 AM to 7:00 PM',
                ],
            ]),
            'messages' => ContactMessage::latest()->paginate(12),
        ]);
    }

    public function updateContact(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'max:180'],
            'content' => ['nullable', 'string'],
            'data.phone' => ['nullable', 'string', 'max:80'],
            'data.email' => ['nullable', 'email', 'max:160'],
            'data.address' => ['nullable', 'string', 'max:255'],
            'data.hours' => ['nullable', 'string', 'max:180'],
            'data.map_url' => ['nullable', 'string', 'max:500'],
        ]);

        SiteContent::updateOrCreate(['key' => 'contact'], $data);

        return back()->with('success', 'Contact details updated.');
    }

    public function markMessageRead(ContactMessage $message): RedirectResponse
    {
        $message->update(['is_read' => true]);

        return back()->with('success', 'Message marked as read.');
    }

    public function destroyMessage(ContactMessage $message): RedirectResponse
    {
        $message->delete();

        return back()->with('success', 'Message deleted.');
    }

    public function editDelivery(): View
    {
        return view('admin.content.delivery', [
            'delivery' => SiteContent::firstOrCreate(['key' => 'delivery'], [
                'title' => 'Delivery Settings',
                'data' => [
                    'is_free_delivery' => true,
                    'delivery_charge' => 0,
                ],
            ]),
        ]);
    }

    public function updateDelivery(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'data.is_free_delivery' => ['nullable', 'boolean'],
            'data.delivery_charge' => ['nullable', 'numeric', 'min:0'],
        ]);

        SiteContent::updateOrCreate(
            ['key' => 'delivery'],
            [
                'title' => 'Delivery Settings',
                'data' => [
                    'is_free_delivery' => (bool) ($validated['data']['is_free_delivery'] ?? false),
                    'delivery_charge' => (float) ($validated['data']['delivery_charge'] ?? 0),
                ],
            ],
        );

        return back()->with('success', 'Delivery settings updated.');
    }

    private function deleteFile(?string $path): void
    {
        if ($path) {
            Storage::disk('public')->delete($path);
        }
    }
}
