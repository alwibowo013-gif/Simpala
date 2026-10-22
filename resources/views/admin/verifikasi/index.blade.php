@extends('layouts.admin')
@section('title', 'Verifikasi Peminjaman')

@section('content')
    <!-- Executive Hero Banner Control Center (Verifikasi Peminjaman) -->
    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #0f766e 100%); border-radius: 16px; padding: 1.8rem 2.2rem; color: white; margin-bottom: 1.8rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);">
        <div>
            <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem;">
                <span style="background: rgba(20, 184, 166, 0.2); color: #2dd4bf; font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 999px; border: 1px solid rgba(45, 212, 191, 0.3); letter-spacing: 0.05em; text-transform: uppercase;">
                    ⚡ VERIFIKASI TRANSAKSI
                </span>
                <span style="font-size: 0.78rem; color: #94a3b8; font-weight: 500;">
                    • Sarpras SMK Negeri 5 Surakarta
                </span>
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.3rem; letter-spacing: -0.02em;">
                Verifikasi Peminjaman
            </h1>
            <p style="font-size: 0.88rem; color: #cbd5e1; margin: 0; max-width: 600px;">
                Tinjau alasan penggunaan dan keaslian pengajuan siswa sebelum memberikan persetujuan atau penolakan.
            </p>
        </div>

        <!-- Counter Badges di Banner -->
        <div style="display: flex; gap: 0.6rem; flex-wrap: wrap;">
            <div style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.15); padding: 0.5rem 0.9rem; border-radius: 10px; text-align: center;">
                <div style="font-size: 0.7rem; color: #fbbf24; font-weight: 700; text-transform: uppercase;">Menunggu</div>
                <div style="font-size: 1.1rem; font-weight: 800; color: white;">{{ $counts['menunggu'] }}</div>
            </div>
            <div style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.15); padding: 0.5rem 0.9rem; border-radius: 10px; text-align: center;">
                <div style="font-size: 0.7rem; color: #34d399; font-weight: 700; text-transform: uppercase;">Disetujui</div>
                <div style="font-size: 1.1rem; font-weight: 800; color: white;">{{ $counts['disetujui'] }}</div>
            </div>
            <div style="background: rgba(255, 255, 255, 0.1); border: 1px solid rgba(255, 255, 255, 0.15); padding: 0.5rem 0.9rem; border-radius: 10px; text-align: center;">
                <div style="font-size: 0.7rem; color: #f87171; font-weight: 700; text-transform: uppercase;">Ditolak</div>
                <div style="font-size: 1.1rem; font-weight: 800; color: white;">{{ $counts['ditolak'] }}</div>
            </div>
        </div>
    </div>

    <!-- Container Grid 2 Kolom (Daftar Pengajuan vs Detail Pengajuan) -->
    <div style="display:flex; gap:1.3rem; align-items:flex-start; flex-wrap:wrap;">
        {{-- Daftar pengajuan di kiri --}}
        <div style="flex:1 1 300px; display:flex; flex-direction:column; gap:0.7rem;">
            @forelse ($list as $item)
                @php
                    $map = ['pending' => ['Menunggu', 'badge-rusak'], 'approved' => ['Disetujui', 'badge-baik'], 'rejected' => ['Ditolak', null]];
                    [$label, $class] = $map[$item->status] ?? [$item->status, null];
                    $isSelected = $selected && $selected->id === $item->id;
                @endphp
                <a href="{{ route('admin.verifikasi.index', ['id' => $item->id]) }}"
                   style="text-decoration:none; color:inherit; display:block; background: {{ $isSelected ? '#eaf3f5' : 'white' }};
                          border: 1px solid {{ $isSelected ? '#1f6f82' : '#e5e9f0' }}; border-radius:10px; padding:0.9rem 1rem;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:0.3rem;">
                        <strong style="font-size:0.9rem; color:#16213e;">{{ $item->user->name ?? '-' }}</strong>
                        @if ($class)
                            <span class="badge-status {{ $class }}">{{ $label }}</span>
                        @else
                            <span class="badge-status" style="background:#fdecea; color:#b42318;">{{ $label }}</span>
                        @endif
                    </div>
                    <div style="font-size:0.85rem; color:#4b5563;">{{ $item->detailUtama()?->alat?->nama_alat ?? '-' }}</div>
                    <div style="font-size:0.75rem; color:#9aa3b5; margin-top:0.2rem;">{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</div>
                </a>
            @empty
                <div class="card-panel" style="color:#9aa3b5;">Belum ada pengajuan peminjaman.</div>
            @endforelse
        </div>

        {{-- Detail pengajuan yang dipilih di kanan --}}
        <div style="flex:2 1 400px;">
            @if ($selected)
                @php $d = $selected->detailUtama(); @endphp
                <div class="card-panel">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:1.2rem;">
                        <div class="card-panel-title" style="margin-bottom:0;">Detail Pengajuan {{ $selected->kodeTransaksi() }}</div>
                    </div>

                    <div style="display:flex; align-items:center; gap:0.8rem; margin-bottom:1.3rem; padding-bottom:1.1rem; border-bottom:1px solid #f3f5f9;">
                        <div style="width:42px; height:42px; border-radius:50%; background:#dceef0; display:flex; align-items:center; justify-content:center; font-weight:700; color:#1f6f82;">
                            {{ strtoupper(substr($selected->user->name ?? '?', 0, 1)) }}
                        </div>
                        <div>
                            <div style="font-weight:700; color:#16213e;">{{ $selected->user->name ?? '-' }}</div>
                            <div style="font-size:0.8rem; color:#9aa3b5;">{{ $selected->user->nis_nip ?? '-' }} • Siswa</div>
                        </div>
                    </div>

                    <div style="display:flex; gap:1.5rem; flex-wrap:wrap; margin-bottom:1.2rem;">
                        <div style="flex:1 1 200px;">
                            <div class="form-hint" style="margin-bottom:0.2rem;">Alat</div>
                            <div style="font-weight:600;">{{ $d?->alat?->nama_alat ?? '-' }}</div>
                        </div>
                        <div style="flex:1 1 100px;">
                            <div class="form-hint" style="margin-bottom:0.2rem;">Jumlah</div>
                            <div style="font-weight:600;">{{ $d?->jumlah ?? '-' }} unit</div>
                        </div>
                        <div style="flex:1 1 150px;">
                            <div class="form-hint" style="margin-bottom:0.2rem;">Tanggal pinjam</div>
                            <div style="font-weight:600;">{{ \Carbon\Carbon::parse($selected->tanggal_pinjam)->format('d M Y') }}</div>
                        </div>
                        <div style="flex:1 1 150px;">
                            <div class="form-hint" style="margin-bottom:0.2rem;">Rencana kembali</div>
                            <div style="font-weight:600;">{{ \Carbon\Carbon::parse($selected->tanggal_kembali_rencana)->format('d M Y') }}</div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label>Keperluan</label>
                        <div class="form-control" style="background:#f9fafb;">{{ $selected->keperluan }}</div>
                    </div>

                    @if ($selected->status === 'pending')
                        <form id="verifikasiForm-{{ $selected->id }}" action="{{ route('admin.verifikasi.approve', $selected->id) }}" method="POST">
                            @csrf

                            <div class="form-group">
                                <label>Catatan petugas (opsional)</label>
                                <input type="text" class="form-control" name="catatan_admin" placeholder="Tambahkan catatan (opsional)">
                            </div>

                            <button type="button" class="btn-outline" style="border-color:#f5c2c0; color:#b42318;"
                                    onclick="showConfirm(document.getElementById('verifikasiForm-{{ $selected->id }}'), 'Tolak Pengajuan?', 'Pengajuan dari {{ $selected->user->name ?? '-' }} akan ditolak. Stok alat akan dikembalikan otomatis.', 'Ya, Tolak', true, '{{ route('admin.verifikasi.reject', $selected->id) }}')">✕ Tolak pengajuan</button>
                            <button type="button" class="btn-simpala"
                                    onclick="showConfirm(document.getElementById('verifikasiForm-{{ $selected->id }}'), 'Setujui Peminjaman?', 'Pengajuan dari {{ $selected->user->name ?? '-' }} akan disetujui dan stok alat akan dikurangi.', 'Ya, Setujui', false)">✓ Setujui peminjaman</button>
                        </form>
                    @else
                        <div class="form-hint">Pengajuan ini sudah diproses, tidak bisa diubah lagi di sini.</div>
                        @if ($selected->catatan_admin)
                            <div class="form-group" style="margin-top:0.8rem;">
                                <label>Catatan petugas</label>
                                <div class="form-control" style="background:#f9fafb;">{{ $selected->catatan_admin }}</div>
                            </div>
                        @endif
                    @endif
                </div>
            @else
                <div class="card-panel" style="color:#9aa3b5;">Pilih salah satu pengajuan di sebelah kiri untuk melihat detailnya.</div>
            @endif
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
@endsection