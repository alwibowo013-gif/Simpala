<?php

namespace Database\Seeders;

use App\Models\KategoriAlat;
use Illuminate\Database\Seeder;

class KategoriAlatSeeder extends Seeder
{
    public function run(): void
    {
        $kategori = ['Elektronik', 'Olahraga', 'Alat Praktikum', 'Multimedia'];

        foreach ($kategori as $nama) {
            KategoriAlat::create(['nama_kategori' => $nama]);
        }
    }
}
