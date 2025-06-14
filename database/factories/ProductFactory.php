<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    { {
            // Daftar produk iPhone yang bisa dibuat secara acak
            $iphoneProducts = [
                'iPhone 15 Pro Max 256GB Natural Titanium' => 22500000,
                'iPhone 15 Pro 128GB Blue Titanium' => 18500000,
                'iPhone 15 128GB Pink' => 14000000,
                'iPhone 14 Plus 256GB Midnight' => 13500000,
                'iPhone SE (Gen 3) 64GB Starlight' => 7500000,
            ];

            // Pilih satu produk secara acak dari daftar
            $name = array_rand($iphoneProducts);
            $price = $iphoneProducts[$name];

            return [
                'name' => $name,
                'slug' => Str::slug($name), // Membuat slug otomatis
                'description' => fake()->paragraph(2), // Deskripsi singkat
                'price' => $price,
                'stock' => fake()->numberBetween(15, 100), // Stok acak
                'image' => null, // Biarkan kosong atau isi dengan URL online jika diinginkan
            ];
        }
    }
}
