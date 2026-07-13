<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(
            ['nip_nrp' => '999999999999999999'],
            [
                'name' => 'Admin USIPA',
                'email' => 'admin@usipa.internal',
                'pangkat_gol' => '-',
                'jabatan_satker' => 'Pengelola USIPA',
                'password' => Hash::make('999999999999999999'),
                'role' => 'admin',
                'must_change_password' => false,
            ]
        );
    }
}