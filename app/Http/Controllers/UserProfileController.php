<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;

class UserProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $favorites = $user->favorites()->with('category')->get();
        $orders = Order::with('items')
                      ->where('user_id', $user->id)
                      ->orWhere('customer_name', $user->name)
                      ->orderBy('created_at', 'desc')
                      ->get();

        return view('user.profile', compact('user', 'favorites', 'orders'));
    }

    public function update(Request $request)
    {
        $user = Auth::user();
        $request->validate([
            'name'          => 'required|string|max:255',
            'phone'         => 'nullable|string|max:20',
            'address'       => 'nullable|string',
            'profile_photo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only('name', 'phone', 'address');

        if ($request->hasFile('profile_photo')) {
            $filename = 'profile_' . $user->id . '_' . time() . '.' . $request->profile_photo->extension();
            $request->profile_photo->move(public_path('profiles'), $filename);
            $data['profile_photo_path'] = '/profiles/' . $filename;
        }

        $user->update($data);

        return back()->with('success', 'Profile updated successfully!');
    }
}
