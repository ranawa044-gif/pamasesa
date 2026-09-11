<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan halaman Pengaturan Akun Mahasiswa
     */
    public function index(Request $request): View
    {
        $user = $request->user()->load('student');
        $student = $user->student;

        return view('student.profil', compact('user', 'student'));
    }

    /**
     * Memperbarui password akun mahasiswa
     */
    public function updatePassword(Request $request): RedirectResponse
    {
        $user = $request->user();

        $validated = $request->validate([
            'current_password' => ['required', 'string'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.required' => 'Password baru wajib diisi.',
            'password.min' => 'Password baru minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        if (! Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password saat ini (lama) yang Anda masukkan salah.']);
        }

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        return back()->with('success', 'Password akun Anda berhasil diperbarui.');
    }

    /**
     * Memperbarui foto profil mahasiswa
     */
    public function updatePhoto(Request $request): RedirectResponse
    {
        $user = $request->user();

        $request->validate([
            'foto_profil' => ['required', 'image', 'mimes:jpg,jpeg,png', 'max:2048'],
        ], [
            'foto_profil.required' => 'Silakan pilih berkas foto terlebih dahulu.',
            'foto_profil.image' => 'Berkas yang dipilih harus berupa gambar.',
            'foto_profil.mimes' => 'Format foto harus berupa JPG, JPEG, atau PNG.',
            'foto_profil.max' => 'Ukuran foto maksimal adalah 2 MB.',
        ]);

        // Hapus foto profil lama jika ada di storage
        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        // Simpan foto baru ke folder profile_photos pada disk public
        $file = $request->file('foto_profil');
        $fileName = 'avatar_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('profile_photos', $fileName, 'public');

        $user->update([
            'foto_profil' => $path,
        ]);

        return back()->with('success', 'Foto profil berhasil diperbarui.');
    }
}
