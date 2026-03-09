<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Menu;

class MenuSeeder extends Seeder
{
    public function run()
    {
        $items = [
            [
                'name' => 'Truffle Wagyu Steak',
                'description' => 'Premium wagyu beef with black truffle sauce and roasted vegetables.',
                'price' => 45.00,
                'category_id' => 1,
                'is_popular' => true,
                'image_path' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?auto=format&fit=crop&w=800&q=80'
            ],
            [
                'name' => 'Lobster Pasta',
                'description' => 'Butter-poached lobster with handmade tagliatelle in a lemon garlic sauce.',
                'price' => 38.00,
                'category_id' => 1,
                'is_new' => true,
                'image_path' => 'https://images.unsplash.com/photo-1551183053-bf91a1d81141?auto=format&fit=crop&w=800&q=80'
            ],
            [
                'name' => 'Crispy Calamari',
                'description' => 'Lightly breaded squid rings with spicy aioli dip.',
                'price' => 14.00,
                'category_id' => 2,
                'is_popular' => true,
                'image_path' => 'https://images.unsplash.com/photo-1599487488170-d11ec9c172f0?auto=format&fit=crop&w=800&q=80'
            ],
            [
                'name' => 'Spicy Margarita',
                'description' => 'Tequila, lime juice, agave, and fresh jalapeño with a chili rim.',
                'price' => 12.00,
                'category_id' => 3,
                'is_promotion' => true,
                'image_path' => 'https://images.unsplash.com/photo-1559811814-e2c57b5e69df?auto=format&fit=crop&w=800&q=80'
            ],
            [
                'name' => 'Molten Lava Cake',
                'description' => 'Gooey chocolate center served with premium Madagascar vanilla bean ice cream.',
                'price' => 10.50,
                'category_id' => 4,
                'is_popular' => true,
                'image_path' => 'https://images.unsplash.com/photo-1624353365286-3f8d62daad51?auto=format&fit=crop&w=800&q=80'
            ],
            [
                'name' => 'Seafood Paella',
                'description' => 'Traditional Spanish rice with shrimp, mussels, and saffron.',
                'price' => 32.00,
                'category_id' => 1,
                'is_promotion' => true,
                'image_path' => 'https://images.unsplash.com/photo-1534080564607-c9275445f29c?auto=format&fit=crop&w=800&q=80'
            ],
        ];

        foreach ($items as $item) {
            Menu::updateOrCreate(['name' => $item['name']], $item);
        }
    }
}