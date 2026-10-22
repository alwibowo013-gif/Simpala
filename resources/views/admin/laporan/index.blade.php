@extends('layouts.admin')
@section('title', 'Laporan')

@section('content')
    <!-- Executive Hero Banner Control Center (Laporan Inventaris) -->
    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #0f766e 100%); border-radius: 16px; padding: 1.8rem 2.2rem; color: white; margin-bottom: 1.8rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);">
        <div>
            <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem;">
                <span style="background: rgba(20, 184, 166, 0.2); color: #2dd4bf; font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 999px; border: 1px solid rgba(45, 212, 191, 0.3); letter-spacing: 0.05em; text-transform: uppercase;">
                    📊 REKAP & ANALITIK
                </span>
                <span style="font-size: 0.78rem; color: #94a3b8; font-weight: 500;">
                    • Sarpras SMK Negeri 5 Surakarta
                </span>
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.3rem; letter-spacing: -0.02em;">
                Laporan & Analisis Inventaris
            </h1>
            <p style="font-size: 0.88rem; color: #cbd5e1; margin: 0; max-width: 600px;">
                Analisis statistik peminjaman, tingkat kepatuhan waktu pengembalian, dan ekspor dokumen laporan secara berkala.
            </p>
        </div>

        <div style="display: flex; gap: 0.6rem; flex-wrap: wrap;">
            <a href="{{ route('admin.laporan.export.pdf', request()->query()) }}" class="btn-outline" style="background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid rgba(255, 255, 255, 0.2); font-weight: 600; padding: 0.65rem 1rem; border-radius: 10px; text-decoration: none; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                ⬇️ Export PDF
            </a>
            <a href="{{ route('admin.laporan.export.csv', request()->query()) }}" class="btn-outline" style="background: #14b8a6; color: #0f172a; font-weight: 700; border: none; padding: 0.65rem 1rem; border-radius: 10px; text-decoration: none; font-size: 0.85rem; display: inline-flex; align-items: center; gap: 0.4rem;">
                ⬇️ Export Excel
            </a>
        </div>
    </div>

    {{-- Filter bulan/tahun & kategori --}}
    <form method="GET" class="search-row" style="margin-bottom: 1.5rem;">
        <select name="bulan" class="form-control" style="max-width:160px;">
            @foreach (['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $i => $nama)
                <option value="{{ $i + 1 }}" {{ $bulan == $i + 1 ? 'selected' : '' }}>{{ $nama }}</option>
            @endforeach
        </select>
        <select name="tahun" class="form-control" style="max-width:120px;">
            @for ($t = now()->year; $t >= now()->year - 2; $t--)
                <option value="{{ $t }}" {{ $tahun == $t ? 'selected' : '' }}>{{ $t }}</option>
            @endfor
        </select>
        <select name="kategori_id" class="form-control" style="max-width:200px;">
            <option value="">Semua kategori</option>
            @foreach ($kategoriList as $kat)
                <option value="{{ $kat->id }}" {{ $kategoriId == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
            @endforeach
        </select>
        <button type="submit" class="btn-simpala" style="background:#1f6f82;">Terapkan</button>
    </form>

    <div class="stat-grid" style="margin-bottom: 1.5rem;">
        <div class="stat-card">
            <div class="top-row"><span class="label">Total peminjaman</span><span class="icon">📈</span></div>
            <div class="value">{{ $totalPeminjaman }}</div>
            <div class="sub">bulan terpilih</div>
        </div>
        <div class="stat-card">
            <div class="top-row"><span class="label">Tingkat kembali tepat waktu</span><span class="icon">✅</span></div>
            <div class="value">{{ $persenTepatWaktu }}%</div>
            <div class="sub">dari transaksi selesai</div>
        </div>
        <div class="stat-card">
            <div class="top-row"><span class="label">Alat bermasalah</span><span class="icon">⚠️</span></div>
            <div class="value">{{ $alatBermasalah }}</div>
            <div class="sub">
                <span style="color:#a1710b;">{{ $gantiRugiMenunggu }} menunggu</span> ·
                <span style="color:#1f9254;">{{ $gantiRugiLunas }} lunas</span>
                @if ($gantiRugiMenunggu > 0)
                    · <a href="{{ route('admin.gantirugi.index') }}" style="color:#1f6f82;">kelola &rarr;</a>
                @endif
            </div>
        </div>
    </div>

    <div style="display:flex; gap:1.3rem; align-items:flex-start; flex-wrap:wrap;">
        <div class="card-panel" style="flex:2 1 420px;">
            <div class="card-panel-title">Tren Peminjaman (6 Bulan Terakhir)</div>
            <canvas id="trenChart" height="120"></canvas>
        </div>

        <div class="card-panel" style="flex:1 1 280px;">
            <div class="card-panel-title">Paling Sering Dipinjam</div>
            <table class="simple-table">
                <thead><tr><th>Alat</th><th>Jml</th></tr></thead>
                <tbody>
                    @forelse ($topAlat as $i => $t)
                        <tr>
                            <td>{{ $i + 1 }}. {{ $t->alat->nama_alat ?? 'Alat dihapus' }}</td>
                            <td>{{ $t->total_pinjam }}×</td>
                        </tr>
                    @empty
                        <tr><td colspan="2" style="text-align:center; color:#9aa3b5; padding:1rem;">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if ($gantiRugiList->count() > 0)
        <div class="card-panel" style="margin-top:1.3rem;">
            <div class="card-panel-title">Kasus Ganti Rugi Bulan Ini</div>
            <table class="simple-table">
                <thead><tr><th>Peminjam</th><th>Alat</th><th>Kondisi</th><th>Status</th><th>Catatan</th></tr></thead>
                <tbody>
                    @foreach ($gantiRugiList as $g)
                        <tr>
                            <td><strong>{{ $g->peminjaman->user->name ?? '-' }}</strong></td>
                            <td>{{ $g->alat->nama_alat ?? 'Alat dihapus' }}</td>
                            <td>{{ ucfirst($g->kondisi_kembali) }}</td>
                            <td>
                                @if ($g->status_ganti_rugi === 'menunggu')
                                    <span class="badge-status badge-rusak">Menunggu</span>
                                @else
                                    <span class="badge-status badge-baik">Lunas</span>
                                @endif
                            </td>
                            <td>{{ $g->catatan_ganti_rugi ?: '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif

    {{-- Chart.js dimuat dari CDN --}}
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    <script>
        const ctx = document.getElementById('trenChart');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode(collect($tren)->pluck('label')) !!},
                datasets: [{
                    label: 'Jumlah Peminjaman',
                    data: {!! json_encode(collect($tren)->pluck('jumlah')) !!},
                    backgroundColor: '#1f6f82',
                    borderRadius: 6,
                }]
            },
            options: {
                plugins: { legend: { display: false } },
                scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
            }
        });
    </script>

    <!-- ===== FOOTER UTAMA ADMIN SIMPALA ===== -->
    <footer style="background: #16213e; color: #9aa7c7; margin-top: 2.5rem; border-radius: 16px; border-top: 1px solid rgba(255,255,255,0.08); padding: 2rem; box-shadow: 0 10px 25px -5px rgba(22, 33, 62, 0.2);">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.8rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
            
            <!-- Kolom 1: Identity & Admin Portal -->
            <div>
                <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.8rem;">
                    <span style="background: #ffffff; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem;">⚙️</span>
                    <span style="font-weight: 800; color: #ffffff; font-size: 1.1rem; letter-spacing: 0.03em;">SIMPALA ADMIN</span>
                </div>
                <p style="font-size: 0.8rem; color: #b7c0d8; line-height: 1.5; margin: 0;">
                    Portal Manajemen Sarana & Prasarana SMK Negeri 5 Surakarta. Layanan pengawasan inventaris, verifikasi peminjaman, dan cetak laporan berkala.
                </p>
            </div>

            <!-- Kolom 2: Jam Operasional & Sistem Status -->
            <div>
                <h4 style="color: #ffffff; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    🕒 Jam Operasional
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.8rem; color: #b7c0d8; line-height: 1.8;">
                    <li>Senin – Kamis: <strong style="color: #ffffff;">07.00 – 15.30 WIB</strong></li>
                    <li>Jumat: <strong style="color: #ffffff;">07.00 – 14.00 WIB</strong></li>
                    <li>Status Server: <span style="color: #4cd964; font-weight: 700;">● Online</span></li>
                </ul>
            </div>

            <!-- Kolom 3: Pintasan Manajemen -->
            <div>
                <h4 style="color: #ffffff; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    🛠️ Pintasan Admin
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.8rem; display: flex; flex-direction: column; gap: 0.4rem;">
                    <li><a href="#" style="color: #5fd0e8; text-decoration: none;">📦 Kelola Master Data Alat</a></li>
                    <li><a href="#" style="color: #5fd0e8; text-decoration: none;">📑 Verifikasi Peminjaman</a></li>
                    <li><a href="#" style="color: #5fd0e8; text-decoration: none;">📊 Laporan & Transaksi</a></li>
                </ul>
            </div>

        </div>

        <!-- Bottom Footer (Copyright) -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.8rem; padding-top: 1.2rem; font-size: 0.75rem; color: #7c88a8;">
            <div>
                © 2026 <strong>SMK Negeri 5 Surakarta</strong> • Rekayasa Perangkat Lunak (RPL).
            </div>
            <div>
                Administrator Panel SIMPALA v1.0
            </div>
        </div>
    </footer>
@endsection