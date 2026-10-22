<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;

class VerifikasiController extends Controller
{
    // Menampilkan daftar semua pengajuan + panel detail di sebelah kanan (seperti desain Figma)
    public function index(Request $request)
    {
        $list = Peminjaman::with(['user', 'detail.alat'])->latest()->get();

        $counts = [
            'menunggu' => $list->where('status', 'pending')->count(),
            'disetujui' => $list->where('status', 'approved')->count(),
            'ditolak' => $list->where('status', 'rejected')->count(),
        ];

        // Kalau ada ?id=.. di URL, itu yang ditampilkan di panel detail kanan.
        // Kalau tidak ada, default-nya pilih item pertama di daftar.
        $selected = null;
        if ($request->filled('id')) {
            $selected = $list->firstWhere('id', (int) $request->id);
        }
        if (!$selected) {
            $selected = $list->first();
        }

        return view('admin.verifikasi.index', compact('list', 'counts', 'selected'));
    }

    public function approve(Request $request, Peminjaman $peminjaman)
    {
        // Guard: cegah approve dobel kalau tombolnya sempat ke-klik 2x atau dibuka di 2 tab
        if ($peminjaman->status !== 'pending') {
            return back()->with('success', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        $peminjaman->update([
            'status' => 'approved',
            'catatan_admin' => $request->catatan_admin,
        ]);

        return redirect()->route('admin.verifikasi.index', ['id' => $peminjaman->id])
            ->with('success', 'Pengajuan disetujui.');
    }

    public function reject(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'pending') {
            return back()->with('success', 'Pengajuan ini sudah diproses sebelumnya.');
        }

        // Karena stok sempat "dikunci" waktu siswa mengajukan (lihat PeminjamanController@store),
        // begitu ditolak stok itu WAJIB dikembalikan supaya tidak hilang percuma.
        foreach ($peminjaman->detail as $item) {
            Alat::where('id', $item->alat_id)->increment('stok_tersedia', $item->jumlah);
        }

        $peminjaman->update([
            'status' => 'rejected',
            'catatan_admin' => $request->catatan_admin,
        ]);

        return redirect()->route('admin.verifikasi.index', ['id' => $peminjaman->id])
            ->with('success', 'Pengajuan ditolak.');
    }
}
