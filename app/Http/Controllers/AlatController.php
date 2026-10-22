<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\KategoriAlat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AlatController extends Controller
{
    public function index(Request $request)
    {
        $query = Alat::with('kategori');

        if ($request->filled('cari')) {
            $keyword = $request->cari;
            $query->where(function ($q) use ($keyword) {
                $q->where('nama_alat', 'like', "%{$keyword}%")
                  ->orWhere('kode_alat', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('kategori_id')) {
            $query->where('kategori_id', $request->kategori_id);
        }

        if ($request->filled('jurusan')) {
            $query->where('jurusan', $request->jurusan);
        }

        $alat = $query->latest()->paginate(10)->withQueryString();
        $kategoriList = KategoriAlat::orderBy('nama_kategori')->get();

        return view('admin.alat.index', compact('alat', 'kategoriList'));
    }

    public function create()
    {
        $kategoriList = KategoriAlat::orderBy('nama_kategori')->get();

        return view('admin.alat.create', compact('kategoriList'));
    }

    /**
     * Helper privat untuk men-generate kode alat otomatis berdasarkan kategori & jurusan
     */
    private function generateKodeAlat($kategoriId, $jurusan)
    {
        $kategori = KategoriAlat::find($kategoriId);
        $namaKategori = $kategori ? strtolower($kategori->nama_kategori) : '';

        $prefix = 'ALT'; // Default prefix jika tidak dikenali

        // Cek apakah kategori berkaitan dengan praktikum / jurusan
        if (str_contains($namaKategori, 'praktikum') && !empty($jurusan)) {
            $j = strtolower($jurusan);
            if (str_contains($j, 'rekayasa perangkat lunak') || str_contains($j, 'rpl')) {
                $prefix = 'JRPL';
            } elseif (str_contains($j, 'listrik')) {
                $prefix = 'JLST';
            } elseif (str_contains($j, 'mesin')) {
                $prefix = 'JMSN';
            } elseif (str_contains($j, 'elektronika')) {
                $prefix = 'JELK';
            } elseif (str_contains($j, 'dpib') || str_contains($j, 'bangunan')) {
                $prefix = 'JBGN';
            } elseif (str_contains($j, 'otomotif')) {
                $prefix = 'JOTM';
            } elseif (str_contains($j, 'otomasi')) {
                $prefix = 'JOTS';
            }
        } else {
            // Berdasarkan nama kategori umum
            if (str_contains($namaKategori, 'multimedia')) {
                $prefix = 'MLT';
            } elseif (str_contains($namaKategori, 'olahraga')) {
                $prefix = 'OLG';
            } elseif (str_contains($namaKategori, 'elektronik')) {
                $prefix = 'ELT';
            } elseif ($kategori) {
                // Ambil 3 huruf depan dari nama kategori jika ada
                $prefix = strtoupper(substr(preg_replace('/[^a-zA-Z]/', '', $kategori->nama_kategori), 0, 3));
            }
        }

        // Cari nomor urut terakhir dengan prefix yang sama
        $lastAlat = Alat::where('kode_alat', 'like', $prefix . '-%')
                        ->orderBy('id', 'desc')
                        ->first();

        $nextNumber = 1;
        if ($lastAlat) {
            // Ambil angka di belakang tanda strip (-) lalu increment
            $parts = explode('-', $lastAlat->kode_alat);
            $lastNum = isset($parts[1]) ? intval($parts[1]) : 0;
            $nextNumber = $lastNum + 1;
        }

        // Format 4 digit angka (contoh: JRPL-0001)
        return $prefix . '-' . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_alat,id',
            'jurusan'     => 'nullable|string|max:100',
            'nama_alat'   => 'required|string|max:100',
            'stok_total'  => 'required|integer|min:0',
            'kondisi'     => 'required|in:baik,rusak',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
            'gambar.image'     => 'File harus berupa gambar (jpg/png).',
            'gambar.max'       => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Generate kode alat secara otomatis oleh sistem
        $validated['kode_alat'] = $this->generateKodeAlat($validated['kategori_id'], $validated['jurusan'] ?? null);
        $validated['stok_tersedia'] = $validated['stok_total'];

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = $request->file('gambar')->store('alat', 'public');
        }

        Alat::create($validated);

        return redirect()->route('admin.alat.index')->with('success', 'Data alat berhasil ditambahkan dengan kode: ' . $validated['kode_alat']);
    }

    public function edit(Alat $alat)
    {
        $kategoriList = KategoriAlat::orderBy('nama_kategori')->get();

        return view('admin.alat.edit', compact('alat', 'kategoriList'));
    }

    public function update(Request $request, Alat $alat)
    {
        $validated = $request->validate([
            'kategori_id' => 'required|exists:kategori_alat,id',
            'jurusan'     => 'nullable|string|max:100',
            'nama_alat'   => 'required|string|max:100',
            'stok_total'  => 'required|integer|min:0',
            'kondisi'     => 'required|in:baik,rusak',
            'gambar'      => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // Jika kategori atau jurusan berubah, kita bisa update kodenya atau biarkan tetap (biasanya kode tetap, atau digenerate ulang jika belum pernah dipinjam. Di sini kita pertahankan kode lama atau sesuaikan kebutuhan).
        $validated['kode_alat'] = $alat->kode_alat; 

        if ($request->hasFile('gambar')) {
            if ($alat->gambar) {
                Storage::disk('public')->delete($alat->gambar);
            }
            $validated['gambar'] = $request->file('gambar')->store('alat', 'public');
        }

        $alat->update($validated);

        return redirect()->route('admin.alat.index')->with('success', 'Data alat berhasil diperbarui.');
    }

    public function destroy(Alat $alat)
    {
        if ($alat->gambar) {
            Storage::disk('public')->delete($alat->gambar);
        }

        $alat->delete();

        return redirect()->route('admin.alat.index')->with('success', 'Data alat berhasil dihapus.');
    }
}