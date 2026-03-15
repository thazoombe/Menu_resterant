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
        'about_restaurant'=> 'Welcome to our restaurant. We take pride in serving the best food in town.',
        'logo_path'       => '',
        'default_theme'   => 'light',
        'enable_translation'=> 'yes',
        'bank_name'       => 'ABA Bank',
        'account_name'    => 'LAY VANNTHA',
        'account_number'  => '123 456 789',
        'payment_qr_path' => '',
        'aba_merchant_id' => '',
        'aba_api_key'     => '',
        'aba_api_url'     => 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/generate-qr',
        'aba_check_status_url' => 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/check-transaction',
        'aba_refund_url'  => 'https://checkout-sandbox.payway.com.kh/api/merchant-portal/merchant-access/online-transaction/refund',
        'aba_payment_link_url' => 'https://checkout-sandbox.payway.com.kh/api/merchant-portal/merchant-access/payment-link/create',
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

        if ($request->hasFile('payment_qr_code')) {
            $qrFilename = 'qr_' . time() . '.' . $request->payment_qr_code->extension();
            $request->payment_qr_code->move(public_path('images'), $qrFilename);
            $data['payment_qr_path'] = '/images/' . $qrFilename;
        }

        foreach ($data as $key => $value) {
            Setting::updateOrCreate(['key' => $key], ['value' => $value ?? '']);
        }

        return back()->with('success', 'Settings saved successfully!');
    }
}
