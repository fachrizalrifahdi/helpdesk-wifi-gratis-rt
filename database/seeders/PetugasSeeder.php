<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PetugasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        \App\Models\Petugas::create([
            'nama' => 'Administrator',
            'role' => 'Admin',
            'username' => 'admin',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);

        \App\Models\Petugas::create([
            'nama' => 'Teknisi 1',
            'role' => 'Teknisi',
            'username' => 'teknisi',
            'password' => \Illuminate\Support\Facades\Hash::make('password'),
        ]);
    }
}
