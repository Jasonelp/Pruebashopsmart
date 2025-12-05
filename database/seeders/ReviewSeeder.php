<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Review;
use Carbon\Carbon;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $reviews = [
            [
                'product_id' => 1,
                'user_id' => 3,
                'rating' => 5,
                'comment' => 'Excelente teléfono, la cámara es increíble y la batería dura todo el día.',
                'created_at' => Carbon::now()->subDays(3),
                'updated_at' => Carbon::now()->subDays(3),
            ],
            [
                'product_id' => 1,
                'user_id' => 4,
                'rating' => 4,
                'comment' => 'Muy buen producto, aunque el precio es elevado. Vale la pena.',
                'created_at' => Carbon::now()->subDays(2),
                'updated_at' => Carbon::now()->subDays(2),
            ],
            [
                'product_id' => 3,
                'user_id' => 3,
                'rating' => 5,
                'comment' => 'Increíble relación calidad-precio. La cámara de 200MP es espectacular.',
                'created_at' => Carbon::now()->subDays(4),
                'updated_at' => Carbon::now()->subDays(4),
            ],
            [
                'product_id' => 15,
                'user_id' => 4,
                'rating' => 5,
                'comment' => 'Los mejores auriculares que he tenido. La cancelación de ruido es perfecta.',
                'created_at' => Carbon::now()->subDays(1),
                'updated_at' => Carbon::now()->subDays(1),
            ],
            [
                'product_id' => 16,
                'user_id' => 3,
                'rating' => 5,
                'comment' => 'Calidad de audio excepcional. Muy cómodos para uso prolongado.',
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'product_id' => 5,
                'user_id' => 4,
                'rating' => 5,
                'comment' => 'La MacBook Pro M3 es una bestia. Perfecta para desarrollo y diseño.',
                'created_at' => Carbon::now()->subDays(6),
                'updated_at' => Carbon::now()->subDays(6),
            ],
            [
                'product_id' => 12,
                'user_id' => 3,
                'rating' => 4,
                'comment' => 'Buen smartwatch, aunque esperaba más funciones de salud.',
                'created_at' => Carbon::now()->subDays(7),
                'updated_at' => Carbon::now()->subDays(7),
            ],
        ];

        foreach ($reviews as $reviewData) {
            Review::create($reviewData);
        }
    }
}
