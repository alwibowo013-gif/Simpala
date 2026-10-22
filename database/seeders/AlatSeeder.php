<?php

namespace Database\Seeders;

use App\Models\Alat;
use Illuminate\Database\Seeder;

class AlatSeeder extends Seeder
{
    public function run(): void
    {
        $data = [
            ['kategori_id' => 1, 'kode_alat' => 'ALT-0001', 'nama_alat' => 'Proyektor Epson EB-X05', 'stok_total' => 5, 'stok_tersedia' => 5],
            ['kategori_id' => 1, 'kode_alat' => 'ALT-0002', 'nama_alat' => 'Laptop Acer Aspire', 'stok_total' => 8, 'stok_tersedia' => 6],
            ['kategori_id' => 2, 'kode_alat' => 'ALT-0003', 'nama_alat' => 'Bola Voli', 'stok_total' => 10, 'stok_tersedia' => 9],
            ['kategori_id' => 3, 'kode_alat' => 'ALT-0004', 'nama_alat' => 'Multimeter Digital', 'stok_total' => 12, 'stok_tersedia' => 12],
            ['kategori_id' => 4, 'kode_alat' => 'ALT-0005', 'nama_alat' => 'Kamera DSLR Canon', 'stok_total' => 3, 'stok_tersedia' => 2],
        ];

        foreach ($data as $item) {
            Alat::create($item);
        }
    }
}
