<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        /* dompdf cuma support CSS dasar, jadi styling di sini sengaja simpel */
        body { font-family: sans-serif; font-size: 12px; color: #1f2937; }
        h2 { color: #16213e; margin-bottom: 2px; }
        .sub { color: #6b7280; margin-bottom: 16px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ccc; padding: 6px 8px; text-align: left; font-size: 11px; }
        th { background: #eef2fb; }
        .stat-row { margin-bottom: 14px; }
        .stat-box { display: inline-block; width: 30%; border: 1px solid #ddd; padding: 8px; margin-right: 1%; }
        .stat-box .label { font-size: 10px; color: #6b7280; }
        .stat-box .value { font-size: 16px; font-weight: bold; color: #16213e; }
    </style>
</head>
<body>
    <h2>Laporan Peminjaman Alat - SIMPALA</h2>
    <div class="sub">SMK Negeri 5 Surakarta - Periode {{ \Carbon\Carbon::create($tahun, $bulan)->translatedFormat('F Y') }}</div>

    <div class="stat-row">
        <div class="stat-box">
            <div class="label">Total Peminjaman</div>
            <div class="value">{{ $totalPeminjaman }}</div>
        </div>
        <div class="stat-box">
            <div class="label">Tingkat Kembali Tepat Waktu</div>
            <div class="value">{{ $persenTepatWaktu }}%</div>
        </div>
        <div class="stat-box">
            <div class="label">Alat Bermasalah</div>
            <div class="value">{{ $alatBermasalah }}</div>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Kode</th>
                <th>Peminjam</th>
                <th>Alat</th>
                <th>Tgl Pinjam</th>
                <th>Rencana Kembali</th>
                <th>Status</th>
                <th>Kondisi Kembali</th>
                <th>Ganti Rugi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($peminjamanBulanIni as $p)
                @php $d = $p->detailUtama(); @endphp
                <tr>
                    <td>{{ $p->kodeTransaksi() }}</td>
                    <td>{{ $p->user->name ?? '-' }}</td>
                    <td>{{ $d?->alat?->nama_alat ?? '-' }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($p->tanggal_kembali_rencana)->format('d M Y') }}</td>
                    <td>{{ ucfirst($p->status) }}</td>
                    <td>{{ $d?->kondisi_kembali ? ucfirst($d->kondisi_kembali) : '-' }}</td>
                    <td>{{ $d && $d->status_ganti_rugi !== 'tidak_ada' ? ucfirst($d->status_ganti_rugi) : '-' }}</td>
                </tr>
            @empty
                <tr><td colspan="8" style="text-align:center;">Tidak ada data pada periode ini.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
