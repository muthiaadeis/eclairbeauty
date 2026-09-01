<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'nama' => 'Rahma Mardian Tini',
                'username' => 'dokter',
                'password' => Hash::make('dokter123'),
                'role' => 'dokter',
                'email' => 'dokter@eclair.com',
                'nomor_telepon' => '08123456789',
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Aulia Rahman',
                'username' => 'dokter2',
                'password' => Hash::make('dokter123'),
                'role' => 'dokter',
                'email' => 'dokter2@eclair.com',
                'nomor_telepon' => '08123456780',
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Septiara Ajeng',
                'username' => 'resepsionis',
                'password' => Hash::make('resepsionis123'),
                'role' => 'resepsionis',
                'email' => 'resepsionis@eclair.com',
                'nomor_telepon' => '08198765432',
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama' => 'Pemilik Klinik',
                'username' => 'pemilik',
                'password' => Hash::make('pemilik123'),
                'role' => 'pemilik',
                'email' => 'pemilik@eclair.com',
                'nomor_telepon' => '08111111111',
                'status_aktif' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
