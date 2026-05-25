<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Product;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
        // Admin Account
        \App\Models\User::updateOrCreate(['username' => 'admin'], [
            'name' => 'System Admin',
            'password' => \Illuminate\Support\Facades\Hash::make('admin123'),
            'role' => 'Admin',
        ]);

        // Cashier Account
        \App\Models\User::updateOrCreate(['username' => 'cashier'], [
            'name' => 'Jane Cashier',
            'password' => \Illuminate\Support\Facades\Hash::make('cashier123'),
            'role' => 'Cashier',
        ]);

        // Default Categories
        $takoyaki = \App\Models\Category::updateOrCreate(['name' => 'Takoyaki']);
        $drinks = \App\Models\Category::updateOrCreate(['name' => 'Drinks']);
        $addons = \App\Models\Category::updateOrCreate(['name' => 'Add-ons']);

        // Default Products
        \App\Models\Product::create([
            'name' => 'Classic Takoyaki (6pcs)',
            'price' => 85,
            'quantity' => 50,
            'category_id' => $takoyaki->id,
            'expiration_date' => '2025-12-31',
            'image' => 'https://images.unsplash.com/photo-1593560708920-61dd98c46a4e?w=400',
        ]);

        \App\Models\Product::create([
            'name' => 'Iced Tea',
            'price' => 25,
            'quantity' => 100,
            'category_id' => $drinks->id,
            'expiration_date' => '2026-01-15',
            'image' => 'https://images.unsplash.com/photo-1556679343-c7306c1976bc?w=400',
        ]);
    }
}
