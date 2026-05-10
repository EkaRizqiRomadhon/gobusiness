<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Auth\Events\PasswordReset;

class PasswordResetController extends Controller
{
    /**
     * Show the "Forgot Password" form.
     */
    public function showForgotForm()
    {
        return view('auth.forgot-password');
    }

    /**
     * Handle the email submission for password reset link.
     */
    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Check if the email exists BEFORE sending the reset link
        $user = \App\Models\User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak terdaftar di sistem kami.',
            ])->withInput();
        }

        // Use Laravel's built-in Password broker to send the reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            return back()->with('success', 'Link reset password telah dikirim ke email Anda. Silakan cek inbox atau folder spam.');
        }

        return back()->withErrors([
            'email' => 'Gagal mengirim email. Coba lagi beberapa saat.',
        ])->withInput();
    }

    /**
     * Show the "Reset Password" form.
     */
    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->email,
        ]);
    }

    /**
     * Handle the new password submission.
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => ['required'],
            'email'                 => ['required', 'email'],
            'password'              => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'password.min'              => 'Password minimal 8 karakter.',
            'password.confirmed'        => 'Konfirmasi password tidak cocok.',
            'password_confirmation.required' => 'Konfirmasi password wajib diisi.',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password'       => Hash::make($password),
                    'remember_token' => Str::random(60),
                ])->save();

                event(new PasswordReset($user));
            }
        );

        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Password berhasil diperbarui! Silakan masuk dengan password baru Anda.');
        }

        return back()->withErrors([
            'email' => match ($status) {
                Password::INVALID_TOKEN => 'Link reset password tidak valid atau sudah kedaluwarsa.',
                Password::INVALID_USER  => 'Email tidak ditemukan.',
                default                 => 'Terjadi kesalahan, silakan coba lagi.',
            },
        ])->withInput();
    }
}
