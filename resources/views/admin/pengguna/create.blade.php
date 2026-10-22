@extends('layouts.admin')
@section('title', 'Tambah Pengguna')

@section('content')
<div style="display: flex; flex-direction: column; min-height: 85vh; justify-content: space-between;">
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        
        <!-- Executive Hero Banner -->
        <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #0f766e 100%); padding: 1.8rem 2.2rem; border-radius: 16px; color: white; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);">
            <div>
                <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem;">
                    <span style="background: rgba(20, 184, 166, 0.2); color: #2dd4bf; font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 999px; border: 1px solid rgba(45, 212, 191, 0.3); text-transform: uppercase;">
                        👤 OTORISASI AKUN
                    </span>
                    <span style="font-size: 0.78rem; color: #94a3b8; font-weight: 500;">• Sarpras SMK Negeri 5 Surakarta</span>
                </div>
                <h1 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.3rem; letter-spacing: -0.02em;">Tambah Pengguna Baru</h1>
                <p style="font-size: 0.88rem; color: #cbd5e1; margin: 0;">Buat akun untuk Admin atau Siswa baru.</p>
            </div>
            <div>
                <a href="{{ route('admin.pengguna.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.2rem; border-radius: 10px; font-size: 0.85rem; font-weight: 600; background: rgba(15, 23, 42, 0.6); color: #cbd5e1; border: 1px solid rgba(255,255,255,0.1); text-decoration: none;">
                    &larr; Kembali
                </a>
            </div>
        </div>

        <!-- Layout Grid 2 Kolom -->
        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; align-items: start;">
            
            <!-- Panel Form Utama (Kiri) -->
            <div class="card-panel" style="max-width: 100%;">
                <form action="{{ route('admin.pengguna.store') }}" method="POST">
                    @csrf

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label>Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ old('name') }}">
                            @error('name') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label>NIS / NIP</label>
                            <input type="text" name="nis_nip" class="form-control" value="{{ old('nis_nip') }}">
                            @error('nis_nip') <div class="form-error">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label>Email</label>
                            <input type="email" name="email" class="form-control" value="{{ old('email') }}">
                            @error('email') <div class="form-error">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label>Role</label>
                            <select name="role" class="form-control">
                                <option value="siswa" {{ old('role') == 'siswa' ? 'selected' : '' }}>Siswa</option>
                                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label>Password</label>
                        <input type="password" name="password" class="form-control">
                        @error('password') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div style="margin-top: 1.5rem; display: flex; gap: 0.8rem;">
                        <button type="submit" class="btn-simpala">Simpan</button>
                        <a href="{{ route('admin.pengguna.index') }}" class="btn-outline">Batal</a>
                    </div>
                </form>
            </div>

            <!-- Panel Informasi & Bantuan (Kanan) -->
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="background: #ffffff; padding: 1.4rem; border-radius: 12px; border: 1px solid #e5e9f0;">
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #16213e; margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.4rem;">
                        💡 Ketentuan Role Akses
                    </h4>
                    <ul style="font-size: 0.83rem; color: #6b7280; line-height: 1.6; padding-left: 1.1rem; margin: 0;">
                        <li style="margin-bottom: 0.4rem;"><strong>Siswa:</strong> Memiliki akses untuk melihat katalog alat dan mengajukan peminjaman.</li>
                        <li><strong>Admin Sarpras:</strong> Memiliki hak akses penuh untuk mengelola inventaris, verifikasi peminjaman, dan manajemen akun.</li>
                    </ul>
                </div>

                <div style="background: #f4f6fb; padding: 1.2rem; border-radius: 12px; border: 1px solid #e2e8f0; font-size: 0.82rem; color: #4b5563; line-height: 1.5;">
                    🔒 <strong>Catatan Keamanan:</strong> Pastikan NIS/NIP yang dimasukkan sudah sesuai dengan data resmi sekolah untuk memudahkan identifikasi akun.
                </div>
            </div>

        </div>
    </div>

    <!-- Footer Card Gelap 3 Kolom -->
    <footer style="background: #0f172a; color: #9aa7c7; margin-top: 3rem; border-radius: 16px; border: 1px solid #1e293b; padding: 2rem; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.3);">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.8rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
            <div>
                <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.8rem; color: #fff; font-weight: 800;">⚙️ SIMPALA ADMIN</div>
                <p style="font-size: 0.8rem; color: #b7c0d8; margin: 0;">Portal Manajemen Sarana & Prasarana SMK Negeri 5 Surakarta. Layanan pengawasan inventaris, verifikasi peminjaman, dan cetak laporan berkala.</p>
            </div>
            <div>
                <h4 style="color: #fff; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.8rem;">🕒 JAM OPERASIONAL</h4>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.8rem; color: #b7c0d8; line-height: 1.8;">
                    <li>Senin – Kamis: <strong style="color:#fff;">07.00 – 15.30 WIB</strong></li>
                    <li>Jumat: <strong style="color:#fff;">07.00 – 14.00 WIB</strong></li>
                    <li>Status Server: <span style="color: #4cd964; font-weight: 700;">● Online</span></li>
                </ul>
            </div>
            <div>
                <h4 style="color: #fff; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.8rem;">🛠️ PINTASAN ADMIN</h4>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.8rem; display: flex; flex-direction: column; gap: 0.4rem;">
                    <li><a href="{{ route('admin.alat.index') }}" style="color: #5fd0e8; text-decoration: none;">📦 Kelola Master Data Alat</a></li>
                    <li><a href="#" style="color: #5fd0e8; text-decoration: none;">📑 Verifikasi Peminjaman</a></li>
                    <li><a href="#" style="color: #5fd0e8; text-decoration: none;">📊 Laporan & Transaksi</a></li>
                </ul>
            </div>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; padding-top: 1.2rem; font-size: 0.75rem; color: #7c88a8;">
            <div>&copy; 2026 <strong>SMK Negeri 5 Surakarta</strong> • Rekayasa Perangkat Lunak (RPL).</div>
            <div>Administrator Panel SIMPALA v1.0</div>
        </div>
    </footer>
</div>
@endsection