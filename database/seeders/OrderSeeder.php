<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use Carbon\Carbon;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Pedido 1 - Completado (hace 5 días)
        $order1 = Order::create([
            'user_id' => 3,
            'total' => 5798.00,
            'status' => 'completado',
            'shipping_address' => 'Av. Principal 123, Ciudad de México',
            'notes' => 'Entrega en horario de oficina',
            'created_at' => Carbon::now()->subDays(5),
            'updated_at' => Carbon::now()->subDays(5),
        ]);

        // Productos del pedido 1
        $order1->products()->attach(1, [
            'quantity' => 1,
            'price' => 4899.00,
            'created_at' => Carbon::now()->subDays(5),
            'updated_at' => Carbon::now()->subDays(5),
        ]);
        $order1->products()->attach(15, [
            'quantity' => 1,
            'price' => 899.00,
            'created_at' => Carbon::now()->subDays(5),
            'updated_at' => Carbon::now()->subDays(5),
        ]);

        // Pedido 2 - En proceso (hace 2 días)
        $order2 = Order::create([
            'user_id' => 4,
            'total' => 2198.00,
            'status' => 'en_proceso',
            'shipping_address' => 'Calle Secundaria 456, Guadalajara',
            'notes' => null,
            'created_at' => Carbon::now()->subDays(2),
            'updated_at' => Carbon::now()->subDays(2),
        ]);

        // Productos del pedido 2
        $order2->products()->attach(16, [
            'quantity' => 1,
            'price' => 1299.00,
            'created_at' => Carbon::now()->subDays(2),
            'updated_at' => Carbon::now()->subDays(2),
        ]);
        $order2->products()->attach(3, [
            'quantity' => 1,
            'price' => 899.00,
            'created_at' => Carbon::now()->subDays(2),
            'updated_at' => Carbon::now()->subDays(2),
        ]);

        // Pedido 3 - Pendiente (hoy)
        $order3 = Order::create([
            'user_id' => 3,
            'total' => 8999.00,
            'status' => 'pendiente',
            'shipping_address' => 'Av. Principal 123, Ciudad de México',
            'notes' => 'Llamar antes de entregar',
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);

        // Productos del pedido 3
        $order3->products()->attach(5, [
            'quantity' => 1,
            'price' => 8999.00,
            'created_at' => Carbon::now(),
            'updated_at' => Carbon::now(),
        ]);
    }
}
