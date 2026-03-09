<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        Category::create(['name' => 'Main Course']);
        Category::create(['name' => 'Appetizers']);
        Category::create(['name' => 'Drinks']);
        Category::create(['name' => 'Desserts']);
    }
}
