<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llamar a todos los seeders en el orden correcto
        $this->call([
            UserSeeder::class,      // Primero los usuarios
            CategorySeeder::class,  // Luego las categorías
            ProductSeeder::class,   // Productos (dependen de categorías y usuarios)
            OrderSeeder::class,     // Pedidos (dependen de usuarios y productos)
            ReviewSeeder::class,    // Reseñas (dependen de usuarios y productos)
        ]);
    }
}
