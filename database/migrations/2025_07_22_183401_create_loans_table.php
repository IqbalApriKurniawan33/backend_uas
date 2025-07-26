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
        // Membuat tabel 'loans'
        Schema::create('loans', function (Blueprint $table) {
            $table->string('loan_id')->primary(); // Kolom loan_id sebagai primary key (string, bukan auto-increment)
            $table->string('user_id'); // Kolom user_id untuk menyimpan ID pengguna yang meminjam buku
            $table->string('book_id'); // Kolom book_id untuk menyimpan ID buku yang dipinjam
            $table->timestamps(); // Kolom created_at dan updated_at otomatis untuk mencatat waktu peminjaman

            // Menambahkan foreign key constraints
            $table->foreign('user_id')->references('user_id')->on('users')->onDelete('cascade'); // Foreign key ke tabel users, hapus data peminjaman jika pengguna dihapus
            $table->foreign('book_id')->references('book_id')->on('books')->onDelete('cascade'); // Foreign key ke tabel books, hapus data peminjaman jika buku dihapus
        });
    }

    /**
     * Membatalkan migrasi dengan menghapus tabel yang telah dibuat.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans'); // Menghapus tabel loans jika ada
    }
};