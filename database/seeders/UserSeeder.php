<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        User::create([
            'name' => 'Admin SIMPALA',
            'email' => 'admin@simpala.test',
            'password' => Hash::make('password123'),
            'role' => 'admin',
        ]);

        User::create([
            'name' => 'Almadima Kurnianata Wibowo',
            'email' => 'siswa@simpala.test',
            'password' => Hash::make('password123'),
            'role' => 'siswa',
        ]);
    }
}
