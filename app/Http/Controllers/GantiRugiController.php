<?php

namespace App\Http\Controllers;

use App\Models\PeminjamanDetail;
use Illuminate\Http\Request;

class GantiRugiController extends Controller
{
    public function index()
    {
        // Ambil semua detail peminjaman yang statusnya pernah kena ganti rugi
        // (baik yang masih menunggu maupun yang sudah lunas)
        $daftar = PeminjamanDetail::with(['alat', 'peminjaman.user'])
            ->whereIn('status_ganti_rugi', ['menunggu', 'lunas'])
            ->latest()
            ->get();

        $menunggu = $daftar->where('status_ganti_rugi', 'menunggu')->count();
        $lunas = $daftar->where('status_ganti_rugi', 'lunas')->count();

        return view('admin.gantirugi.index', compact('daftar', 'menunggu', 'lunas'));
    }

    public function tandaiLunas(Request $request, PeminjamanDetail $detail)
    {
        $validated = $request->validate([
            'catatan_ganti_rugi' => 'nullable|string|max:255',
        ]);

        $detail->update([
            'status_ganti_rugi' => 'lunas',
            'catatan_ganti_rugi' => $validated['catatan_ganti_rugi'] ?? $detail->catatan_ganti_rugi,
        ]);

        return back()->with('success', 'Ganti rugi berhasil ditandai lunas.');
    }
}
