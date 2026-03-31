<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Kepala Perpustakaan
        User::create([
            'name'        => 'Dr. Siti Rahayu',
            'email'       => 'kepala@perpustakaan.com',
            'password'    => Hash::make('password123'),
            'role'        => 'kepala_perpustakaan',
            'no_telepon'  => '081234567890',
            'alamat'      => 'Jl. Sudirman No. 1, Jakarta',
        ]);

        // Petugas 1
        User::create([
            'name'        => 'Budi',
            'email'       => 'petugas1@perpustakaan.com',
            'password'    => Hash::make('12345678'),
            'role'        => 'petugas',
            'no_telepon'  => '081234567891',
            'alamat'      => 'Jl. Gatot Subroto No. 5, Jakarta',
        ]);

        // Petugas 2
        User::create([
            'name'        => 'Darsono',
            'email'       => 'petugas2@perpustakaan.com',
            'password'    => Hash::make('password123'),
            'role'        => 'petugas',
            'no_telepon'  => '081234567892',
            'alamat'      => 'Jl. Thamrin No. 10, Jakarta',
        ]);
    }
}
