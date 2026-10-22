@extends('layouts.siswa')
@section('title', 'Peminjaman Saya')

@section('content')
    {{-- Hero Banner (Konsisten dengan Beranda & Jelajah) --}}
    <div style="background: linear-gradient(135deg, #16213e 0%, #1f6f82 100%); border-radius:16px; padding:1.8rem 2rem; margin-bottom:1.5rem; color:white; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; box-shadow: 0 10px 25px -5px rgba(31, 111, 130, 0.25);">
        <div>
            <div style="font-size:0.72rem; color:#7fd4e8; font-weight:700; letter-spacing:0.05em; margin-bottom:0.4rem; text-transform:uppercase;">
                RIWAYAT TRANSMISI & PEMINJAMAN
            </div>
            <div style="font-size:1.4rem; font-weight:700; margin-bottom:0.3rem;">Peminjaman Saya</div>
            <div style="font-size:0.85rem; color:#b7c0d8;">Pantau status pengajuan, proses verifikasi, dan riwayat pengembalian alatmu.</div>
        </div>
        <div>
            <a href="{{ route('jelajah') }}" class="btn-simpala" style="background:#ffffff; color:#16213e; font-weight:700; padding:0.7rem 1.2rem; border-radius:10px; text-decoration:none; display:inline-flex; align-items:center; gap:0.4rem; border:none;">
                + Ajukan Peminjaman
            </a>
        </div>
    </div>

    <!-- Ringkasan Statistik Status Peminjaman -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.2rem; margin-bottom: 1.8rem;">
        <div style="background: white; border: 1px solid #e5e9f0; border-radius: 12px; padding: 1.1rem 1.3rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
            <div style="font-size: 0.85rem; color: #4b5563;">Menunggu</div>
            <div style="font-size: 1.7rem; font-weight: 700; color: #16213e; margin-top: 0.5rem;">{{ $menunggu }}</div>
            <div style="font-size: 0.78rem; color: #9aa3b5;">sedang diverifikasi</div>
        </div>

        <div style="background: white; border: 1px solid #e5e9f0; border-radius: 12px; padding: 1.1rem 1.3rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
            <div style="font-size: 0.85rem; color: #4b5563;">Sedang dipinjam</div>
            <div style="font-size: 1.7rem; font-weight: 700; color: #16213e; margin-top: 0.5rem;">{{ $dipinjam }}</div>
            <div style="font-size: 0.78rem; color: #9aa3b5;">sudah disetujui</div>
        </div>

        <div style="background: white; border: 1px solid #e5e9f0; border-radius: 12px; padding: 1.1rem 1.3rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
            <div style="font-size: 0.85rem; color: #4b5563;">Selesai</div>
            <div style="font-size: 1.7rem; font-weight: 700; color: #16213e; margin-top: 0.5rem;">{{ $selesai }}</div>
            <div style="font-size: 0.78rem; color: #9aa3b5;">dikembalikan/ditolak</div>
        </div>
    </div>

    <!-- Layout 2 Kolom: Tabel Riwayat di Kiri, Bantuan di Kanan -->
    <div style="display: grid; grid-template-columns: 1fr 320px; gap: 1.5rem; align-items: start;">
        
        <!-- Kolom Kiri: Tabel Riwayat -->
        <div style="background:white; border:1px solid #e5e9f0; border-radius:12px; padding:1.3rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
            <div style="font-weight:700; color:#16213e; margin-bottom:1rem;">📋 Riwayat Peminjaman</div>
            <div style="overflow-x: auto;">
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr>
                            <th style="text-align:left; font-size:0.72rem; text-transform:uppercase; color:#9aa3b5; padding:0.5rem;">Kode</th>
                            <th style="text-align:left; font-size:0.72rem; text-transform:uppercase; color:#9aa3b5; padding:0.5rem;">Alat</th>
                            <th style="text-align:left; font-size:0.72rem; text-transform:uppercase; color:#9aa3b5; padding:0.5rem;">Tgl Pinjam</th>
                            <th style="text-align:left; font-size:0.72rem; text-transform:uppercase; color:#9aa3b5; padding:0.5rem;">Keperluan</th>
                            <th style="text-align:left; font-size:0.72rem; text-transform:uppercase; color:#9aa3b5; padding:0.5rem;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($peminjaman as $p)
                            @php $d = $p->detailUtama(); @endphp
                            <tr>
                                <td style="padding:0.7rem 0.5rem; border-top:1px solid #f3f5f9; font-size:0.85rem; font-weight: 600; color:#334155;">
                                    {{ $p->kodeTransaksi() }}
                                </td>
                                <td style="padding:0.7rem 0.5rem; border-top:1px solid #f3f5f9; font-size:0.85rem; font-weight:700; color:#16213e;">
                                    {{ $d?->alat?->nama_alat ?? '-' }}
                                </td>
                                <td style="padding:0.7rem 0.5rem; border-top:1px solid #f3f5f9; font-size:0.85rem;">
                                    {{ \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d M Y') }}
                                </td>
                                <td style="padding:0.7rem 0.5rem; border-top:1px solid #f3f5f9; font-size:0.85rem;">
                                    {{ $p->keperluan }}
                                </td>
                                
                                <td style="padding:0.7rem 0.5rem; border-top:1px solid #f3f5f9;">
                                    <div style="display:flex; align-items:center; gap:0.4rem; flex-wrap:wrap;">
                                        @php
                                            $map = [
                                                'pending' => ['Menunggu', 'badge-pending'], 
                                                'approved' => ['Disetujui', 'badge-approved'], 
                                                'rejected' => ['Ditolak', 'badge-rejected'], 
                                                'returned' => ['Selesai', 'badge-returned']
                                            ];
                                            [$label, $class] = $map[$p->status] ?? [$p->status, 'badge-pending'];
                                        @endphp
                                        <span class="badge-status {{ $class }}">{{ $label }}</span>
                                        
                                        @if ($d && $d->status_ganti_rugi === 'menunggu')
                                            <span class="badge-status" style="background:#fdecea; color:#b42318;">Perlu Ganti Rugi</span>
                                        @elseif ($d && $d->status_ganti_rugi === 'lunas')
                                            <span class="badge-status" style="background:#e6edf7; color:#3856a8;">Ganti Rugi Lunas</span>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" style="text-align:center; color:#9aa3b5; padding:2rem;">Belum ada riwayat peminjaman.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if (method_exists($peminjaman, 'hasPages') && $peminjaman->hasPages())
                <div style="margin-top:1rem;">
                    {{ $peminjaman->links() }}
                </div>
            @endif
        </div>

        <!-- Kolom Kanan: Bantuan & Informasi Pengembalian -->
        <div style="display: flex; flex-direction: column; gap: 1.2rem;">
            
            <div style="background: white; border: 1px solid #e5e9f0; border-radius: 12px; padding: 1.2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: #16213e; margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.4rem;">
                    🔄 Pengembalian Alat
                </h4>
                <p style="font-size: 0.82rem; color: #64748b; line-height: 1.5; margin: 0;">
                    Bawa fisik alat beserta perlengkapannya ke ruang Sarpras untuk diperiksa oleh petugas sebelum status diubah menjadi <strong>Selesai</strong>.
                </p>
            </div>

            <div style="background: white; border: 1px solid #e5e9f0; border-radius: 12px; padding: 1.2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: #16213e; margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.4rem;">
                    ❓ Pertanyaan Umum
                </h4>
                <div style="display: flex; flex-direction: column; gap: 0.8rem; font-size: 0.8rem;">
                    <div>
                        <strong style="color: #334155; display: block; margin-bottom: 0.2rem;">Bagaimana jika pengajuan ditolak?</strong>
                        <span style="color: #64748b; line-height: 1.4; display: block;">Periksa ketersediaan alat di menu Jelajah atau pastikan alasan penggunaan diisi dengan jelas.</span>
                    </div>
                    <div style="border-top: 1px dashed #e2e8f0; padding-top: 0.6rem;">
                        <strong style="color: #334155; display: block; margin-bottom: 0.2rem;">Bagaimana jika alat rusak/hilang?</strong>
                        <span style="color: #64748b; line-height: 1.4; display: block;">Segera laporkan ke petugas Sarpras. Prosedur ganti rugi akan ditangani via modul Ganti Rugi.</span>
                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- ===== FOOTER UTAMA SIMPALA ===== -->
    <footer style="background: #16213e; color: #9aa7c7; margin-top: 2.5rem; border-radius: 16px; border-top: 1px solid rgba(255,255,255,0.08); padding: 2rem; box-shadow: 0 10px 25px -5px rgba(22, 33, 62, 0.2);">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.8rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
            
            <!-- Kolom 1: Identity & School -->
            <div>
                <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.8rem;">
                    <span style="background: #ffffff; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem;">📦</span>
                    <span style="font-weight: 800; color: #ffffff; font-size: 1.1rem; letter-spacing: 0.03em;">SIMPALA</span>
                </div>
                <p style="font-size: 0.8rem; color: #b7c0d8; line-height: 1.5; margin: 0;">
                    Sistem Pengelolaan Sarana dan Prasarana terintegrasi SMK Negeri 5 Surakarta. Layanan peminjaman alat praktikum sekolah yang cepat, tertib, dan transparan.
                </p>
            </div>

            <!-- Kolom 2: Jam Operasional -->
            <div>
                <h4 style="color: #ffffff; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    🕒 Pelayanan Sarpras
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.8rem; color: #b7c0d8; line-height: 1.8;">
                    <li>Senin – Kamis: <strong style="color: #ffffff;">07.00 – 15.30 WIB</strong></li>
                    <li>Jumat: <strong style="color: #ffffff;">07.00 – 14.00 WIB</strong></li>
                    <li>Lokasi: Ruang Sarpras & Lab RPL</li>
                </ul>
            </div>

            <!-- Kolom 3: Navigasi Cepat -->
            <div>
                <h4 style="color: #ffffff; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    🔗 Tautan Cepat
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.8rem; display: flex; flex-direction: column; gap: 0.4rem;">
                    <li><a href="{{ route('jelajah') }}" style="color: #5fd0e8; text-decoration: none;">🔍 Katalog Alat Praktikum</a></li>
                    <li><a href="{{ route('pinjam.saya') }}" style="color: #5fd0e8; text-decoration: none;">📋 Riwayat Peminjaman Saya</a></li>
                    <li><a href="#" style="color: #5fd0e8; text-decoration: none;">👤 Pengaturan Profil Akun</a></li>
                </ul>
            </div>

        </div>

        <!-- Bottom Footer (Copyright) -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.8rem; padding-top: 1.2rem; font-size: 0.75rem; color: #7c88a8;">
            <div>
                © 2026 <strong>SMK Negeri 5 Surakarta</strong> • Rekayasa Perangkat Lunak (RPL).
            </div>
            <div>
                Dikembangkan dengan ❤️ untuk SIMPALA.
            </div>
        </div>
    </footer>
@endsection