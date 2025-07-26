<?php

namespace App\Models; // Namespace untuk model

use Illuminate\Database\Eloquent\Model; // Mengimpor kelas dasar Model dari Laravel

class Loan extends Model
{
    // Menentukan nama kolom primary key
    protected $primaryKey = 'loan_id';

    // Menonaktifkan auto-increment karena primary key bukan integer
    public $incrementing = false;

    // Menentukan tipe data primary key sebagai string
    protected $keyType = 'string';

    // Kolom yang diperbolehkan untuk mass assignment
    protected $fillable = [
        'loan_id', // ID peminjaman (string)
        'user_id', // ID pengguna yang meminjam
        'book_id', // ID buku yang dipinjam
    ];

    // Mendefinisikan relasi ke model User
    public function user()
    {
        // Relasi belongsTo ke model User, menggunakan user_id sebagai foreign key
        return $this->belongsTo(User::class, 'user_id', 'user_id');
    }

    // Mendefinisikan relasi ke model Book
    public function book()
    {
        // Relasi belongsTo ke model Book, menggunakan book_id sebagai foreign key
        return $this->belongsTo(Book::class, 'book_id', 'book_id');
    }
}