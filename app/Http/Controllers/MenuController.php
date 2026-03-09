<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function index()
    {
        $menus = Menu::with('category')->get();
        $featuredItem = Menu::where('is_promotion', true)->orWhere('is_new', true)->first();
        return view('menu', compact('menus', 'featuredItem'));
    }

    public function checkout(\Illuminate\Http\Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'items' => 'required|array|min:1',
            'items.*.id' => 'required|exists:menus,id',
            'items.*.quantity' => 'required|integer|min:1',
            'items.*.price' => 'required|numeric'
        ]);

        \Illuminate\Support\Facades\DB::beginTransaction();

        try {
            $totalPrice = 0;
            foreach ($request->items as $item) {
                $totalPrice += $item['price'] * $item['quantity'];
            }

            $order = \App\Models\Order::create([
                'customer_name' => $request->customer_name,
                'total_price' => $totalPrice,
                'status' => 'pending',
                'user_id' => Auth::id(), // Link to user if logged in
            ]);

            foreach ($request->items as $item) {
                \App\Models\OrderItem::create([
                    'order_id' => $order->id,
                    'menu_id' => $item['id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price']
                ]);
            }

            \Illuminate\Support\Facades\DB::commit();

            return response()->json([
                'success' => true,
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\DB::rollBack();
            return response()->json(['success' => false, 'error' => $e->getMessage()], 500);
        }
    }
}