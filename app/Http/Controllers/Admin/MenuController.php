<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use Illuminate\Http\Request;

use App\Models\Category;

class MenuController extends Controller
{

    public function index()
    {
        $menus = Menu::with('category')->get();
        return view('admin.menu.index', compact('menus'));
    }

    public function dashboard()
    {
        $today = now()->startOfDay();
        $month = now()->startOfMonth();

        $salesToday = \App\Models\Order::where('status', 'completed')
            ->where('created_at', '>=', $today)
            ->sum('total_price');

        $monthlyRevenue = \App\Models\Order::where('status', 'completed')
            ->where('created_at', '>=', $month)
            ->sum('total_price');

        $monthlyExpenses = \App\Models\Expense::where('date', '>=', $month)
            ->sum('amount');

        $recentOrders = \App\Models\Order::with('items.menu')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $recentExpenses = \App\Models\Expense::orderBy('date', 'desc')
            ->take(5)
            ->get();

        $netProfit = $monthlyRevenue - $monthlyExpenses;

        // --- Chart Data ---
        // Daily sales for the last 30 days
        $last30Days = collect(range(29, 0))->map(function ($daysAgo) {
            $date = now()->subDays($daysAgo)->toDateString();
            $revenue = \App\Models\Order::where('status', 'completed')
                ->whereDate('created_at', $date)
                ->sum('total_price');
            return ['date' => now()->subDays($daysAgo)->format('M d'), 'revenue' => (float)$revenue];
        });
        $dailySalesLabels = $last30Days->pluck('date');
        $dailySalesData   = $last30Days->pluck('revenue');

        // Top 10 selling foods (all time by quantity)
        $topFoods = \App\Models\OrderItem::with('menu')
            ->select('menu_id', \Illuminate\Support\Facades\DB::raw('SUM(quantity) as total_qty'))
            ->groupBy('menu_id')
            ->orderByDesc('total_qty')
            ->take(10)
            ->get()
            ->filter(fn($item) => $item->menu)
            ->values();
        $topFoodLabels = $topFoods->map(fn($i) => $i->menu->name);
        $topFoodData   = $topFoods->pluck('total_qty');

        return view('admin.dashboard', compact(
            'salesToday',
            'monthlyRevenue',
            'monthlyExpenses',
            'netProfit',
            'recentOrders',
            'recentExpenses',
            'dailySalesLabels',
            'dailySalesData',
            'topFoodLabels',
            'topFoodData'
        ));
    }

    public function updateOrderStatus(Request $request, $id)
    {
        $order = \App\Models\Order::findOrFail($id);
        $order->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'Order status updated!');
    }

    public function expenses()
    {
        $expenses = \App\Models\Expense::orderBy('date', 'desc')->get();
        return view('admin.expenses', compact('expenses'));
    }

    public function storeExpense(Request $request)
    {
        $request->validate([
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'category' => 'required|string',
            'date' => 'required|date'
        ]);

        \App\Models\Expense::create($request->all());
        return redirect()->back()->with('success', 'Expense recorded!');
    }

    public function deleteExpense($id)
    {
        \App\Models\Expense::destroy($id);
        return redirect()->back()->with('success', 'Expense deleted!');
    }

    public function create()
    {
        $categories = Category::all();
        return view('admin.menu.create_menu', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        
        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('menus'), $imageName);
            $data['image_path'] = '/menus/'.$imageName;
        }

        $data['is_new'] = $request->has('is_new');
        $data['is_popular'] = $request->has('is_popular');
        $data['is_promotion'] = $request->has('is_promotion');

        unset($data['image']);
        Menu::create($data);
        return redirect('/admin/menu')->with('success', 'Menu item created successfully!');
    }

    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        $categories = Category::all();
        return view('admin.menu.edit', compact('menu', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $menu = Menu::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('image')) {
            $imageName = time().'.'.$request->image->extension();  
            $request->image->move(public_path('menus'), $imageName);
            $data['image_path'] = '/menus/'.$imageName;
        }

        $data['is_new'] = $request->has('is_new');
        $data['is_popular'] = $request->has('is_popular');
        $data['is_promotion'] = $request->has('is_promotion');

        unset($data['image']);
        $menu->update($data);

        return redirect('/admin/menu')->with('success', 'Menu item updated successfully!');
    }

    public function destroy($id)
    {
        Menu::destroy($id);

        return redirect('/admin/menu')->with('success', 'Menu item deleted successfully!');
    }

}