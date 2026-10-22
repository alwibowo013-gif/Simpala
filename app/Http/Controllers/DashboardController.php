<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;

class DashboardController extends Controller
{
    public function admin()
    {
        $totalAlat = Alat::sum('stok_total');
        $tersedia = Alat::sum('stok_tersedia');
        $sedangDipinjam = $totalAlat - $tersedia;

        $stokPerKategori = Alat::with('kategori')
            ->get()
            ->groupBy(fn ($alat) => $alat->kategori->nama_kategori ?? 'Tanpa Kategori')
            ->map(fn ($group) => $group->sum('stok_total'));

        // Sekarang tabel peminjaman sudah ada isinya (Minggu 5), jadi 2 statistik
        // ini yang tadinya placeholder "-" sudah bisa dihitung beneran.
        $menungguVerifikasi = Peminjaman::where('status', 'pending')->count();

        // Terlambat = statusnya masih 'approved' (belum dikembalikan) TAPI
        // tanggal rencana kembalinya sudah lewat dari hari ini.
        $terlambat = Peminjaman::where('status', 'approved')
            ->whereDate('tanggal_kembali_rencana', '<', now()->toDateString())
            ->count();

        // 3 pengajuan terbaru untuk ditampilkan di panel "Pengajuan terbaru" seperti desain Figma
        $pengajuanTerbaru = Peminjaman::with(['user', 'detail.alat'])->latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalAlat', 'tersedia', 'sedangDipinjam', 'stokPerKategori',
            'menungguVerifikasi', 'terlambat', 'pengajuanTerbaru'
        ));
    }
    public function siswa()
    {
        $userId = auth()->user()->id;
        // Hitung statistik khusus siswa yang login
        $sedangDipinjam = Peminjaman::where('user_id', $userId)->where('status', 'approved')->count();
        $menungguVerifikasi = Peminjaman::where('user_id', $userId)->where('status', 'pending')->count();
        $totalSelesai = Peminjaman::where('user_id', $userId)->where('status', 'completed')->count();

        // Ambil 4 alat yang paling baru/tersedia untuk rekomendasi di beranda
        $alatPopuler = Alat::with('kategori')->where('stok_tersedia', '>', 0)->latest()->take(7)->get();

        return view('siswa.beranda', compact('sedangDipinjam', 'menungguVerifikasi', 'totalSelesai', 'alatPopuler'));
    }
}
