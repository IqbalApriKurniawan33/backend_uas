<?php

namespace Database\Seeders; // Namespace untuk seeder

use App\Models\User; // Mengimpor model User
use Illuminate\Database\Seeder; // Mengimpor kelas dasar Seeder dari Laravel
use Illuminate\Support\Facades\Hash; // Mengimpor Hash untuk mengenkripsi kata sandi
use Illuminate\Support\Str; // Mengimpor Str untuk menghasilkan ULID

class UserSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk mengisi data awal ke tabel users.
     */
    public function run(): void
    {
        // Membuat data pengguna baru di tabel users
        User::create([
            'user_id'         => (string) Str::ulid(), // Menghasilkan ULID unik sebagai user_id (dikonversi ke string)
            'name'            => 'Admin', // Nama pengguna, diisi dengan 'Admin'
            'username'        => 'admin', // Username unik, diisi dengan 'admin'
            'email'           => 'admin@ifump.net', // Email unik untuk pengguna admin
            'password'        => Hash::make('password'), // Kata sandi dienkripsi menggunakan bcrypt
            'membership_date' => now()->toDateString(), // Tanggal keanggotaan diisi dengan tanggal saat ini
        ]);
    }
}