<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Dr. Siti Rahayu',
                'email' => 'kepala@perpustakaan.com',
                'password' => Hash::make('12345678'),
                'role' => 'kepala_perpustakaan',
                'no_telepon' => '081234567890',
                'alamat' => 'Jakarta',
            ],
            [
                'name' => 'Budi',
                'email' => 'petugas1@perpustakaan.com',
                'password' => Hash::make('12345678'),
                'role' => 'petugas',
                'no_telepon' => '081234567891',
                'alamat' => 'Jakarta',
            ],
            [
                'name' => 'Darsono',
                'email' => 'petugas2@perpustakaan.com',
                'password' => Hash::make('12345678'),
                'role' => 'petugas',
                'no_telepon' => '081234567892',
                'alamat' => 'Jakarta',
            ],
        ];

        foreach ($users as $user) {
            User::updateOrCreate(
                ['email' => $user['email']],
                array_merge($user, [
                    'password' => Hash::make('password123'),
                ])
            );
        }
    }
}