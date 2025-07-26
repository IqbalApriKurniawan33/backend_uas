<?php

namespace App\Http\Controllers; // Namespace untuk controller

use App\Models\User; // Mengimpor model User
use Illuminate\Http\Request; // Mengimpor kelas Request untuk menangani input HTTP
use Illuminate\Http\JsonResponse; // Mengimpor kelas JsonResponse untuk respons JSON
use Illuminate\Database\Eloquent\ModelNotFoundException; // Mengimpor exception untuk model tidak ditemukan
use Exception; // Mengimpor kelas Exception untuk penanganan error umum

class UserController extends Controller
{
    /**
     * Menampilkan semua data pengguna.
     */
    public function index(): JsonResponse
    {
        // Mengambil semua data pengguna dari tabel users
        $dataUser = User::all();
        // Mengembalikan data dalam format JSON dengan status 200 (OK)
        return response()->json($dataUser, 200);
    }

    /**
     * Menampilkan detail pengguna berdasarkan user_id.
     */
    public function show($user_id): JsonResponse
    {
        try {
            // Mencari pengguna berdasarkan user_id, gagal jika tidak ditemukan
            $user = User::findOrFail($user_id);
            // Mengembalikan data pengguna dalam format JSON dengan status 200 (OK)
            return response()->json($user, 200);
        } catch (ModelNotFoundException $e) {
            // Mengembalikan pesan error jika pengguna tidak ditemukan dengan status 404
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        }
    }

    /**
     * Menambahkan pengguna baru ke database.
     */
    public function store(Request $request): JsonResponse
    {
        // Validasi input
        $request->validate([
            'user_id' => 'required|string|unique:users,user_id', // user_id wajib, string, unik
            'name' => 'required|string|max:255', // Nama wajib, maksimal 255 karakter
            'username' => 'required|string|unique:users,username', // Username wajib, unik
            'email' => 'required|email|unique:users,email', // Email wajib, format email, unik
            'password' => 'required|string|min:8', // Kata sandi wajib, minimal 8 karakter
            'membership_date' => 'required|date', // Tanggal keanggotaan wajib, format tanggal
        ]);

        // Membuat pengguna baru
        $user = User::create([
            'user_id' => $request->user_id, // ID pengguna
            'name' => $request->name, // Nama pengguna
            'username' => strtolower($request->username), // Username dalam huruf kecil
            'email' => $request->email, // Email pengguna
            'password' => bcrypt($request->password), // Kata sandi dienkripsi
            'membership_date' => $request->membership_date, // Tanggal keanggotaan
        ]);

        // Mengembalikan respons sukses dengan status 201 (Created)
        return response()->json([
            'message' => 'Akun pengguna berhasil ditambahkan.',
            'data' => $user,
        ], 201);
    }

    /**
     * Memperbarui data pengguna berdasarkan user_id.
     */
    public function update(Request $request, $user_id): JsonResponse
    {
        try {
            // Mencari pengguna berdasarkan user_id, gagal jika tidak ditemukan
            $user = User::findOrFail($user_id);

            // Validasi input, menggunakan 'sometimes' agar kolom opsional
            $request->validate([
                'name' => 'sometimes|string|max:255', // Nama opsional, maksimal 255 karakter
                'email' => 'sometimes|email|unique:users,email,' . $user_id . ',user_id', // Email opsional, unik kecuali untuk user_id ini
                'username' => 'sometimes|string|max:255|unique:users,username,' . $user_id . ',user_id', // Username opsional, unik kecuali untuk user_id ini
                'password' => 'sometimes|string|min:8', // Kata sandi opsional, minimal 8 karakter
                'membership_date' => 'sometimes|date', // Tanggal keanggotaan opsional, format tanggal
            ]);

            // Mengambil hanya kolom yang dikirim dalam request
            $data = $request->only(['name', 'email', 'username', 'password', 'membership_date']);
            // Mengenkripsi kata sandi jika ada dalam request
            if (isset($data['password'])) {
                $data['password'] = bcrypt($data['password']);
            }

            // Memperbarui data pengguna
            $user->update($data);

            // Mengembalikan respons berdasarkan apakah ada perubahan
            return response()->json([
                'message' => $user->wasChanged()
                    ? 'Data pengguna berhasil diperbarui.'
                    : 'Tidak ada perubahan yang dilakukan.',
                'data' => $user,
            ], 200);
        } catch (ModelNotFoundException $e) {
            // Mengembalikan pesan error jika pengguna tidak ditemukan
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        } catch (Exception $e) {
            // Mengembalikan pesan error untuk kesalahan lainnya
            return response()->json(['message' => 'Terjadi kesalahan saat update.'], 500);
        }
    }

    /**
     * Menghapus pengguna berdasarkan user_id.
     */
    public function destroy($user_id): JsonResponse
    {
        try {
            // Mencari pengguna berdasarkan user_id, gagal jika tidak ditemukan
            $user = User::findOrFail($user_id);
            // Menghapus pengguna
            $user->delete();

            // Mengembalikan pesan sukses
            return response()->json(['message' => 'User berhasil dihapus.']);
        } catch (ModelNotFoundException $e) {
            // Mengembalikan pesan error jika pengguna tidak ditemukan
            return response()->json(['message' => 'User tidak ditemukan.'], 404);
        } catch (Exception $e) {
            // Mengembalikan pesan error untuk kesalahan lainnya
            return response()->json(['message' => 'Terjadi kesalahan saat menghapus.'], 500);
        }
    }

    /**
     * Menghitung total jumlah pengguna.
     */
    public function count(): JsonResponse
    {
        // Menghitung jumlah total pengguna di tabel users
        $count = User::count();
        // Mengembalikan jumlah dalam format JSON
        return response()->json([
            'total' => $count
        ]);
    }
}