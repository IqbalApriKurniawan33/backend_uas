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
        // Membuat tabel 'books'
        Schema::create('books', function (Blueprint $table) {
            $table->string('book_id')->primary(); // Kolom book_id sebagai primary key (string, bukan auto-increment)
            $table->string('title'); // Kolom judul buku (default panjang 255 karakter)
            $table->string('isbn'); // Kolom ISBN buku (kode identifikasi buku)
            $table->string('publisher'); // Kolom nama penerbit buku
            $table->string('year_published'); // Kolom tahun terbit buku (disimpan sebagai string)
            $table->integer('stock'); // Kolom jumlah stok buku (tipe data integer)
            $table->timestamps(); // Kolom created_at dan updated_at otomatis untuk mencatat waktu pembuatan dan pembaruan
        });
    }

    /**
     * Membatalkan migrasi dengan menghapus tabel yang telah dibuat.
     */
    public function down(): void
    {
        Schema::dropIfExists('books'); // Menghapus tabel books jika ada
    }
};