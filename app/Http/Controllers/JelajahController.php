<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\KategoriAlat;
use Illuminate\Http\Request;

class JelajahController extends Controller
{
    public function index(Request $request)
    {
        $query = Alat::with('kategori')->where('stok_tersedia', '>', 0);

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

        $alat = $query->orderBy('nama_alat')->paginate(8)->withQueryString();
        $kategoriList = KategoriAlat::orderBy('nama_kategori')->get();

        return view('siswa.jelajah', compact('alat', 'kategoriList'));
    }
}