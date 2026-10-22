<?php

namespace App\Http\Controllers;

use App\Models\KategoriAlat;
use Illuminate\Http\Request;

class KategoriAlatController extends Controller
{
    // READ - Menampilkan semua data kategori
    public function index()
    {
        // orderBy biar urutannya rapi dari yang terbaru
        $kategori = KategoriAlat::orderBy('nama_kategori')->get();

        return view('admin.kategori.index', compact('kategori'));
    }

    // Menampilkan form tambah data
    public function create()
    {
        return view('admin.kategori.create');
    }

    // CREATE - Menyimpan data baru dari form
    public function store(Request $request)
    {
        // Validasi: nama_kategori wajib diisi, maksimal 100 karakter, tidak boleh duplikat
        $validated = $request->validate([
            'nama_kategori' => 'required|string|max:100|unique:kategori_alat,nama_kategori',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'nama_kategori.unique' => 'Kategori dengan nama ini sudah ada.',
        ]);

        KategoriAlat::create($validated);

        // redirect() + with('success', ...) untuk munculin alert hijau di halaman index
        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil ditambahkan.');
    }

    // Menampilkan form edit, $kategori otomatis di-fetch oleh Laravel (Route Model Binding)
    public function edit(KategoriAlat $kategori)
    {
        return view('admin.kategori.edit', compact('kategori'));
    }

    // UPDATE - Menyimpan perubahan data
    public function update(Request $request, KategoriAlat $kategori)
    {
        $validated = $request->validate([
            // ignore($kategori->id) supaya validasi unique tidak menolak nama yang sama dengan data itu sendiri
            'nama_kategori' => 'required|string|max:100|unique:kategori_alat,nama_kategori,' . $kategori->id,
        ]);

        $kategori->update($validated);

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    // DELETE - Menghapus data
    public function destroy(KategoriAlat $kategori)
    {
        // Kalau kategori masih dipakai oleh alat lain, migration kita set onDelete('cascade')
        // artinya alat yang pakai kategori ini juga akan ikut terhapus. Hati-hati saat pakai fitur ini.
        $kategori->delete();

        return redirect()->route('admin.kategori.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
