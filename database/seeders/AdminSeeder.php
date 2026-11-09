<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Akun admin awal. Kredensial dapat diatur lewat .env
     * (ADMIN_EMAIL, ADMIN_PASSWORD). Segera ganti kata sandi bawaan.
     */
    public function run(): void
    {
        User::firstOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@umb.ac.id')],
            [
                'name' => env('ADMIN_NAME', 'Administrator'),
                'password' => env('ADMIN_PASSWORD', 'admin12345'),
                'role' => User::ADMIN,
            ],
        );
    }
}
