<?php

namespace App\Http\Controllers; // Namespace untuk controller

use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP
use Illuminate\Support\Facades\DB; // Mengimpor facade DB untuk query langsung ke database

class BookAuthorController extends Controller
{
    /**
     * Menampilkan semua relasi buku-penulis.
     */
    public function index()
    {
        // Mengambil semua data dari tabel book_authors dengan join ke tabel books dan authors
        $bookAuthors = DB::table('book_authors')
            ->join('books', 'book_authors.book_id', '=', 'books.book_id') // Join dengan tabel books
            ->join('authors', 'book_authors.author_id', '=', 'authors.author_id') // Join dengan tabel authors
            ->select('book_authors.*', 'books.title as book_title', 'authors.name as author_name') // Memilih kolom dan menambahkan title dan name
            ->get();

        // Mengembalikan data dalam format JSON
        return response()->json($bookAuthors);
    }

    /**
     * Menambahkan relasi baru antara buku dan penulis.
     */
    public function store(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'book_id' => 'required|exists:books,book_id', // book_id wajib dan harus ada di tabel books
            'author_id' => 'required|exists:authors,author_id', // author_id wajib dan harus ada di tabel authors
        ]);

        // Memeriksa apakah relasi sudah ada untuk mencegah duplikasi
        $exists = DB::table('book_authors')
            ->where('book_id', $validated['book_id'])
            ->where('author_id', $validated['author_id'])
            ->exists();

        if ($exists) {
            // Mengembalikan error jika relasi sudah ada
            return response()->json(['message' => 'Relation already exists'], 409); // Status 409: Conflict
        }

        // Menyisipkan relasi baru ke tabel book_authors
        DB::table('book_authors')->insert([
            'book_id' => $validated['new_book_id'],
            'author_id' => $validated['new_author_id'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Mengembalikan respons sukses dengan status 201 (Created)
        return response()->json(['message' => 'Author attached to book'], 201);
    }

    /**
     * Menampilkan relasi spesifik berdasarkan book_id dan author_id.
     */
    public function show($book_id, $author_id)
    {
        // Mencari relasi berdasarkan book_id dan author_id
        $relation = DB::table('book_authors')
            ->where('book_id', $book_id)
            ->where('author_id', $author_id)
            ->first();

        // Mengembalikan error jika relasi tidak ditemukan
        if (!$relation) {
            return response()->json(['message' => 'Relation not found'], 404); // Status 404: Not Found
        }

        // Mengembalikan data relasi dalam format JSON
        return response()->json($relation);
    }

    /**
     * Memperbarui relasi buku dan penulis.
     */
    public function update(Request $request, $book_id, $author_id)
    {
        // Validasi input untuk relasi baru
        $validated = $request->validate([
            'new_book_id' => 'required|exists:books,book_id', // book_id baru wajib dan harus ada
            'new_author_id' => 'required|exists:authors,author_id', // author_id baru wajib dan harus ada
        ]);

        // Memeriksa apakah relasi yang akan diupdate ada
        $existingRelation = DB::table('book_authors')
            ->where('book_id', $book_id)
            ->where('author_id', $author_id)
            ->first();

        if (!$existingRelation) {
            // Mengembalikan error jika relasi tidak ditemukan
            return response()->json(['message' => 'Relation not found'], 404); // Status 404: Not Found
        }

        // Memeriksa apakah kombinasi baru sudah ada (mencegah duplikasi)
        $exists = DB::table('book_authors')
            ->where('book_id', $validated['new_book_id'])
            ->where('author_id', $validated['new_author_id'])
            ->where('book_id', '!=', $book_id) // Mengecualikan relasi yang sedang diupdate
            ->where('author_id', '!=', $author_id)
            ->exists();

        if ($exists) {
            // Mengembalikan error jika relasi baru sudah ada
            return response()->json(['message' => 'New relation already exists'], 409); // Status 409: Conflict
        }

        // Memulai transaksi database untuk memastikan integritas
        DB::beginTransaction();
        try {
            // Menghapus relasi lama
            DB::table('book_authors')
                ->where('book_id', $book_id)
                ->where('author_id', $author_id)
                ->delete();

            // Menambahkan relasi baru
            DB::table('book_authors')->insert([
                'book_id' => $validated['new_book_id'],
                'author_id' => $validated['new_author_id'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Commit transaksi jika berhasil
            DB::commit();
            return response()->json(['message' => 'Relation updated successfully'], 200);
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            DB::rollBack();
            return response()->json(['message' => 'Failed to update relation: ' . $e->getMessage()], 500); // Status 500: Internal Server Error
        }
    }

    /**
     * Menghapus relasi buku dan penulis.
     */
    public function destroy($book_id, $author_id)
    {
        // Menghapus relasi berdasarkan book_id dan author_id
        $deleted = DB::table('book_authors')
            ->where('book_id', $book_id)
            ->where('author_id', $author_id)
            ->delete();

        // Mengembalikan respons berdasarkan hasil penghapusan
        if ($deleted) {
            return response()->json(['message' => 'Relation deleted']); // Sukses
        }

        return response()->json(['message' => 'Relation not found'], 404); // Status 404: Not Found
    }
}