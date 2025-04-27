<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthenticationController extends Controller
{

        public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $user = \App\Models\User::where('email', $credentials['email'])->first();

        if (!$user || !\Illuminate\Support\Facades\Hash::check($credentials['password'], $user->password)) {
            return response()->json([
                'status'  => false,
                'message' => 'Email atau password salah.'
            ], 401);
        }

        // Buat token baru
        $token = $user->createToken('login-token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Login berhasil',
            'data'    => [
                'user'  => $user,
                'token' => $token
            ]
        ], 200);
    }

    public function register(Request $request)
    {
        // Validasi input
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string|max:255',
            'dob'      => 'required|date',
            'email'    => 'required|email|unique:users,email',
            'no_telp'  => 'required|string|max:15',
            'gender'   => 'required|in:male,female',
            'role'     => 'required|string',
            'password' => 'required|string|min:6',
            'pin'      => 'nullable|string|max:6',
            'photo'    => 'nullable|string',
            'skill'    => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status'  => false,
                'message' => 'Validation error',
                'errors'  => $validator->errors()
            ], 422);
        }

        // Simpan user
        $user = User::create([
            'name'     => $request->name,
            'dob'      => $request->dob,
            'email'    => $request->email,
            'no_telp'  => $request->no_telp,
            'gender'   => $request->gender,
            'role'     => $request->role,
            'password' => Hash::make($request->password),
            'pin'      => $request->pin,
            'photo'    => $request->photo,
            'skill'    => $request->skill,
        ]);

        // Buat token untuk user
        $token = $user->createToken('user-token')->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'User registered successfully',
            'data'    => [
                'user'  => $user,
                'token' => $token
            ]
        ], 201);
    }

    public function logout(Request $request)
    {
        // Hapus semua token milik user yang sedang login
        $request->user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logout berhasil. Token dihapus.'
        ]);
    }

}
