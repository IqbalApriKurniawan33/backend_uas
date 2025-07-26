<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\AuthorController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\BookAuthorController;

// 🔐 Rute untuk autentikasi tanpa memerlukan token
Route::post('/login', [AuthController::class, 'login']); // Endpoint untuk login pengguna
Route::post('/register', [AuthController::class, 'register']); // Endpoint untuk registrasi pengguna baru

// 🔐 Grup rute yang memerlukan autentikasi dengan Sanctum token
Route::middleware('auth:sanctum')->group(function () {

    Route::post('/logout', [AuthController::class, 'logout']); // Endpoint untuk logout pengguna

    // 👤 Rute untuk pengelolaan data pengguna
    Route::apiResource('users', UserController::class); // Rute RESTful untuk CRUD pengguna (index, store, show, update, destroy)
    Route::get('/users-count', [UserController::class, 'count']); // Endpoint untuk menghitung jumlah pengguna

    // 📚 Rute untuk pengelolaan data buku
    Route::apiResource('books', BookController::class); // Rute RESTful untuk CRUD buku

    // ✍️ Rute untuk pengelolaan data penulis
    Route::apiResource('authors', AuthorController::class); // Rute RESTful untuk CRUD penulis

    // 🔁 Rute untuk pengelolaan relasi banyak-ke-banyak antara buku dan penulis
    Route::get('/book-authors', [BookAuthorController::class, 'index']); // Menampilkan semua relasi buku-penulis
    Route::post('/book-authors', [BookAuthorController::class, 'store']); // Membuat relasi baru antara buku dan penulis
    Route::get('/book-authors/{book_id}/{author_id}', [BookAuthorController::class, 'show']); // Menampilkan detail relasi spesifik berdasarkan book_id dan author_id
    Route::patch('/book-authors/{book_id}/{author_id}', [BookAuthorController::class, 'update']); // Memperbarui relasi spesifik
    Route::delete('/book-authors/{book_id}/{author_id}', [BookAuthorController::class, 'destroy']); // Menghapus relasi spesifik

    // 📥 Rute untuk pengelolaan data peminjaman buku
    Route::apiResource('loans', LoanController::class); // Rute RESTful untuk CRUD peminjaman

    // 📊 Rute untuk menampilkan statistik dashboard
    Route::get('/dashboard-counts', function () {
        return response()->json([
            'users' => \App\Models\User::count(), // Jumlah total pengguna
            'books' => \App\Models\Book::count(), // Jumlah total buku
            'authors' => \App\Models\Author::count(), // Jumlah total penulis
            'loans' => \App\Models\Loan::count(), // Jumlah total peminjaman
            'book_authors' => \App\Models\BookAuthor::count(), // Jumlah total relasi buku-penulis
        ]);
    });
});