<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class CategoryFactory extends Factory
{
    public function definition(): array
    {
        $categories = [
            'Peraturan Menteri',
            'Surat Edaran',
            'Keputusan Direktur Jenderal',
            'Peraturan Pemerintah',
            'Undang-Undang',
            'Peraturan Presiden',
            'Keputusan Menteri',
            'Instruksi Menteri',
        ];

        $name = $this->faker->unique()->randomElement($categories);

        return [
            'name' => $name,
            'slug' => Str::slug($name),
        ];
    }
}
