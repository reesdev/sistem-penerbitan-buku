<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        // 1. Cek apakah data yang dikirim valid
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // 2. Buat akun baru (Otomatis diberi role USER/Penulis)
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'USER',
        ]);

        // 3. Buatkan Token API sebagai tanda pengenal
        $token = $user->createToken('auth_token')->plainTextToken;

        // 4. Kembalikan balasan sukses
        return response()->json([
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ], 201);
    }
        public function login(Request $request)
    {
        // 1. Validasi input
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        // 2. Cari user di database
        $user = User::where('email', $request->email)->first();

        // 3. Cek kecocokan password
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Email atau Password salah.'
            ], 401); // 401 Unauthorized
        }

        // 4. Jika cocok, terbitkan Token baru
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'data' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ]);
    }
}