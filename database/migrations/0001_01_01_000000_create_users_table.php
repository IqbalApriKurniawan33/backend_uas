<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

// Mengembalikan kelas anonim yang meng-extend Migration
return new class extends Migration
{
    /**
     * Menjalankan migrasi untuk membuat tabel di database.
     */
    public function up(): void
    {
        // Membuat tabel 'users'
        Schema::create('users', function (Blueprint $table) {
            $table->string('user_id')->primary(); // Kolom user_id sebagai primary key (string, bukan auto-increment)
            $table->string('name', 50); // Kolom nama dengan panjang maksimal 50 karakter
            $table->string('username', 50)->unique(); // Kolom username, unik, maksimal 50 karakter
            $table->string('email', 50)->unique(); // Kolom email, unik, maksimal 50 karakter
            $table->string('password'); // Kolom password untuk hash bcrypt (default 255 karakter)
            $table->date('membership_date'); // Kolom tanggal keanggotaan (format tanggal)
            $table->timestamp('email_verified_at')->nullable(); // Kolom untuk menyimpan waktu verifikasi email, boleh null
            $table->rememberToken(); // Kolom untuk token "ingat saya" pada autentikasi
            $table->timestamps(); // Kolom created_at dan updated_at otomatis
        });

        // Membuat tabel 'password_reset_tokens' untuk reset kata sandi
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary(); // Kolom email sebagai primary key
            $table->string('token'); // Kolom untuk menyimpan token reset kata sandi
            $table->timestamp('created_at')->nullable(); // Kolom waktu pembuatan token, boleh null
        });

        // Membuat tabel 'sessions' untuk menyimpan data sesi pengguna
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary(); // Kolom ID sesi sebagai primary key
            $table->string('user_id')->nullable()->index(); // Kolom user_id (string), boleh null, diindeks untuk performa
            $table->string('ip_address', 45)->nullable(); // Kolom alamat IP, maksimal 45 karakter (mendukung IPv6)
            $table->text('user_agent')->nullable(); // Kolom user agent peramban, boleh null
            $table->longText('payload'); // Kolom untuk menyimpan data sesi dalam format teks panjang
            $table->integer('last_activity')->index(); // Kolom waktu aktivitas terakhir, diindeks untuk performa
        });
    }

    /**
     * Membatalkan migrasi dengan menghapus tabel yang telah dibuat.
     */
    public function down(): void
    {
        Schema::dropIfExists('sessions'); // Menghapus tabel sessions jika ada
        Schema::dropIfExists('password_reset_tokens'); // Menghapus tabel password_reset_tokens jika ada
        Schema::dropIfExists('users'); // Menghapus tabel users jika ada
    }
};