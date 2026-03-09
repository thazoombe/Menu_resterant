<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Menu;
use Illuminate\Support\Facades\Auth;

class FavoriteController extends Controller
{
    public function toggle(Request $request, $id)
    {
        if (!Auth::check()) {
            return response()->json(['error' => 'Login required'], 401);
        }

        $user = Auth::user();
        $menu = Menu::findOrFail($id);

        if ($user->favorites()->where('menu_id', $id)->exists()) {
            $user->favorites()->detach($id);
            $favorited = false;
        } else {
            $user->favorites()->attach($id);
            $favorited = true;
        }

        return response()->json(['success' => true, 'favorited' => $favorited]);
    }
}
