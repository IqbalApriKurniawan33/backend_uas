<?php

namespace Database\Seeders; // Namespace untuk seeder

use App\Models\User; // Mengimpor model User
// use Illuminate\Database\Console\Seeds\WithoutModelEvents; // Baris ini dikomentari, tidak digunakan
use Illuminate\Database\Seeder; // Mengimpor kelas dasar Seeder dari Laravel

class DatabaseSeeder extends Seeder
{
    /**
     * Mengisi database aplikasi dengan data awal.
     */
    public function run(): void
    {
        // Baris ini dikomentari, sehingga tidak menghasilkan 10 data pengguna secara otomatis
        // User::factory(10)->create();

        // Membuat satu data pengguna menggunakan factory dengan data spesifik
        User::factory()->create([
            'name' => 'Test User', // Nama pengguna diisi dengan 'Test User'
            'email' => 'test@example.com', // Email pengguna diisi dengan 'test@example.com'
        ]);
    }
}