<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class PaymentController extends Controller
{
    public function checkoutABA($orderId)
    {
        $order = Order::with('items.menu')->findOrFail($orderId);
        $settings = Setting::pluck('value', 'key');

        $merchantId = $settings['aba_merchant_id'] ?? '';
        $apiKey = $settings['aba_api_key'] ?? '';
        $apiUrl = $settings['aba_api_url'] ?? 'https://checkout.ababank.com/api/payment-gateway/v1/payments';

        if (empty($merchantId) || empty($apiKey)) {
            return back()->with('error', 'ABA PayWay credentials not configured.');
        }

        $reqTime = date('YmdHis');
        $tranId = 'ORD-' . str_pad($order->id, 5, '0', STR_PAD_LEFT);
        $amount = number_format($order->total_price, 2, '.', '');
        
        $firstName = $order->customer_name;
        $lastName = 'Customer'; 
        $email = $order->user ? $order->user->email : 'customer@example.com';
        $phone = $settings['phone'] ?? '012345678';

        $type = 'purchase';
        $paymentOption = 'abapay';
        $returnUrl = url('/order/invoice/' . $order->id);
        $cancelUrl = url('/order/invoice/' . $order->id);
        $continueSuccessUrl = url('/order/invoice/' . $order->id);

        $items = base64_encode(json_encode($order->items->map(function($item) {
            return [
                'name' => $item->menu->name ?? 'Item',
                'quantity' => (string)$item->quantity,
                'price' => number_format($item->price, 2, '.', ''),
            ];
        })->toArray()));

        $hashString = $reqTime . $merchantId . $tranId . $amount . $firstName . $lastName . $email . $phone . $type . $paymentOption . $returnUrl . $cancelUrl . $continueSuccessUrl;
        $hash = base64_encode(hash_hmac('sha512', $hashString, $apiKey, true));

        return view('payment.aba_checkout', compact(
            'order', 'merchantId', 'apiUrl', 'reqTime', 'tranId', 'amount', 
            'firstName', 'lastName', 'email', 'phone', 'hash', 'type', 
            'paymentOption', 'returnUrl', 'cancelUrl', 'continueSuccessUrl', 'items'
        ));
    }

    public function createKHQR(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $settings = Setting::pluck('value', 'key');

        $merchantId = $settings['aba_merchant_id'] ?? '';
        $apiKey = $settings['aba_api_key'] ?? '';
        $apiUrl = $settings['aba_api_url'] ?? 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/generate-qr';

        if (empty($merchantId) || empty($apiKey)) {
            return response()->json(['error' => 'ABA PayWay credentials not configured.'], 400);
        }

        date_default_timezone_set('Asia/Phnom_Penh');
        $reqTime = date('YmdHis');
        $tranId = 'QR' . time() . $order->id; 
        $amount = number_format($order->total_price, 2, '.', '');
        
        $firstName = $order->customer_name;
        $lastName = 'Customer';
        $email = $order->user ? $order->user->email : 'customer@example.com';
        $phone = $settings['phone'] ?? '012345678';
        $type = 'purchase';
        $paymentOption = 'abapay'; // Updated to abapay for QR flow

        // Full hash for ABA PayWay: req_time + merchant_id + tran_id + amount + firstname + lastname + email + phone + type + payment_option
        $hashString = $reqTime . $merchantId . $tranId . $amount . $firstName . $lastName . $email . $phone . $type . $paymentOption;
        $hash = base64_encode(hash_hmac('sha512', $hashString, $apiKey, true));

        $data = [
            'req_time' => $reqTime,
            'merchant_id' => $merchantId,
            'tran_id' => $tranId,
            'amount' => $amount,
            'firstname' => $firstName,
            'lastname' => $lastName,
            'email' => $email,
            'phone' => $phone,
            'type' => $type,
            'payment_option' => $paymentOption,
            'hash' => $hash,
        ];

        \Log::info('ABA QR Request Trace:', ['url' => $apiUrl, 'data' => $data]);

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::asForm()->post($apiUrl, $data);
            $result = $response->json();
            
            \Log::info('ABA QR Debug:', ['url' => $apiUrl, 'result' => $result]);

            // Try fallback to purchase endpoint if generate-qr fails with merchant error
            if (isset($result['status']) && $result['status'] == 1 && strpos($apiUrl, 'generate-qr') !== false) {
                \Log::info('generate-qr failed, trying purchase endpoint fallback...');
                $purchaseUrl = str_replace('generate-qr', 'purchase', $apiUrl);
                $response = Http::asForm()->post($purchaseUrl, $data);
                $result = $response->json();
                \Log::info('ABA QR Purchase Fallback Debug:', ['url' => $purchaseUrl, 'result' => $result]);
            }

            if (isset($result['status']) && ($result['status'] == 0 || $result['status'] == '00')) {
                $order->update(['aba_tran_id' => $result['tran_id'] ?? '']);
                $result['amount'] = number_format($order->total_price, 2);
            }

            return response()->json($result);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function checkTransactionStatus($orderId)
    {
        $order = Order::findOrFail($orderId);
        
        if ($order->status === 'paid' || $order->status === 'completed') {
            return response()->json(['status' => 'paid']);
        }

        $settings = Setting::pluck('value', 'key');
        $merchantId = $settings['aba_merchant_id'] ?? '';
        $apiKey = $settings['aba_api_key'] ?? '';
        $apiUrl = $settings['aba_check_status_url'] ?? 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/check-transaction';

        if (empty($merchantId) || empty($apiKey) || empty($order->aba_tran_id)) {
            return response()->json(['status' => 'pending']);
        }

        date_default_timezone_set('Asia/Phnom_Penh');
        $reqTime = date('YmdHis');
        $hashString = $reqTime . $merchantId . $order->aba_tran_id;
        $hash = base64_encode(hash_hmac('sha512', $hashString, $apiKey, true));

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::asForm()->post($apiUrl, [
                'req_time' => $reqTime,
                'merchant_id' => $merchantId,
                'tran_id' => $order->aba_tran_id,
                'hash' => $hash,
            ]);

            $result = $response->json();

            if (isset($result['status']) && $result['status'] == 0) {
                $order->update(['status' => 'paid']);
                return response()->json(['status' => 'paid']);
            }

            return response()->json(['status' => 'pending', 'details' => $result]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    public function handleCallback(Request $request)
    {
        // PayWay will send POST request to this URL
        // The payload fields can vary based on the specific ABA PayWay API version/mode
        // Based on user provided sample, we check merchant_ref and payment_status_code
        
        $tranId = $request->input('merchant_ref') ?? $request->input('tran_id') ?? $request->input('transaction_id');
        $status = $request->input('payment_status_code') ?? $request->input('status');
        
        if ($status == 0) {
            $order = Order::where('aba_tran_id', $tranId)->first();
            if ($order && $order->status !== 'paid' && $order->status !== 'completed') {
                $order->update(['status' => 'paid']);
            }
        }

        return response()->json(['status' => 'ok']);
    }

    public function refundTransaction($orderId)
    {
        $order = Order::findOrFail($orderId);
        $settings = Setting::pluck('value', 'key');
        
        $merchantId = $settings['aba_merchant_id'] ?? '';
        $apiKey = $settings['aba_api_key'] ?? '';
        $apiUrl = $settings['aba_refund_url'] ?? 'https://checkout-sandbox.payway.com.kh/api/merchant-portal/merchant-access/online-transaction/refund';

        if (empty($merchantId) || empty($apiKey) || empty($order->aba_tran_id)) {
            return back()->with('error', 'Refund credentials or Transaction ID missing.');
        }

        $reqTime = date('YmdHis');
        $amount = number_format($order->total_price, 2, '.', '');
        
        // Hash for refund: req_time + merchant_id + tran_id + amount
        $hashString = $reqTime . $merchantId . $order->aba_tran_id . $amount;
        $hash = base64_encode(hash_hmac('sha512', $hashString, $apiKey, true));

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::asForm()->post($apiUrl, [
                'req_time' => $reqTime,
                'merchant_id' => $merchantId,
                'tran_id' => $order->aba_tran_id,
                'amount' => $amount,
                'hash' => $hash,
            ]);

            $result = $response->json();

            if (isset($result['status']) && $result['status'] == 0) {
                $order->update(['status' => 'refunded']);
                return back()->with('success', 'Transaction refunded successfully!');
            }

            return back()->with('error', 'Refund failed: ' . ($result['description'] ?? 'Unknown error'));
        } catch (\Exception $e) {
            return back()->with('error', 'Refund error: ' . $e->getMessage());
        }
    }

    public function createPaymentLink(Request $request, $orderId)
    {
        $order = Order::findOrFail($orderId);
        $settings = Setting::pluck('value', 'key');
        
        $merchantId = $settings['aba_merchant_id'] ?? '';
        $apiKey = $settings['aba_api_key'] ?? '';
        $apiUrl = $settings['aba_payment_link_url'] ?? 'https://checkout-sandbox.payway.com.kh/api/merchant-portal/merchant-access/payment-link/create';

        if (empty($merchantId) || empty($apiKey)) {
            return response()->json(['error' => 'API credentials not configured.'], 400);
        }

        $reqTime = date('YmdHis');
        $tranId = 'LNK' . time() . $order->id;
        $amount = number_format($order->total_price, 2, '.', '');
        
        $hashString = $reqTime . $merchantId . $tranId . $amount;
        $hash = base64_encode(hash_hmac('sha512', $hashString, $apiKey, true));

        try {
            /** @var \Illuminate\Http\Client\Response $response */
            $response = Http::asForm()->post($apiUrl, [
                'req_time' => $reqTime,
                'merchant_id' => $merchantId,
                'tran_id' => $tranId,
                'amount' => $amount,
                'hash' => $hash,
            ]);

            return response()->json($response->json());
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
