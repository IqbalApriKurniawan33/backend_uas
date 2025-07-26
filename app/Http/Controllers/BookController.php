<?php

namespace App\Http\Controllers; // Namespace untuk controller

use App\Models\Book; // Mengimpor model Book
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP

class BookController extends Controller
{
    /**
     * Menampilkan semua buku beserta relasi penulisnya.
     */
    public function index()
    {
        // Mengambil semua data buku dengan relasi authors (eager loading)
        return Book::with('authors')->get();
    }

    /**
     * Menyimpan buku baru ke database.
     */
    public function store(Request $request)
    {
        // Membuat buku baru berdasarkan semua data dari request
        return Book::create($request->all());
    }

    /**
     * Menampilkan detail buku berdasarkan ID beserta relasi penulisnya.
     */
    public function show($id)
    {
        // Mencari buku berdasarkan ID dengan relasi authors, gagal jika tidak ditemukan
        return Book::with('authors')->findOrFail($id);
    }

    /**
     * Memperbarui data buku berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        // Mencari buku berdasarkan ID, gagal jika tidak ditemukan
        $book = Book::findOrFail($id);
        // Memperbarui data buku dengan data dari request
        $book->update($request->all());
        // Mengembalikan data buku yang telah diperbarui
        return $book;
    }

    /**
     * Menghapus buku berdasarkan ID.
     */
    public function destroy($id)
    {
        // Menghapus buku berdasarkan ID dan mengembalikan jumlah baris yang dihapus
        return Book::destroy($id);
    }
}