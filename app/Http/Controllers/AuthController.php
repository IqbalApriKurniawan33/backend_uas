<?php

namespace App\Http\Controllers; // Namespace untuk controller

use App\Models\User; // Mengimpor model User
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP
use App\Http\Requests\LoginRequest; // Mengimpor request kustom untuk validasi login
use App\Http\Resources\UserResource; // Mengimpor resource untuk format respons pengguna
use Illuminate\Support\Facades\Auth; // Mengimpor facade Auth untuk autentikasi
use Illuminate\Support\Facades\Hash; // Mengimpor facade Hash untuk enkripsi kata sandi
use Illuminate\Support\Str; // Mengimpor Str untuk menghasilkan ULID
use Illuminate\Validation\ValidationException; // Mengimpor exception untuk validasi

class AuthController extends Controller
{
    /**
     * Proses login pengguna.
     */
    public function login(LoginRequest $request)
    {
        // Mengambil data yang sudah divalidasi dari LoginRequest
        $credentials = $request->validated();

        // Mencari pengguna berdasarkan username (case-sensitive menggunakan BINARY)
        $user = User::whereRaw('BINARY username = ?', [$credentials['username']])->first();

        // Memeriksa apakah pengguna ada dan kata sandi cocok
        if (! $user || ! Hash::check($credentials['password'], $user->password)) {
            // Melempar exception jika username atau kata sandi salah
            throw ValidationException::withMessages([
                'username' => 'Username atau password salah.',
            ]);
        }

        // Mengembalikan token autentikasi dan data pengguna dalam format UserResource
        return response()->json([
            'token' => $user->createToken('mobile-token')->plainTextToken, // Membuat token Sanctum
            'user' => new UserResource($user), // Data pengguna dalam format resource
        ]);
    }

    /**
     * Proses logout pengguna.
     */
    public function logout(Request $request)
    {
        // Memeriksa apakah ada pengguna yang sedang login
        if ($request->user()) {
            // Menghapus token akses saat ini
            $request->user()->currentAccessToken()->delete();
        }

        // Mengembalikan pesan sukses
        return response()->json([
            'message' => 'Logout berhasil.',
        ]);
    }

    /**
     * Mendaftarkan pengguna baru.
     */
    public function register(Request $request)
    {
        // Validasi data input
        $data = $request->validate([
            'name'     => 'required|string|max:255', // Nama wajib, string, maksimal 255 karakter
            'username' => 'required|string|max:255|unique:users,username', // Username wajib, unik
            'password' => 'required|string|min:6', // Kata sandi wajib, minimal 6 karakter
            'email'    => 'required|email|unique:users,email', // Email wajib, format email, unik
        ]);

        // Membuat pengguna baru
        $user = User::create([
            'user_id'         => (string) Str::ulid(), // Menghasilkan ULID sebagai user_id
            'name'            => $data['name'], // Nama pengguna
            'username'        => strtolower($data['username']), // Username dalam huruf kecil
            'email'           => $data['email'], // Email pengguna
            'password'        => Hash::make($data['password']), // Kata sandi dienkripsi
            'membership_date' => now()->toDateString(), // Tanggal keanggotaan saat ini
        ]);

        // Mengembalikan respons sukses dengan status 201 (Created)
        return response()->json([
            'message' => 'User berhasil terdaftar',
            'user'    => new UserResource($user), // Data pengguna dalam format resource
        ], 201);
    }
}