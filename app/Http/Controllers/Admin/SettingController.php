<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\AboutItem;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    private $defaults = [
        'restaurant_name' => 'Resto Delights',
        'tagline'         => 'Hand-crafted meals delivered straight to your door.',
        'phone'           => '',
        'email'           => '',
        'address'         => '',
        'currency'        => '$',
        'tax_rate'        => '0',
        'facebook'        => '',
        'instagram'       => '',
        'twitter'         => '',
        'tiktok'          => '',
        'youtube'         => '',
        'telegram'        => '',
        'opening_hours'   => 'Mon–Fri 10:00–22:00',
        'logo_path'       => '',
        'default_theme'   => 'light',
        'enable_translation'=> 'yes',
    ];

    public function index()
    {
        foreach ($this->defaults as $key => $value) {
            Setting::firstOrCreate(['key' => $key], ['value' => $value]);
        }
        $settings = Setting::pluck('value', 'key');
        $aboutItems = AboutItem::orderBy('order')->get();
        return view('admin.settings', compact('settings', 'aboutItems'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', 'logo']);

        if ($request->hasFile('logo')) {
            $filename = 'logo_' . time() . '.' . $request->logo->extension();
            $request->logo->move(public_path('logos'), $filename);
            $data['logo_path'] = '/logos/' . $filename;
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
        }

        return back()->with('success', 'Settings saved successfully!');
    }
}
