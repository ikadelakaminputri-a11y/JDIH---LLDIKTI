<?php

namespace Database\Factories;

use App\Models\Category;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegulationFactory extends Factory
{
    public function definition(): array
    {
        $year = $this->faker->numberBetween(2018, 2025);

        $titles = [
            'Peraturan tentang Standar Nasional Pendidikan Tinggi',
            'Pedoman Penyelenggaraan Program Studi',
            'Tata Cara Akreditasi Perguruan Tinggi',
            'Petunjuk Teknis Pemberian Beasiswa',
            'Peraturan tentang Penerimaan Mahasiswa Baru',
            'Pedoman Pengelolaan Keuangan Perguruan Tinggi',
            'Tata Kelola Sumber Daya Manusia di Lingkungan LLDIKTI',
            'Pedoman Pengembangan Kurikulum Pendidikan Tinggi',
            'Peraturan tentang Penelitian dan Pengabdian Masyarakat',
            'Petunjuk Teknis Izin Operasional Perguruan Tinggi Swasta',
            'Peraturan tentang Sistem Penjaminan Mutu Internal',
            'Pedoman Penyelenggaraan Pendidikan Jarak Jauh',
            'Tata Cara Pengajuan Hibah Penelitian',
            'Peraturan tentang Kerja Sama Perguruan Tinggi',
            'Pedoman Pembinaan Kemahasiswaan',
        ];

        $number = sprintf('%02d', $this->faker->numberBetween(1, 99)) . '/' . $year;

        return [
            'category_id' => Category::inRandomOrder()->first()?->id ?? Category::factory(),
            'title'        => $this->faker->unique()->randomElement($titles),
            'number'       => $number,
            // slug di-generate otomatis oleh Model boot()
            'year'         => $year,
            'status'       => $this->faker->randomElement(['published', 'unpublished']),
        ];
    }

    public function published(): static
    {
        return $this->state(['status' => 'published']);
    }

    public function unpublished(): static
    {
        return $this->state(['status' => 'unpublished']);
    }
}
