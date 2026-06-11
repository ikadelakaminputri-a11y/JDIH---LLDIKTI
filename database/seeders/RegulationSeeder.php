<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Regulation;
use App\Models\RegulationRelation;
use App\Models\RegulationVersion;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RegulationSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::where('email', 'admin@lldikti.test')->first();

        if (! $admin) {
            $this->command->error('Admin user not found. Jalankan UserSeeder terlebih dahulu.');
            return;
        }

        $regulations = [
            // ── Undang-Undang ─────────────────────────────────────────
            [
                'title'    => 'Undang-Undang tentang Pendidikan Tinggi',
                'number'   => '12/2012',
                'year'     => 2012,
                'status'   => 'published',
                'category' => 'Undang-Undang',
                'versions' => [
                    ['notes' => 'Versi pertama.', 'downloads' => 120],
                ],
            ],
            [
                'title'    => 'Undang-Undang tentang Sistem Pendidikan Nasional',
                'number'   => '20/2003',
                'year'     => 2003,
                'status'   => 'published',
                'category' => 'Undang-Undang',
                'versions' => [
                    ['notes' => 'Versi pertama.', 'downloads' => 95],
                ],
            ],

            // ── Peraturan Pemerintah ───────────────────────────────────
            [
                'title'    => 'Peraturan Pemerintah tentang Standar Nasional Pendidikan',
                'number'   => '19/2005',
                'year'     => 2005,
                'status'   => 'published',
                'category' => 'Peraturan Pemerintah',
                'versions' => [
                    ['notes' => 'Versi pertama.', 'downloads' => 80],
                    ['notes' => 'Revisi — penyesuaian pasal 22 dan 23.', 'downloads' => 45],
                ],
            ],
            [
                'title'    => 'Peraturan Pemerintah tentang Pengelolaan dan Penyelenggaraan Pendidikan',
                'number'   => '17/2010',
                'year'     => 2010,
                'status'   => 'published',
                'category' => 'Peraturan Pemerintah',
                'versions' => [
                    ['notes' => 'Versi pertama.', 'downloads' => 60],
                ],
            ],

            // ── Peraturan Menteri ──────────────────────────────────────
            [
                'title'    => 'Peraturan Menteri tentang Standar Nasional Pendidikan Tinggi',
                'number'   => '03/2020',
                'year'     => 2020,
                'status'   => 'published',
                'category' => 'Peraturan Menteri',
                'versions' => [
                    ['notes' => 'Versi pertama.', 'downloads' => 75],
                ],
            ],
            [
                'title'    => 'Peraturan Menteri tentang Standar Nasional Pendidikan Tinggi',
                'number'   => '53/2023',
                'year'     => 2023,
                'status'   => 'published',
                'category' => 'Peraturan Menteri',
                'versions' => [
                    ['notes' => 'Versi pertama.', 'downloads' => 110],
                    ['notes' => 'Perbaikan redaksional lampiran.', 'downloads' => 38],
                    ['notes' => 'Revisi substansi bagian IV.', 'downloads' => 12],
                ],
            ],
            [
                'title'    => 'Peraturan Menteri tentang Program Indonesia Pintar',
                'number'   => '10/2020',
                'year'     => 2020,
                'status'   => 'published',
                'category' => 'Peraturan Menteri',
                'versions' => [
                    ['notes' => 'Versi pertama.', 'downloads' => 55],
                ],
            ],
            [
                'title'    => 'Peraturan Menteri tentang Program Indonesia Pintar pada Pendidikan Tinggi',
                'number'   => '09/2022',
                'year'     => 2022,
                'status'   => 'published',
                'category' => 'Peraturan Menteri',
                'versions' => [
                    ['notes' => 'Versi pertama.', 'downloads' => 88],
                    ['notes' => 'Revisi — penambahan mekanisme verifikasi.', 'downloads' => 33],
                ],
            ],
            [
                'title'    => 'Peraturan Menteri tentang Akreditasi Program Studi dan Perguruan Tinggi',
                'number'   => '05/2020',
                'year'     => 2020,
                'status'   => 'published',
                'category' => 'Peraturan Menteri',
                'versions' => [
                    ['notes' => 'Versi pertama.', 'downloads' => 47],
                ],
            ],
            [
                'title'    => 'Peraturan Menteri tentang Izin Perguruan Tinggi Swasta',
                'number'   => '07/2021',
                'year'     => 2021,
                'status'   => 'unpublished',
                'category' => 'Peraturan Menteri',
                'versions' => [
                    ['notes' => 'Draft pertama — belum dipublikasikan.', 'downloads' => 0],
                ],
            ],

            // ── Keputusan Menteri ──────────────────────────────────────
            [
                'title'    => 'Keputusan Menteri tentang Penetapan Perguruan Tinggi Swasta di Wilayah Kalimantan',
                'number'   => '456/2023',
                'year'     => 2023,
                'status'   => 'published',
                'category' => 'Keputusan Menteri',
                'versions' => [
                    ['notes' => 'Versi pertama.', 'downloads' => 29],
                ],
            ],
            [
                'title'    => 'Keputusan Menteri tentang Penetapan Koordinator LLDIKTI Wilayah XI',
                'number'   => '123/2024',
                'year'     => 2024,
                'status'   => 'published',
                'category' => 'Keputusan Menteri',
                'versions' => [
                    ['notes' => 'Versi pertama.', 'downloads' => 18],
                ],
            ],

            // ── Surat Edaran ───────────────────────────────────────────
            [
                'title'    => 'Surat Edaran tentang Pelaksanaan Pembelajaran Daring Perguruan Tinggi',
                'number'   => '02/2021',
                'year'     => 2021,
                'status'   => 'published',
                'category' => 'Surat Edaran',
                'versions' => [
                    ['notes' => 'Versi pertama.', 'downloads' => 64],
                    ['notes' => 'Pembaruan panduan teknis.', 'downloads' => 21],
                ],
            ],
            [
                'title'    => 'Surat Edaran tentang Penyelenggaraan Ujian Akhir Semester Tahun 2024',
                'number'   => '01/2024',
                'year'     => 2024,
                'status'   => 'published',
                'category' => 'Surat Edaran',
                'versions' => [
                    ['notes' => 'Versi pertama.', 'downloads' => 42],
                ],
            ],

            // ── Keputusan Direktur Jenderal ────────────────────────────
            [
                'title'    => 'Keputusan Direktur Jenderal tentang Tata Cara Akreditasi Perguruan Tinggi Swasta',
                'number'   => '08/2022',
                'year'     => 2022,
                'status'   => 'published',
                'category' => 'Keputusan Direktur Jenderal',
                'versions' => [
                    ['notes' => 'Versi pertama.', 'downloads' => 53],
                    ['notes' => 'Revisi mekanisme pengajuan berkas.', 'downloads' => 27],
                    ['notes' => 'Penyesuaian dengan kebijakan BAN-PT terbaru.', 'downloads' => 9],
                ],
            ],
        ];

        $created = [];

        foreach ($regulations as $data) {
            $category = Category::where('name', $data['category'])->first();

            if (! $category) {
                $this->command->warn("Kategori '{$data['category']}' tidak ditemukan, dilewati.");
                continue;
            }

            $regulation = Regulation::create([
                'category_id' => $category->id,
                'title'       => $data['title'],
                'number'      => $data['number'],
                'slug'        => Str::slug($data['title'] . '-' . $data['number']),
                'year'        => $data['year'],
                'status'      => $data['status'],
            ]);

            // Buat semua versi PDF
            $versions = $data['versions'];
            foreach ($versions as $i => $versionData) {
                $isLast = $i === array_key_last($versions);

                RegulationVersion::create([
                    'regulation_id'  => $regulation->id,
                    'version_number' => $i + 1,
                    'file_path'      => 'regulations/dummy-' . $regulation->id . '-v' . ($i + 1) . '.pdf',
                    'file_size'      => rand(200000, 2000000),
                    'is_active'      => $isLast, // hanya versi terakhir yang aktif
                    'uploaded_by'    => $admin->id,
                    'notes'          => $versionData['notes'],
                    'download_count' => $versionData['downloads'],
                ]);
            }

            $created[$data['number']] = $regulation;
        }

        $this->seedRelations($created);

        $this->command->info('Seeded ' . count($created) . ' regulasi.');
    }

    private function seedRelations(array $regulations): void
    {
        // Format: [source_number, target_number, tipe_relasi]
        // Permendikbud 53/2023 mencabut Permendikbud 03/2020 (standar nasional lama)
        // Permendikbud 53/2023 juga mencabut sebagian Permendikbud 19/2005 (PP standar)
        // Permendikbud 09/2022 mencabut Permendikbud 10/2020 (PIP lama)
        // Permendikbud 09/2022 mencabut sebagian Permendikbud 10/2020 khusus pendidikan tinggi
        $pairs = [
            ['53/2023', '03/2020', 'mencabut'],
            ['53/2023', '19/2005', 'dicabut_sebagian'],
            ['09/2022', '10/2020', 'mencabut'],
            ['08/2022', '05/2020', 'mengubah'],
        ];

        foreach ($pairs as [$sourceNum, $targetNum, $type]) {
            $source = $regulations[$sourceNum] ?? null;
            $target = $regulations[$targetNum] ?? null;

            if (! $source || ! $target) continue;
            if ($source->id === $target->id) continue;

            RegulationRelation::firstOrCreate(
                [
                    'source_regulation_id' => $source->id,
                    'target_regulation_id' => $target->id,
                ],
                ['relation_type' => $type]
            );
        }
    }
}
