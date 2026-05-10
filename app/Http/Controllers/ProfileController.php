<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function index()
    {
        return view('pages.profile.index', [
            'user' => Auth::user()
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'qris_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        $user = Auth::user();
        $user->name = $request->name;

        if ($request->hasFile('qris_image')) {
            // Delete old QRIS if exists
            if ($user->qris_path) {
                Storage::disk('public')->delete($user->qris_path);
            }
            $path = $request->file('qris_image')->store('qris', 'public');
            $user->qris_path = $path;
        }

        $user->save();

        return back()->with('success', 'Profil dan QRIS berhasil diperbarui.');
    }

    public function settings()
    {
        return view('pages.profile.settings', [
            'user' => Auth::user()
        ]);
    }

    public function destroy(Request $request)
    {
        $user = Auth::user();
        
        // Hapus QRIS dari storage jika ada
        if ($user->qris_path) {
            Storage::disk('public')->delete($user->qris_path);
        }

        Auth::logout();
        
        $user->delete();
        
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login')->with('success', 'Akun Anda telah berhasil dihapus.');
    }
}
