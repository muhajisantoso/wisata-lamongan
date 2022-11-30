<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\File;
use Illuminate\Support\Facades\Storage;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Acara>
 */
class AcaraFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'kategori_id' => Kategori::factory()->create()->id,
            'name' => fake()->words(3, true),
            'deskripsi' => fake()->text(),
            'gambar' => null,
            'tanggal' => fake()->date('Y-m-d')
        ];
    }
}
