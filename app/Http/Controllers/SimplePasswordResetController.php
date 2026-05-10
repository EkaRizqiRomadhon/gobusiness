<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class SimplePasswordResetController extends Controller
{
    // 1. Tampilkan form verifikasi
    public function showVerificationForm()
    {
        return view('auth.simple-forgot-password');
    }

    // 2. Proses verifikasi data
    public function verifyUser(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'business_name' => 'required|string',
        ]);

        $user = User::where('email', $request->email)
                    ->where('business_name', $request->business_name)
                    ->first();

        if (!$user) {
            return back()->withErrors([
                'error' => 'Data tidak cocok. Pastikan Email dan Nama Bisnis benar.',
            ])->withInput();
        }

        // Simpan ID user ke session sementara (berlaku sebentar saja)
        session(['reset_user_id' => $user->id]);

        return redirect()->route('simple.password.resetForm');
    }

    // 3. Tampilkan form buat password baru
    public function showResetForm()
    {
        if (!session()->has('reset_user_id')) {
            return redirect()->route('simple.password.request')->withErrors(['error' => 'Sesi kedaluwarsa. Silakan verifikasi ulang.']);
        }

        return view('auth.simple-reset-password');
    }

    // 4. Proses simpan password baru
    public function updatePassword(Request $request)
    {
        if (!session()->has('reset_user_id')) {
            return redirect()->route('simple.password.request');
        }

        $request->validate([
            'password' => 'required|min:8|confirmed',
        ], [
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        $user = User::find(session('reset_user_id'));
        if ($user) {
            $user->password = Hash::make($request->password);
            $user->save();
        }

        // Hapus session
        session()->forget('reset_user_id');

        return redirect()->route('login')->with('success', 'Password berhasil diperbarui! Silakan masuk.');
    }
}
