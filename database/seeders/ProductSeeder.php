<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    private function getPlaceholderImage(int $categoryId, int $index): string
    {
        $colors = [
            1 => '3498db', // Smartphones - azul
            2 => '2ecc71', // Computadoras - verde
            3 => 'e74c3c', // Cámaras - rojo
            4 => '9b59b6', // Relojes - morado
            5 => 'f39c12', // Auriculares - naranja
            6 => '1abc9c', // Tablets - turquesa
        ];

        $color = $colors[$categoryId] ?? '95a5a6';
        return "https://placehold.co/600x600/{$color}/ffffff?text=Product+{$index}";
    }

    public function run()
    {
        $products = [
            // Smartphones
            [
                'name' => 'iPhone 15 Pro - 256GB Titanio Natural',
                'description' => 'El smartphone más avanzado de Apple con chip A17 Pro y cámara de 48MP',
                'price' => 4899.00,
                'stock' => 25,
                'category_id' => 1,
                'user_id' => 2,
            ],
            [
                'name' => 'Samsung Galaxy S24 Ultra - 512GB',
                'description' => 'Potencia extrema con S Pen integrado y pantalla Dynamic AMOLED 2X',
                'price' => 4299.00,
                'stock' => 18,
                'category_id' => 1,
                'user_id' => 2,
            ],
            [
                'name' => 'Xiaomi Redmi Note 13 Pro - 256GB',
                'description' => 'Excelente relación calidad-precio con cámara de 200MP',
                'price' => 899.00,
                'stock' => 45,
                'category_id' => 1,
                'user_id' => 5,
            ],
            [
                'name' => 'Google Pixel 8 Pro - 128GB',
                'description' => 'Fotografía computacional de Google con IA avanzada',
                'price' => 3499.00,
                'stock' => 30,
                'category_id' => 1,
                'user_id' => 2,
            ],

            // Computadoras
            [
                'name' => 'MacBook Pro 16" M3 Pro',
                'description' => 'Rendimiento profesional para creativos y desarrolladores',
                'price' => 8999.00,
                'stock' => 12,
                'category_id' => 2,
                'user_id' => 2,
            ],
            [
                'name' => 'Dell XPS 15 - Intel i9',
                'description' => 'Laptop premium con pantalla OLED 4K',
                'price' => 6499.00,
                'stock' => 15,
                'category_id' => 2,
                'user_id' => 5,
            ],
            [
                'name' => 'Lenovo ThinkPad X1 Carbon',
                'description' => 'Ultraligera y perfecta para negocios',
                'price' => 5299.00,
                'stock' => 20,
                'category_id' => 2,
                'user_id' => 5,
            ],
            [
                'name' => 'ASUS ROG Zephyrus G14',
                'description' => 'Gaming portátil con AMD Ryzen 9 y RTX 4060',
                'price' => 4999.00,
                'stock' => 10,
                'category_id' => 2,
                'user_id' => 2,
            ],

            // Cámaras
            [
                'name' => 'Canon EOS R6 Mark II',
                'description' => 'Cámara mirrorless full-frame de 24MP',
                'price' => 9499.00,
                'stock' => 8,
                'category_id' => 3,
                'user_id' => 2,
            ],
            [
                'name' => 'Sony Alpha A7 IV',
                'description' => 'Versátil cámara híbrida para foto y video',
                'price' => 8999.00,
                'stock' => 10,
                'category_id' => 3,
                'user_id' => 5,
            ],
            [
                'name' => 'Nikon Z8',
                'description' => 'Cámara profesional con sensor de 45.7MP',
                'price' => 12999.00,
                'stock' => 5,
                'category_id' => 3,
                'user_id' => 2,
            ],

            // Relojes
            [
                'name' => 'Apple Watch Series 9 GPS',
                'description' => 'El reloj inteligente más popular del mundo',
                'price' => 1599.00,
                'stock' => 35,
                'category_id' => 4,
                'user_id' => 2,
            ],
            [
                'name' => 'Samsung Galaxy Watch 6 Classic',
                'description' => 'Elegancia y tecnología en tu muñeca',
                'price' => 1299.00,
                'stock' => 28,
                'category_id' => 4,
                'user_id' => 5,
            ],
            [
                'name' => 'Garmin Fenix 7X',
                'description' => 'Reloj deportivo premium con GPS multibanda',
                'price' => 2999.00,
                'stock' => 15,
                'category_id' => 4,
                'user_id' => 2,
            ],

            // Auriculares
            [
                'name' => 'AirPods Pro 2da Generación',
                'description' => 'Cancelación de ruido adaptativa y audio espacial',
                'price' => 899.00,
                'stock' => 50,
                'category_id' => 5,
                'user_id' => 2,
            ],
            [
                'name' => 'Sony WH-1000XM5',
                'description' => 'Los mejores auriculares con cancelación de ruido',
                'price' => 1299.00,
                'stock' => 30,
                'category_id' => 5,
                'user_id' => 5,
            ],
            [
                'name' => 'Bose QuietComfort Ultra',
                'description' => 'Audio inmersivo con cancelación de ruido premium',
                'price' => 1499.00,
                'stock' => 25,
                'category_id' => 5,
                'user_id' => 2,
            ],

            // Tablets
            [
                'name' => 'iPad Pro 12.9" M2',
                'description' => 'La tablet más potente con chip M2',
                'price' => 4499.00,
                'stock' => 15,
                'category_id' => 6,
                'user_id' => 2,
            ],
            [
                'name' => 'Samsung Galaxy Tab S9 Ultra',
                'description' => 'Pantalla AMOLED gigante de 14.6 pulgadas',
                'price' => 3999.00,
                'stock' => 12,
                'category_id' => 6,
                'user_id' => 5,
            ],
            [
                'name' => 'Microsoft Surface Pro 9',
                'description' => 'Tablet 2 en 1 con Windows 11',
                'price' => 3299.00,
                'stock' => 18,
                'category_id' => 6,
                'user_id' => 2,
            ],
        ];

        foreach ($products as $index => $product) {
            $product['image'] = $this->getPlaceholderImage($product['category_id'], $index + 1);
            Product::create($product);
        }
    }
}
