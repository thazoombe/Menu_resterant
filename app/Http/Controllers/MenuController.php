<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MenuController extends Controller
{
    public function landing()
    {
        $appSettings = \App\Models\Setting::pluck('value', 'key');
        return view('welcome', compact('appSettings'));
    }

    public function index()
    {
        $menus = Menu::with('category')->get();
        // Group menus by category for the sectioned view
        $groupedMenus = $menus->groupBy('category_id');
        $categories = \App\Models\Category::all();
        $featuredItem = Menu::where('is_promotion', true)->orWhere('is_new', true)->first();
        $appSettings = \App\Models\Setting::pluck('value', 'key');
        return view('menu', compact('groupedMenus', 'categories', 'featuredItem', 'appSettings'));
    }

    public function show($id)
    {
        $menu = Menu::with(['category', 'images', 'reviews.user'])
                    ->findOrFail($id);
                    
        $relatedMenus = Menu::where('category_id', $menu->category_id)
                            ->where('id', '!=', $id)
                            ->take(4)
                            ->get();

        $appSettings = \App\Models\Setting::pluck('value', 'key');

        return view('menu.show', compact('menu', 'relatedMenus', 'appSettings'));
    }

    public function storeReview(Request $request, $id)
    {
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000'
        ]);

        \App\Models\MenuReview::create([
            'menu_id' => $id,
            'user_id' => Auth::id(),
            'rating' => $request->rating,
            'comment' => $request->comment
        ]);

        return redirect()->back()->with('success', 'Review submitted successfully!');
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

    public function invoice($id)
    {
        $order = \App\Models\Order::with('items.menu')->findOrFail($id);
        $appSettings = \App\Models\Setting::pluck('value', 'key');
        return view('order.invoice', compact('order', 'appSettings'));
    }

    public function about()
    {
        $aboutItems = \App\Models\AboutItem::orderBy('order')->get();
        $appSettings = \App\Models\Setting::pluck('value', 'key');
        return view('about', compact('aboutItems', 'appSettings'));
    }
}