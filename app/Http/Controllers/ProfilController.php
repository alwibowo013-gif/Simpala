<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfilController extends Controller
{
    // Halaman profil dipakai BERSAMA oleh Admin & Siswa. Yang ditampilkan selalu
    // data user yang sedang login ($request->user()), jadi tidak ada risiko
    // satu user melihat/mengubah profil user lain lewat URL.
    public function edit(Request $request)
    {
        return view('profil.edit', ['user' => $request->user()]);
    }

    public function updateInfo(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
        ]);

        // NIS/NIP, email, dan role SENGAJA tidak bisa diubah dari sini
        // (itu identitas akun, hanya Admin yang boleh mengubahnya lewat menu Pengguna)
        $request->user()->update($validated);

        return back()->with('success', 'Informasi profil berhasil diperbarui.');
    }

    public function updateFoto(Request $request)
    {
        $request->validate([
            'foto' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048', // maks 2MB
        ], [
            'foto.required' => 'Pilih file foto terlebih dahulu.',
            'foto.image' => 'File harus berupa gambar.',
            'foto.mimes' => 'Format foto harus jpg, jpeg, png, atau webp.',
            'foto.max' => 'Ukuran foto maksimal 2MB.',
        ]);

        $user = $request->user();

        // Hapus foto lama dulu supaya folder storage tidak menumpuk file yang sudah tidak terpakai
        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        $path = $request->file('foto')->store('profil', 'public');
        $user->update(['foto_profil' => $path]);

        return back()->with('success', 'Foto profil berhasil diganti.');
    }

    public function hapusFoto(Request $request)
    {
        $user = $request->user();

        if ($user->foto_profil) {
            Storage::disk('public')->delete($user->foto_profil);
            $user->update(['foto_profil' => null]);
        }

        return back()->with('success', 'Foto profil dihapus.');
    }

    public function updatePassword(Request $request)
    {
        $validated = $request->validate([
            'current_password' => 'required',
            'password' => 'required|string|min:6|confirmed', // "confirmed" = harus sama dengan field password_confirmation
        ], [
            'current_password.required' => 'Password lama wajib diisi.',
            'password.min' => 'Password baru minimal 6 karakter.',
            'password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = $request->user();

        // Cek password lama dulu. Tanpa ini, orang yang kebetulan memakai
        // laptop/sesi yang sedang login bisa langsung ganti password tanpa tahu password lamanya.
        if (!Hash::check($validated['current_password'], $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        }

        $user->update(['password' => Hash::make($validated['password'])]);

        return back()->with('success', 'Password berhasil diganti.');
    }
}
