<?php

namespace App\Http\Controllers; // Namespace untuk controller

use App\Models\Loan; // Mengimpor model Loan
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP

class LoanController extends Controller
{
    /**
     * Menampilkan semua data peminjaman beserta relasi pengguna dan buku.
     */
    public function index()
    {
        // Mengambil semua data peminjaman dengan relasi user dan book (eager loading)
        return Loan::with(['user', 'book'])->get();
    }

    /**
     * Menyimpan data peminjaman baru ke database.
     */
    public function store(Request $request)
    {
        // Membuat peminjaman baru berdasarkan semua data dari request
        return Loan::create($request->all());
    }

    /**
     * Menampilkan detail peminjaman berdasarkan ID beserta relasi pengguna dan buku.
     */
    public function show($id)
    {
        // Mencari peminjaman berdasarkan ID dengan relasi user dan book, gagal jika tidak ditemukan
        return Loan::with(['user', 'book'])->findOrFail($id);
    }

    /**
     * Memperbarui data peminjaman berdasarkan ID.
     */
    public function update(Request $request, $id)
    {
        // Mencari peminjaman berdasarkan ID, gagal jika tidak ditemukan
        $loan = Loan::findOrFail($id);
        // Memperbarui data peminjaman dengan data dari request
        $loan->update($request->all());
        // Mengembalikan data peminjaman yang telah diperbarui
        return $loan;
    }

    /**
     * Menghapus data peminjaman berdasarkan ID.
     */
    public function destroy($id)
    {
        // Menghapus peminjaman berdasarkan ID dan mengembalikan jumlah baris yang dihapus
        return Loan::destroy($id);
    }
}