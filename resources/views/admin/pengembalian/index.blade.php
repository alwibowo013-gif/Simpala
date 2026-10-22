@extends('layouts.admin')
@section('title', 'Pengembalian')

@section('content')
    <!-- Executive Hero Banner Control Center (Pengembalian Alat) -->
    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #0f766e 100%); border-radius: 16px; padding: 1.8rem 2.2rem; color: white; margin-bottom: 1.8rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);">
        <div>
            <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem;">
                <span style="background: rgba(20, 184, 166, 0.2); color: #2dd4bf; font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 999px; border: 1px solid rgba(45, 212, 191, 0.3); letter-spacing: 0.05em; text-transform: uppercase;">
                    🔄 SIRKULASI INVENTARIS
                </span>
                <span style="font-size: 0.78rem; color: #94a3b8; font-weight: 500;">
                    • Sarpras SMK Negeri 5 Surakarta
                </span>
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.3rem; letter-spacing: -0.02em;">
                Pengembalian Alat
            </h1>
            <p style="font-size: 0.88rem; color: #cbd5e1; margin: 0; max-width: 600px;">
                Catat penerimaan fisik alat yang dikembalikan siswa serta periksa kondisi akhir unit secara cermat.
            </p>
        </div>

        <div style="background: rgba(255, 255, 255, 0.08); border: 1px solid rgba(255, 255, 255, 0.15); padding: 0.7rem 1.2rem; border-radius: 12px; display: flex; align-items: center; gap: 0.8rem;">
            <span style="font-size: 1.5rem;">📦</span>
            <div>
                <div style="font-size: 0.72rem; color: #94a3b8; text-transform: uppercase; font-weight: 700;">Sedang Dipinjam</div>
                <div style="font-size: 1.1rem; font-weight: 800; color: #2dd4bf;">{{ $list->count() }} Transaksi</div>
            </div>
        </div>
    </div>

    <!-- Tabel Data Pengembalian -->
    <div class="card-panel">
        <table class="simple-table">
            <thead>
                <tr>
                    <th>Peminjam</th>
                    <th>Alat</th>
                    <th>Tgl Pinjam</th>
                    <th>Rencana Kembali</th>
                    <th style="width:260px;">Kondisi & Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($list as $item)
                    @php $d = $item->detailUtama(); @endphp
                    <tr>
                        <td><strong>{{ $item->user->name ?? '-' }}</strong></td>
                        <td>
                            <strong>{{ $d?->alat?->nama_alat ?? '-' }}</strong>
                            <span style="color:#9aa3b5; font-size: 0.82rem;">({{ $d?->jumlah }} unit)</span>
                        </td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_pinjam)->format('d M Y') }}</td>
                        <td>{{ \Carbon\Carbon::parse($item->tanggal_kembali_rencana)->format('d M Y') }}</td>
                        <td>
                            {{-- Form inline langsung dalam 1 sel tabel --}}
                            <form action="{{ route('admin.pengembalian.proses', $item->id) }}" method="POST" style="display:flex; gap:0.4rem; align-items:center;">
                                @csrf
                                <select name="kondisi_kembali" class="form-control" style="padding:0.4rem 0.6rem; font-size:0.82rem;" required>
                                    <option value="baik">Baik</option>
                                    <option value="rusak">Rusak</option>
                                    <option value="hilang">Hilang</option>
                                </select>
                                <button type="button" class="btn-simpala" style="background:#14b8a6; color:#0f172a; font-weight:700; padding:0.5rem 0.8rem; font-size:0.8rem; white-space:nowrap; border:none;"
                                        onclick="showConfirm(this.closest('form'), 'Catat Pengembalian?', 'Alat \'{{ $d?->alat?->nama_alat }}\' akan dicatat sebagai dikembalikan oleh {{ $item->user->name ?? '-' }}.', 'Proses', false)">Proses</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; color:#9aa3b5; padding:2rem;">Tidak ada alat yang sedang dipinjam.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
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