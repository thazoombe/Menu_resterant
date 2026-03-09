<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Menu;
use App\Models\Order;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExportController extends Controller
{
    public function exportOrders()
    {
        $fileName = 'orders_' . date('Y-m-d') . '.csv';
        $orders = Order::all();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('Order ID', 'Customer', 'Total', 'Status', 'Date');

        $callback = function() use($orders, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($orders as $order) {
                $row['Order ID'] = $order->id;
                $row['Customer'] = $order->customer_name;
                $row['Total']    = $order->total_price;
                $row['Status']   = $order->status;
                $row['Date']     = $order->created_at;

                fputcsv($file, array($row['Order ID'], $row['Customer'], $row['Total'], $row['Status'], $row['Date']));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function exportExpenses()
    {
        $fileName = 'expenses_' . date('Y-m-d') . '.csv';
        $expenses = Expense::all();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('ID', 'Date', 'Description', 'Category', 'Amount');

        $callback = function() use($expenses, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);

            foreach ($expenses as $expense) {
                fputcsv($file, array($expense->id, $expense->date, $expense->description, $expense->category, $expense->amount));
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printOrders()
    {
        $orders = Order::with('items.menu')->get();
        return view('admin.export.orders_print', compact('orders'));
    }

    public function printExpenses()
    {
        $expenses = Expense::all();
        return view('admin.export.expenses_print', compact('expenses'));
    }

    public function exportMenu()
    {
        $fileName = 'menu_' . date('Y-m-d') . '.csv';
        $menus = Menu::with('category')->get();

        $headers = array(
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        );

        $columns = array('ID', 'Name', 'Category', 'Price', 'Description');

        $callback = function() use($menus, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($menus as $menu) {
                fputcsv($file, array($menu->id, $menu->name, $menu->category->name ?? 'N/A', $menu->price, $menu->description));
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function printMenu()
    {
        $menus = Menu::with('category')->get();
        return view('admin.export.menu_print', compact('menus'));
    }
}
