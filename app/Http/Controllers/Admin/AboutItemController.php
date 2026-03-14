<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AboutItem;
use Illuminate\Http\Request;

class AboutItemController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'google_url' => 'nullable|url|max:255',
        ]);

        $data = $request->only(['name', 'role', 'description', 'facebook_url', 'instagram_url', 'google_url']);

        if ($request->hasFile('image')) {
            $filename = 'about_' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('about_images'), $filename);
            $data['image_path'] = '/about_images/' . $filename;
        }

        AboutItem::create($data);

        return back()->with('success', 'About Item added successfully!');
    }

    public function update(Request $request, AboutItem $item)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'role' => 'nullable|string|max:255',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'facebook_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'google_url' => 'nullable|url|max:255',
        ]);

        $data = $request->only(['name', 'role', 'description', 'facebook_url', 'instagram_url', 'google_url']);

        if ($request->hasFile('image')) {
            $filename = 'about_' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('about_images'), $filename);
            $data['image_path'] = '/about_images/' . $filename;
        }

        $item->update($data);

        return back()->with('success', 'About Item updated successfully!');
    }

    public function destroy(AboutItem $item)
    {
        $item->delete();
        return back()->with('success', 'About Item deleted successfully!');
    }
}
