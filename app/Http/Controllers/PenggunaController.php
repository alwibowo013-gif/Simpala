<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class PenggunaController extends Controller
{
    public function index()
    {
        $pengguna = User::orderBy('role')->orderBy('name')->get();

        return view('admin.pengguna.index', compact('pengguna'));
    }

    public function create()
    {
        return view('admin.pengguna.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'nis_nip' => 'required|string|max:20|unique:users,nis_nip',
            'email' => 'required|email|max:100|unique:users,email',
            'password' => 'required|string|min:6',
            'role' => 'required|in:admin,siswa',
        ], [
            'nis_nip.unique' => 'NIS/NIP ini sudah dipakai akun lain.',
            'email.unique' => 'Email ini sudah dipakai akun lain.',
        ]);

        // Password WAJIB di-hash, tidak boleh disimpan plain text
        $validated['password'] = Hash::make($validated['password']);

        User::create($validated);

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    public function edit(User $pengguna)
    {
        return view('admin.pengguna.edit', compact('pengguna'));
    }

    public function update(Request $request, User $pengguna)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'nis_nip' => 'required|string|max:20|unique:users,nis_nip,' . $pengguna->id,
            'email' => 'required|email|max:100|unique:users,email,' . $pengguna->id,
            'password' => 'nullable|string|min:6', // kosong = password tidak diubah
            'role' => 'required|in:admin,siswa',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            // Kalau admin tidak mengisi password baru, hapus dari array
            // supaya kolom password lama TIDAK ikut ke-update jadi kosong.
            unset($validated['password']);
        }

        $pengguna->update($validated);

        return redirect()->route('admin.pengguna.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    public function destroy(User $pengguna)
    {
        // Guard: admin tidak boleh menghapus akunnya sendiri (bisa bikin ke-lock out dari sistem)
        if ($pengguna->id === auth()->id()) {
            return back()->with('error', 'Kamu tidak bisa menghapus akunmu sendiri.');
        }

        // Hapus juga file foto profilnya (kalau ada) supaya tidak jadi file yatim di storage
        if ($pengguna->foto_profil) {
            Storage::disk('public')->delete($pengguna->foto_profil);
        }

        $pengguna->delete();

        return redirect()->route('admin.pengguna.index')->with('success', 'Pengguna berhasil dihapus.');
    }
}
