<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class PaymentIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Mock Settings
        Setting::create(['key' => 'aba_merchant_id', 'value' => 'TEST_MERCHANT']);
        Setting::create(['key' => 'aba_api_key', 'value' => 'TEST_KEY']);
        Setting::create(['key' => 'aba_api_url', 'value' => 'https://checkout-sandbox.payway.com.kh/api/payment-gateway/v1/payments/generate-qr']);
    }

    public function test_can_create_khqr()
    {
        $user = User::factory()->create();
        $order = Order::create([
            'customer_name' => 'John Doe',
            'total_price' => 10.00,
            'status' => 'pending',
            'user_id' => $user->id
        ]);

        // Mock PayWay Success Response
        Http::fake([
            '*/create-nw' => Http::response([
                'status' => 0,
                'qr_image' => 'data:image/png;base64,xxxx',
                'tran_id' => 'QR123456789'
            ], 200)
        ]);
        
        $response = $this->actingAs($user)->postJson(route('payment.khqr.create', $order->id));

        $response->assertStatus(200)
                 ->assertJson(['status' => 0]);
        
        $order->refresh();
        $this->assertNotEmpty($order->aba_tran_id);
    }

    public function test_check_status_paid_logic()
    {
        $user = User::factory()->create();
        $order = Order::create([
            'customer_name' => 'John Doe',
            'total_price' => 10.00,
            'status' => 'pending',
            'aba_tran_id' => 'QR12345',
            'user_id' => $user->id
        ]);

        // Mock PayWay Status Paid
        Http::fake([
            '*/check-transaction' => Http::response([
                'status' => 0,
                'description' => 'Success'
            ], 200)
        ]);

        $response = $this->actingAs($user)->getJson(route('payment.khqr.status', $order->id));
        $response->assertStatus(200)
                 ->assertJson(['status' => 'paid']);
        
        $order->refresh();
        $this->assertEquals('paid', $order->status);
    }
}
