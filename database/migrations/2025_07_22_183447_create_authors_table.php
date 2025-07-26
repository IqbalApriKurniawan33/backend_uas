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
        // Membuat tabel 'authors'
        Schema::create('authors', function (Blueprint $table) {
            $table->string('author_id')->primary(); // Kolom author_id sebagai primary key (string, bukan auto-increment)
            $table->string('name'); // Kolom nama penulis (default panjang 255 karakter)
            $table->string('nationality'); // Kolom kewarganegaraan penulis
            $table->string('birthdate'); // Kolom tanggal lahir penulis (disimpan sebagai string)
            $table->timestamps(); // Kolom created_at dan updated_at otomatis untuk mencatat waktu pembuatan dan pembaruan
        });
    }

    /**
     * Membatalkan migrasi dengan menghapus tabel yang telah dibuat.
     */
    public function down(): void
    {
        Schema::dropIfExists('authors'); // Menghapus tabel authors jika ada
    }
};