<?php

namespace App\Models; // Namespace untuk model

use Laravel\Sanctum\HasApiTokens; // Trait untuk autentikasi berbasis token Sanctum
use Illuminate\Notifications\Notifiable; // Trait untuk mendukung notifikasi
use Illuminate\Database\Eloquent\Concerns\HasUlids; // Trait untuk menggunakan ULID sebagai identifier
use Illuminate\Database\Eloquent\Factories\HasFactory; // Trait untuk mendukung factory
use Illuminate\Foundation\Auth\User as Authenticatable; // Kelas dasar untuk autentikasi pengguna
use Illuminate\Database\Eloquent\Relations\HasMany; // Impor untuk relasi HasMany

class User extends Authenticatable
{
    // Menggunakan trait untuk fungsionalitas tambahan
    use HasApiTokens, HasFactory, Notifiable, HasUlids;

    // Menentukan nama kolom primary key
    protected $primaryKey = 'user_id';

    // Menonaktifkan auto-increment karena primary key bukan integer
    public $incrementing = false;

    // Menentukan tipe data primary key sebagai string
    protected $keyType = 'string';

    /**
     * Kolom yang diperbolehkan untuk mass assignment.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id', // ID pengguna (string, ULID)
        'name', // Nama pengguna
        'email', // Email pengguna
        'username', // Username pengguna
        'password', // Kata sandi pengguna (hashed)
        'membership_date', // Tanggal keanggotaan
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi (misalnya, saat diubah ke JSON).
     *
     * @var list<string>
     */
    protected $hidden = [
        'password', // Menyembunyikan kata sandi
        'remember_token', // Menyembunyikan token "ingat saya"
    ];

    /**
     * Menentukan tipe data untuk kolom tertentu saat diakses.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'name' => 'string', // Memastikan name bertipe string
            'username' => 'string', // Memastikan username bertipe string
            'membership_date' => 'date', // Memastikan membership_date sebagai date
            'email_verified_at' => 'datetime', // Memastikan email_verified_at sebagai datetime
            'password' => 'hashed', // Memastikan password dianggap sebagai hashed
        ];
    }

    /**
     * Mengubah username menjadi huruf kecil secara otomatis saat disimpan.
     */
    public function setUsernameAttribute($value): void
    {
        $this->attributes['username'] = strtolower($value); // Mengubah nilai username ke huruf kecil
    }

    /**
     * Mendefinisikan relasi satu-ke-banyak dengan model Loan.
     */
    public function loans(): HasMany
    {
        // Relasi hasMany ke model Loan, menggunakan user_id sebagai foreign key
        return $this->hasMany(Loan::class, 'user_id', 'user_id');
    }
}