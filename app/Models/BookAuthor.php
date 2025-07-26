<?php

namespace App\Models; // Namespace untuk model

use Illuminate\Database\Eloquent\Relations\Pivot; // Mengimpor kelas Pivot dari Laravel

class BookAuthor extends Pivot
{
    // Menentukan nama tabel yang digunakan oleh model pivot
    protected $table = 'book_authors';

    // Menonaktifkan auto-increment karena tidak menggunakan primary key tunggal
    public $incrementing = false;

    // Menentukan tipe data kunci sebagai string
    protected $keyType = 'string';

    // Kolom yang diperbolehkan untuk mass assignment
    protected $fillable = [
        'book_id', // ID buku dalam relasi
        'author_id', // ID penulis dalam relasi
    ];

    // Mendefinisikan relasi ke model Book
    public function book()
    {
        // Relasi belongsTo ke model Book, menggunakan book_id sebagai foreign key
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }

    // Mendefinisikan relasi ke model Author
    public function author()
    {
        // Relasi belongsTo ke model Author, menggunakan author_id sebagai foreign key
        return $this->belongsTo(Author::class, 'author_id', 'author_id');
    }
}