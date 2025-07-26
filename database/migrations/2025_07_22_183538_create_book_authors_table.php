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
        // Membuat tabel 'book_authors' untuk relasi banyak-ke-banyak antara buku dan penulis
        Schema::create('book_authors', function (Blueprint $table) {
            $table->string('book_id'); // Kolom book_id untuk menyimpan ID buku
            $table->string('author_id'); // Kolom author_id untuk menyimpan ID penulis
            $table->timestamps(); // Kolom created_at dan updated_at otomatis untuk mencatat waktu pembuatan dan pembaruan

            $table->primary(['book_id', 'author_id']); // Kombinasi book_id dan author_id sebagai composite primary key

            // Menambahkan foreign key constraints
            $table->foreign('book_id')->references('book_id')->on('books')->onDelete('cascade'); // Foreign key ke tabel books, hapus data jika buku dihapus
            $table->foreign('author_id')->references('author_id')->on('authors')->onDelete('cascade'); // Foreign key ke tabel authors, hapus data jika penulis dihapus
        });
    }

    /**
     * Membatalkan migrasi dengan menghapus tabel yang telah dibuat.
     */
    public function down(): void
    {
        Schema::dropIfExists('book_authors'); // Menghapus tabel book_authors jika ada
    }
};