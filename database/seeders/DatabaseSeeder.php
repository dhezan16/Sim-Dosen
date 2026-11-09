<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Aman dijalankan berulang kali: semua seeder memakai firstOrCreate
     * sehingga data yang sudah ada tidak diduplikasi atau ditimpa.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            ReferensiSeeder::class,
        ]);
    }
}
