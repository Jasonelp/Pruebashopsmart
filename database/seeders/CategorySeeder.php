<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run()
    {
        $categories = [
            ['name' => 'Smartphones', 'description' => 'Los mejores smartphones del mercado'],
            ['name' => 'Computadoras', 'description' => 'Laptops y PCs de última generación'],
            ['name' => 'Cámaras', 'description' => 'Cámaras profesionales y accesorios'],
            ['name' => 'Relojes', 'description' => 'Relojes inteligentes y clásicos'],
            ['name' => 'Auriculares', 'description' => 'Audio de alta calidad'],
            ['name' => 'Tablets', 'description' => 'Tablets para trabajo y entretenimiento'],
        ];

        foreach ($categories as $category) {
            Category::create($category);
        }
    }
}
