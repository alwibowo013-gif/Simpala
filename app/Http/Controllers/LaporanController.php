<?php

namespace App\Http\Controllers;

use App\Models\KategoriAlat;
use App\Models\Peminjaman;
use App\Models\PeminjamanDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;

class LaporanController extends Controller
{
    private function hitungLaporan(Request $request): array
    {
        $bulan = $request->input('bulan', now()->month);
        $tahun = $request->input('tahun', now()->year);
        $kategoriId = $request->input('kategori_id');

        $query = Peminjaman::with(['user', 'detail.alat.kategori'])
            ->whereMonth('tanggal_pinjam', $bulan)
            ->whereYear('tanggal_pinjam', $tahun);

        if ($kategoriId) {
            $query->whereHas('detail.alat', fn ($q) => $q->where('kategori_id', $kategoriId));
        }

        $peminjamanBulanIni = $query->get();

        $totalPeminjaman = $peminjamanBulanIni->count();

        $sudahKembali = $peminjamanBulanIni->where('status', 'returned');
        $tepatWaktu = $sudahKembali->filter(function ($p) {
            return $p->tanggal_kembali_aktual && $p->tanggal_kembali_aktual <= $p->tanggal_kembali_rencana;
        });
        $persenTepatWaktu = $sudahKembali->count() > 0
            ? round(($tepatWaktu->count() / $sudahKembali->count()) * 100)
            : 0;

        $alatBermasalah = PeminjamanDetail::whereIn('kondisi_kembali', ['rusak', 'hilang'])
            ->whereHas('peminjaman', function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal_pinjam', $bulan)->whereYear('tanggal_pinjam', $tahun);
            })->count();

        // BARU: pecah alat bermasalah jadi "masih menunggu diganti" vs "sudah lunas",
        // supaya dari Laporan langsung kelihatan berapa yang masih jadi tanggungan siswa
        $gantiRugiMenunggu = PeminjamanDetail::where('status_ganti_rugi', 'menunggu')
            ->whereHas('peminjaman', function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal_pinjam', $bulan)->whereYear('tanggal_pinjam', $tahun);
            })->count();

        $gantiRugiLunas = PeminjamanDetail::where('status_ganti_rugi', 'lunas')
            ->whereHas('peminjaman', function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal_pinjam', $bulan)->whereYear('tanggal_pinjam', $tahun);
            })->count();

        // Daftar detailnya (bukan cuma angka) untuk ditampilkan di panel Laporan
        $gantiRugiList = PeminjamanDetail::with(['alat', 'peminjaman.user'])
            ->whereIn('status_ganti_rugi', ['menunggu', 'lunas'])
            ->whereHas('peminjaman', function ($q) use ($bulan, $tahun) {
                $q->whereMonth('tanggal_pinjam', $bulan)->whereYear('tanggal_pinjam', $tahun);
            })->latest()->get();

        $tren = [];
        for ($i = 5; $i >= 0; $i--) {
            $bulanKe = now()->subMonths($i);
            $tren[] = [
                'label' => $bulanKe->translatedFormat('M'),
                'jumlah' => Peminjaman::whereMonth('tanggal_pinjam', $bulanKe->month)
                    ->whereYear('tanggal_pinjam', $bulanKe->year)
                    ->count(),
            ];
        }

        $topAlat = PeminjamanDetail::selectRaw('alat_id, COUNT(*) as total_pinjam')
            ->groupBy('alat_id')
            ->orderByDesc('total_pinjam')
            ->with('alat')
            ->take(5)
            ->get();

        return compact(
            'peminjamanBulanIni', 'totalPeminjaman', 'persenTepatWaktu',
            'alatBermasalah', 'gantiRugiMenunggu', 'gantiRugiLunas', 'gantiRugiList',
            'tren', 'topAlat', 'bulan', 'tahun', 'kategoriId'
        );
    }

    public function index(Request $request)
    {
        $data = $this->hitungLaporan($request);
        $data['kategoriList'] = KategoriAlat::orderBy('nama_kategori')->get();

        return view('admin.laporan.index', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->hitungLaporan($request);

        $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('admin.laporan.pdf', $data);

        return $pdf->download('laporan-peminjaman-' . $data['bulan'] . '-' . $data['tahun'] . '.pdf');
    }

    public function exportCsv(Request $request)
    {
        $data = $this->hitungLaporan($request);
        $filename = 'laporan-peminjaman-' . $data['bulan'] . '-' . $data['tahun'] . '.csv';

        return response()->streamDownload(function () use ($data) {
            $out = fopen('php://output', 'w');

            // BARU: tambah kolom Kondisi Kembali & Ganti Rugi
            fputcsv($out, ['Kode', 'Peminjam', 'Alat', 'Tanggal Pinjam', 'Rencana Kembali', 'Status', 'Kondisi Kembali', 'Ganti Rugi']);

            foreach ($data['peminjamanBulanIni'] as $p) {
                $d = $p->detailUtama();
                fputcsv($out, [
                    $p->kodeTransaksi(),
                    $p->user->name ?? '-',
                    $d?->alat?->nama_alat ?? '-',
                    $p->tanggal_pinjam,
                    $p->tanggal_kembali_rencana,
                    $p->status,
                    $d?->kondisi_kembali ?? '-',
                    $d && $d->status_ganti_rugi !== 'tidak_ada' ? $d->status_ganti_rugi : '-',
                ]);
            }

            fclose($out);
        }, $filename, ['Content-Type' => 'text/csv']);
    }
}
