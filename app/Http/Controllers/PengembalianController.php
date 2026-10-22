<?php

namespace App\Http\Controllers;

use App\Models\Alat;
use App\Models\Peminjaman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PengembalianController extends Controller
{
    public function index()
    {
        $list = Peminjaman::with(['user', 'detail.alat'])
            ->where('status', 'approved')
            ->latest()
            ->get();

        return view('admin.pengembalian.index', compact('list'));
    }

    public function proses(Request $request, Peminjaman $peminjaman)
    {
        if ($peminjaman->status !== 'approved') {
            return back()->with('success', 'Peminjaman ini sudah diproses sebelumnya.');
        }

        $validated = $request->validate([
            'kondisi_kembali' => 'required|in:baik,rusak,hilang',
        ]);

        DB::transaction(function () use ($peminjaman, $validated) {
            foreach ($peminjaman->detail as $item) {
                // Rusak/hilang otomatis memicu status "menunggu ganti rugi".
                // Kondisi baik tidak perlu ganti rugi apa-apa.
                $statusGantiRugi = in_array($validated['kondisi_kembali'], ['rusak', 'hilang'])
                    ? 'menunggu'
                    : 'tidak_ada';

                $item->update([
                    'kondisi_kembali' => $validated['kondisi_kembali'],
                    'status_ganti_rugi' => $statusGantiRugi,
                ]);

                if ($validated['kondisi_kembali'] === 'baik') {
                    // Alat fisiknya balik dalam kondisi baik -> stok tersedia bertambah lagi
                    Alat::where('id', $item->alat_id)->increment('stok_tersedia', $item->jumlah);
                } elseif ($validated['kondisi_kembali'] === 'rusak') {
                    // Alat balik secara fisik (jadi tetap nambah stok_tersedia),
                    // tapi kondisi alat itu ditandai rusak untuk keperluan laporan
                    Alat::where('id', $item->alat_id)->increment('stok_tersedia', $item->jumlah);
                    Alat::where('id', $item->alat_id)->update(['kondisi' => 'rusak']);
                } else {
                    // kondisi 'hilang': barangnya PERMANEN tidak ada lagi.
                    // stok_tersedia TIDAK ditambah, dan stok_total ikut dikurangi
                    // supaya rasio tersedia/total tetap akurat selamanya
                    // (bukan cuma "kelihatan lagi dipinjam" terus-menerus).
                    Alat::where('id', $item->alat_id)->decrement('stok_total', $item->jumlah);
                }
            }

            $peminjaman->update([
                'status' => 'returned',
                'tanggal_kembali_aktual' => now()->toDateString(),
            ]);
        });

        return redirect()->route('admin.pengembalian.index')->with('success', 'Pengembalian berhasil dicatat.');
    }
}
