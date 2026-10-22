@extends('layouts.admin')
@section('title', 'Ringkasan')

@section('content')
    <!-- Executive Hero Banner Admin dengan Ikon Lonceng Terintegrasi -->
<div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #0f766e 100%); border-radius: 16px; padding: 1.8rem 2.2rem; color: white; margin-bottom: 1.8rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);">
    <div>
        <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem;">
            <span style="background: rgba(20, 184, 166, 0.2); color: #2dd4bf; font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 999px; border: 1px solid rgba(45, 212, 191, 0.3); letter-spacing: 0.05em; text-transform: uppercase;">
                🛡️ CONTROL CENTER
            </span>
            <span style="font-size: 0.78rem; color: #94a3b8; font-weight: 500;">
                • Sarpras SMK Negeri 5 Surakarta
            </span>
        </div>
        <h1 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.3rem; letter-spacing: -0.02em;">
            Ringkasan Inventaris & Transaksi
        </h1>
        <p style="font-size: 0.88rem; color: #cbd5e1; margin: 0; max-width: 600px;">
            Pantau sirkulasi alat praktikum, verifikasi peminjaman siswa, dan analisis ketersediaan stok secara rinci.
        </p>
    </div>

    <!-- Tombol Aksi & Ikon Lonceng Notifikasi Terintegrasi -->
    <div style="display: flex; gap: 0.8rem; flex-wrap: wrap; align-items: center;">
        <a href="{{ route('admin.alat.create') }}" class="btn-simpala" style="background: #14b8a6; color: #0f172a; font-weight: 700; padding: 0.7rem 1.2rem; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; border: none; font-size: 0.85rem;">
            + Tambah Alat
        </a>
        <a href="{{ route('admin.verifikasi.index') }}" class="btn-outline" style="background: rgba(255, 255, 255, 0.1); color: white; border: 1px solid rgba(255, 255, 255, 0.2); font-weight: 600; padding: 0.7rem 1.1rem; border-radius: 10px; text-decoration: none; font-size: 0.85rem;">
            ⚡ Verifikasi
        </a>

        <!-- Ikon Lonceng Notifikasi di dalam Hero Banner -->
        @php
    // Menghitung status menunggu dengan mencakup berbagai kemungkinan format huruf di database
    $pendingCount = \App\Models\Peminjaman::whereIn('status', ['menunggu', 'Menunggu', 'PENDING', 'pending'])->count();
@endphp
        <a href="{{ route('admin.verifikasi.index') }}" title="Peminjaman Menunggu Verifikasi" style="position: relative; width: 42px; height: 42px; background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.2); border-radius: 10px; display: flex; align-items: center; justify-content: center; text-decoration: none; font-size: 1.1rem; transition: all 0.2s ease;">
            🔔
            @if($pendingCount > 0)
                <span style="position: absolute; top: -6px; right: -6px; background: #ef4444; color: white; font-size: 0.68rem; font-weight: 700; padding: 0.1rem 0.45rem; border-radius: 999px; border: 2px solid #0f172a; box-shadow: 0 2px 4px rgba(239, 68, 68, 0.4);">
                    {{ $pendingCount }}
                </span>
            @endif
        </a>
    </div>
</div>

    <!-- Ringkasan Statistik & Konten Utama Admin -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="top-row"><span class="label">Alat tersedia</span><span class="icon">🗄️</span></div>
            <div class="value">{{ $tersedia }}</div>
            <div class="sub">dari {{ $totalAlat }} unit</div>
        </div>

        <div class="stat-card">
            <div class="top-row"><span class="label">Sedang dipinjam</span><span class="icon">🔄</span></div>
            <div class="value">{{ $sedangDipinjam }}</div>
            <div class="sub">berdasarkan selisih stok</div>
        </div>

        <div class="stat-card">
            <div class="top-row"><span class="label">Terlambat</span><span class="icon">⏰</span></div>
            <div class="value">{{ $terlambat }}</div>
            <div class="sub">perlu ditindaklanjuti</div>
        </div>

        <div class="stat-card">
            <div class="top-row"><span class="label">Menunggu verifikasi</span><span class="icon">✅</span></div>
            <div class="value">{{ $menungguVerifikasi }}</div>
            <div class="sub"><a href="{{ route('admin.verifikasi.index') }}" style="color:#1f6f82;">lihat semua &rarr;</a></div>
        </div>
    </div>

    <div style="display:flex; gap:1.3rem; align-items:flex-start; flex-wrap:wrap;">
        <div class="card-panel" style="flex:2 1 420px;">
            <div class="card-panel-title">Pengajuan Terbaru</div>
            <table class="simple-table">
                <thead>
                    <tr><th>Peminjam</th><th>Alat</th><th>Tanggal</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @forelse ($pengajuanTerbaru as $item)
                        @php
                            $d = $item->detailUtama();
                            $map = ['pending' => ['Menunggu', 'badge-rusak'], 'approved' => ['Disetujui', 'badge-baik'], 'rejected' => ['Ditolak', null], 'returned' => ['Selesai', null]];
                            [$label, $class] = $map[$item->status] ?? [$item->status, null];
                        @endphp
                        <tr>
                            <td>{{ $item->user->name ?? '-' }}</td>
                            <td>{{ $d?->alat?->nama_alat ?? '-' }}</td>
                            <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                            <td>
                                @if ($class)
                                    <span class="badge-status {{ $class }}">{{ $label }}</span>
                                @else
                                    <span class="badge-status" style="background:#e6edf7; color:#3856a8;">{{ $label }}</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="4" style="text-align:center; color:#9aa3b5; padding:1.5rem;">Belum ada pengajuan.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="card-panel" style="flex:1 1 280px;">
            <div class="card-panel-title">Stok per Kategori</div>
            <table class="simple-table">
                <thead><tr><th>Kategori</th><th>Stok</th><th>%</th></tr></thead>
                <tbody>
                    @forelse ($stokPerKategori as $nama => $jumlah)
                        <tr>
                            <td>{{ $nama }}</td>
                            <td>{{ $jumlah }}</td>
                            <td>{{ $totalAlat > 0 ? round(($jumlah / $totalAlat) * 100) : 0 }}%</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" style="text-align:center; color:#9aa3b5; padding:1.5rem;">Belum ada data.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

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

    <!-- ===== POP-UP SELAMAT DATANG (WELCOME NOTIFICATION) DENGAN ANIMASI SLIDE-UP & TANGAN MELAMBAI ===== -->
    @if(session('login_success'))
    <div id="welcomePopup" style="position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(5px); display: flex; align-items: center; justify-content: center; z-index: 9999; animation: fadeInModal 0.3s ease;">
        <div style="background: white; border-radius: 16px; padding: 2.2rem; width: 100%; max-width: 420px; text-align: center; box-shadow: 0 20px 40px rgba(0,0,0,0.25); position: relative; overflow: hidden; animation: slideUpModal 0.45s cubic-bezier(0.16, 1, 0.3, 1);">
            
            <!-- Aksen Garis Gradasi di Atas Card -->
            <div style="position: absolute; top: 0; left: 0; right: 0; height: 5px; background: linear-gradient(90deg, #16213e, #1f6f82, #14b8a6);"></div>

            <!-- Ikon Tangan Melambai (Waving Hand) -->
            <div class="waving-hand-icon" style="width: 70px; height: 70px; background: rgba(20, 184, 166, 0.12); color: #14b8a6; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-size: 32px; margin: 0 auto 1.2rem auto; box-shadow: 0 0 0 8px rgba(20, 184, 166, 0.05); transform-origin: 70% 70%;">
                👋
            </div>

            <h3 style="font-size: 1.35rem; font-weight: 800; color: #0f172a; margin-bottom: 0.5rem; letter-spacing: -0.02em;">
                Login Berhasil!
            </h3>
            
            <p style="font-size: 0.9rem; color: #475569; line-height: 1.6; margin-bottom: 1.8rem;">
                {{ session('login_success') }}
            </p>

            <button type="button" onclick="closeWelcomePopup()" style="width: 100%; background: linear-gradient(135deg, #16213e 0%, #1f6f82 100%); color: white; border: none; padding: 0.8rem; border-radius: 10px; font-weight: 700; font-size: 0.95rem; cursor: pointer; box-shadow: 0 4px 12px rgba(31, 111, 130, 0.3); transition: all 0.2s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
                Mulai Kelola Inventaris
            </button>
        </div>
    </div>

    <style>
        @keyframes fadeInModal {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        
        /* Animasi Slide Up dari Bawah dengan Efek Membal Halus */
        @keyframes slideUpModal {
            from { 
                opacity: 0; 
                transform: translateY(60px) scale(0.95); 
            }
            to { 
                opacity: 1; 
                transform: translateY(0) scale(1); 
            }
        }

        /* Animasi Ikon Tangan Melambai Gerak-gerik */
        @keyframes waveHand {
            0% { transform: rotate(0deg); }
            20% { transform: rotate(14deg); }
            40% { transform: rotate(-8deg); }
            60% { transform: rotate(14deg); }
            80% { transform: rotate(-4deg); }
            100% { transform: rotate(0deg); }
        }

        .waving-hand-icon {
            animation: waveHand 1.8s infinite ease-in-out;
        }
    </style>

    <script>
        function closeWelcomePopup() {
            const popup = document.getElementById('welcomePopup');
            if (popup) {
                popup.style.opacity = '0';
                popup.style.transform = 'translateY(20px)';
                popup.style.transition = 'all 0.25s ease';
                setTimeout(() => popup.remove(), 250);
            }
        }

        // Otomatis menutup sendiri setelah 5 detik jika tidak diklik
        setTimeout(() => {
            closeWelcomePopup();
        }, 5000);
    </script>
    @endif
@endsection