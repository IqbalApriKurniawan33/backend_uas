<?php

namespace App\Models; // Namespace untuk model

use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar Model dari Laravel

class Book extends Model
{
    // Menentukan nama kolom primary key
    protected $primaryKey = 'book_id';

    // Menonaktifkan auto-increment karena primary key bukan integer
    public $incrementing = false;

    // Menentukan tipe data primary key sebagai string
    protected $keyType = 'string';

    // Kolom yang diperbolehkan untuk mass assignment
    protected $fillable = [
        'book_id', // ID buku (string)
        'title', // Judul buku
        'isbn', // ISBN buku
        'publisher', // Penerbit buku
        'year_published', // Tahun terbit buku
        'stock', // Jumlah stok buku
    ];

    // Mendefinisikan relasi satu-ke-banyak dengan model Loan
    public function loans()
    {
        // Relasi hasMany ke model Loan, menggunakan book_id sebagai foreign key
        return $this->hasMany(Loan::class, 'book_id', 'book_id');
    }

    // Mendefinisikan relasi banyak-ke-banyak dengan model Author
    public function authors()
    {
        // Relasi belongsToMany ke model Author melalui tabel pivot book_authors
        return $this->belongsToMany(Author::class, 'book_authors', 'book_id', 'author_id');
    }
}