@extends('layouts.admin')
@section('title', 'Pengguna')

@section('content')
    <!-- Executive Hero Banner Control Center (Data Pengguna) -->
    <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #0f766e 100%); border-radius: 16px; padding: 1.8rem 2.2rem; color: white; margin-bottom: 1.8rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);">
        <div>
            <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem;">
                <span style="background: rgba(20, 184, 166, 0.2); color: #2dd4bf; font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 999px; border: 1px solid rgba(45, 212, 191, 0.3); letter-spacing: 0.05em; text-transform: uppercase;">
                    👥 OTORISASI AKUN
                </span>
                <span style="font-size: 0.78rem; color: #94a3b8; font-weight: 500;">
                    • Sarpras SMK Negeri 5 Surakarta
                </span>
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.3rem; letter-spacing: -0.02em;">
                Data Pengguna & Akses Hak
            </h1>
            <p style="font-size: 0.88rem; color: #cbd5e1; margin: 0; max-width: 600px;">
                Kelola kredensial akun Admin dan Siswa yang terdaftar untuk mengakses sistem SIMPALA.
            </p>
        </div>

        <div>
            <a href="{{ route('admin.pengguna.create') }}" class="btn-simpala" style="background: #14b8a6; color: #0f172a; font-weight: 700; padding: 0.75rem 1.3rem; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; border: none; font-size: 0.88rem;">
                + Tambah Pengguna Baru
            </a>
        </div>
    </div>

    <!-- Tabel Data Pengguna -->
    <div class="card-panel">
        <table class="simple-table">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>NIS/NIP</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th style="width:120px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pengguna as $item)
                    <tr>
                        <td><strong>{{ $item->name }}</strong> @if ($item->id === auth()->id()) <span class="form-hint">(kamu)</span> @endif</td>
                        <td>{{ $item->nis_nip ?? '-' }}</td>
                        <td>{{ $item->email }}</td>
                        <td>
                            @if ($item->role === 'admin')
                                <span class="badge-status badge-baik">Admin</span>
                            @else
                                <span class="badge-status" style="background:#e6edf7; color:#3856a8;">Siswa</span>
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('admin.pengguna.edit', $item->id) }}" class="icon-btn" title="Edit">✏️</a>
                            @if ($item->id !== auth()->id())
                                <form action="{{ route('admin.pengguna.destroy', $item->id) }}" method="POST" style="display:inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="icon-btn danger" title="Hapus"
                                            onclick="showConfirm(this.closest('form'), 'Hapus Pengguna?', 'Akun \'{{ $item->name }}\' akan dihapus permanen dan tidak bisa login lagi.', 'Hapus', true)">🗑️</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; color:#9aa3b5; padding:2rem;">Belum ada data pengguna.</td>
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