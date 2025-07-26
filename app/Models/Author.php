<?php

namespace App\Models; // Namespace untuk model

use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar Model dari Laravel

class Author extends Model
{
    // Menentukan nama kolom primary key
    protected $primaryKey = 'author_id';

    // Menonaktifkan auto-increment karena primary key bukan integer
    public $incrementing = false;

    // Menentukan tipe data primary key sebagai string
    protected $keyType = 'string';

    // Kolom yang diperbolehkan untuk mass assignment
    protected $fillable = [
        'author_id', // ID penulis (string)
        'name', // Nama penulis
        'nationality', // Kewarganegaraan penulis
        'birthdate', // Tanggal lahir penulis
    ];

    // Mendefinisikan relasi banyak-ke-banyak dengan model Book
    public function books()
    {
        // Relasi belongsToMany ke model Book melalui tabel pivot book_authors
        return $this->belongsToMany(Book::class, 'book_authors', 'author_id', 'book_id');
    }
}