<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            'Undang-Undang',
            'Peraturan Pemerintah',
            'Peraturan Presiden',
            'Peraturan Menteri',
            'Keputusan Menteri',
            'Surat Edaran',
            'Keputusan Direktur Jenderal',
            'Instruksi Menteri',
        ];

        foreach ($categories as $name) {
            Category::firstOrCreate(
                ['slug' => Str::slug($name)],
                ['name' => $name]
            );
        }
    }
}
