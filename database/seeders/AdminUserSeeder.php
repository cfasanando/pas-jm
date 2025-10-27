<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => 'admin@jm.gob.pe'],
            ['name' => 'Admin JM', 'password' => Hash::make('CambiaEstaClave123!')]
        );
    }
}
