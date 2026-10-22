<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PeminjamanController extends Controller
{
    // Menampilkan form pengajuan. Kalau datang dari halaman Jelajah Alat (?alat_id=..),
    // alat itu langsung ditampilkan sebagai preview seperti di desain Figma.
    public function create(Request $request)
    {
        $alatTerpilih = null;

        if ($request->filled('alat_id')) {
            $alatTerpilih = Alat::find($request->alat_id);
        }

        // Kalau tidak datang dari Jelajah Alat, siswa tetap bisa pilih alat lewat dropdown
        $alatList = Alat::where('stok_tersedia', '>', 0)->orderBy('nama_alat')->get();

        return view('siswa.ajukan', compact('alatTerpilih', 'alatList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'alat_id' => 'required|exists:alat,id',
            'jumlah' => 'required|integer|min:1',
            'tanggal_pinjam' => 'required|date',
            'tanggal_kembali_rencana' => 'required|date|after_or_equal:tanggal_pinjam',
            'keperluan' => 'required|string|max:255',
            'setuju' => 'accepted', // checkbox tanggung jawab WAJIB dicentang
        ], [
            'setuju.accepted' => 'Kamu harus menyetujui pernyataan tanggung jawab dulu.',
            'tanggal_kembali_rencana.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
        ]);

        $alat = Alat::findOrFail($validated['alat_id']);

        // Validasi manual: jangan sampai siswa minta lebih banyak dari stok yang ada
        if ($validated['jumlah'] > $alat->stok_tersedia) {
            return back()->withErrors([
                'jumlah' => "Stok tidak mencukupi, tersisa {$alat->stok_tersedia} unit.",
            ])->withInput();
        }

        // DB::transaction memastikan kedua query (bikin peminjaman + kurangi stok)
        // sama-sama berhasil atau sama-sama gagal -- tidak ada kondisi setengah-jalan.
        DB::transaction(function () use ($validated) {
            $peminjaman = Peminjaman::create([
                'user_id' => auth()->id(),
                'tanggal_pinjam' => $validated['tanggal_pinjam'],
                'tanggal_kembali_rencana' => $validated['tanggal_kembali_rencana'],
                'keperluan' => $validated['keperluan'],
                'status' => 'pending',
            ]);

            PeminjamanDetail::create([
                'peminjaman_id' => $peminjaman->id,
                'alat_id' => $validated['alat_id'],
                'jumlah' => $validated['jumlah'],
            ]);

            // Stok "dikunci" begitu diajukan, supaya tidak ada siswa lain yang
            // rebutan alat yang sama sementara masih menunggu verifikasi.
            // Kalau nanti ditolak Admin, stok ini dikembalikan lagi (lihat VerifikasiController).
            Alat::where('id', $validated['alat_id'])->decrement('stok_tersedia', $validated['jumlah']);
        });

        return redirect()->route('pinjam.saya')->with('success', 'Pengajuan berhasil dikirim, menunggu verifikasi Admin.');
    }

    // Riwayat peminjaman milik siswa yang sedang login
    public function mine()
    {
        $userId = auth()->id();

        $peminjaman = Peminjaman::with('detail.alat')
            ->where('user_id', $userId)
            ->latest()
            ->get();

        $menunggu = $peminjaman->where('status', 'pending')->count();
        $dipinjam = $peminjaman->where('status', 'approved')->count();
        $selesai = $peminjaman->whereIn('status', ['returned', 'rejected'])->count();

        return view('siswa.riwayat', compact('peminjaman', 'menunggu', 'dipinjam', 'selesai'));
    }
}
