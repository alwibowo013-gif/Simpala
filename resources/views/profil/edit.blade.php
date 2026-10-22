{{-- Satu view dipakai untuk Admin DAN Siswa: layout-nya dipilih otomatis sesuai role yang login --}}
@extends($user->isAdmin() ? 'layouts.admin' : 'layouts.siswa')
@section('title', 'Profil Saya')

@section('content')
    <style>
        .pf-wrap { display: flex; gap: 1.3rem; align-items: flex-start; flex-wrap: wrap; margin-top: 1.3rem; }
        .pf-left { flex: 1 1 260px; max-width: 340px; text-align: center; }
        .pf-right { flex: 2 1 420px; display: flex; flex-direction: column; gap: 1.3rem; min-width: 0; }
        .pf-avatar {
            width: 120px; height: 120px; border-radius: 50%; margin: 0 auto 1rem; overflow: hidden;
            background: #1f6f82; color: white; font-size: 2.8rem; font-weight: 700;
            display: flex; align-items: center; justify-content: center; border: 4px solid #eef2fb;
        }
        .pf-avatar img { width: 100%; height: 100%; object-fit: cover; display: block; }
        .pf-name { font-weight: 700; font-size: 1.05rem; color: #16213e; }
        .pf-sub { color: #9aa3b5; font-size: 0.82rem; margin-bottom: 1.1rem; }
        .pf-actions { display: flex; flex-direction: column; gap: 0.5rem; margin-top: 0.8rem; }
        .pf-actions .btn-simpala, .pf-actions .btn-outline { justify-content: center; width: 100%; }
        .pf-actions .btn-simpala:disabled { opacity: 0.45; cursor: not-allowed; }
        .pf-file-name { font-size: 0.78rem; color: #6b7280; min-height: 1.1rem; word-break: break-all; }
        .pf-readonly { background: #f9fafb; color: #6b7280; cursor: not-allowed; }
    </style>

    {{-- Hero Banner Dinamis Berdasarkan Role --}}
    @if ($user->isAdmin())
        <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #0f766e 100%); border-radius: 16px; padding: 1.8rem 2.2rem; color: white; margin-bottom: 1.8rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);">
            <div>
                <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem;">
                    <span style="background: rgba(20, 184, 166, 0.2); color: #2dd4bf; font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 999px; border: 1px solid rgba(45, 212, 191, 0.3); letter-spacing: 0.05em; text-transform: uppercase;">
                        ⚙️ PENGATURAN AKUN
                    </span>
                    <span style="font-size: 0.78rem; color: #94a3b8; font-weight: 500;">
                        • Sarpras SMK Negeri 5 Surakarta
                    </span>
                </div>
                <h1 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.3rem; letter-spacing: -0.02em;">
                    Profil Administrator
                </h1>
                <p style="font-size: 0.88rem; color: #cbd5e1; margin: 0; max-width: 600px;">
                    Kelola foto identitas, informasi akun admin, serta keamanan kata sandi akses sistem pusat.
                </p>
            </div>
        </div>
    @else
        <div style="background: linear-gradient(135deg, #16213e 0%, #1f6f82 100%); border-radius: 16px; padding: 1.8rem 2.2rem; color: white; margin-bottom: 1.8rem; box-shadow: 0 10px 25px -5px rgba(31, 111, 130, 0.25);">
            <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem;">
                <span style="background: rgba(255, 255, 255, 0.15); color: #5fd0e8; font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 999px; letter-spacing: 0.05em; text-transform: uppercase;">
                    👤 AKUN SISWA
                </span>
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.3rem;">Profil Saya</h1>
            <p style="font-size: 0.88rem; color: #b7c0d8; margin: 0; max-width: 600px;">
                Kelola foto profil, informasi akun, dan keamanan password kamu dengan mudah.
            </p>
        </div>
    @endif

    <div class="pf-wrap">
        {{-- ============ KARTU KIRI: FOTO PROFIL ============ --}}
        <div class="card-panel pf-left">
            {{-- Kalau user punya foto -> tampilkan foto, kalau tidak -> inisial nama --}}
            <div class="pf-avatar" id="pfPreview">
                @if ($user->fotoUrl())
                    <img src="{{ $user->fotoUrl() }}" alt="Foto profil">
                @else
                    {{ $user->inisial() }}
                @endif
            </div>

            <div class="pf-name">{{ $user->name }}</div>
            <div class="pf-sub">{{ $user->isAdmin() ? 'Admin Sarpras' : 'Siswa' }}</div>

            {{-- enctype="multipart/form-data" WAJIB ada karena form ini mengirim file --}}
            <form action="{{ route('profil.foto') }}" method="POST" enctype="multipart/form-data" id="formFoto">
                @csrf
                <input type="file" name="foto" id="fotoInput" accept="image/png,image/jpeg,image/webp" style="display:none;">

                <div class="pf-actions">
                    <label for="fotoInput" class="btn-outline" style="cursor:pointer;">📷 Pilih foto baru</label>
                    <div class="pf-file-name" id="pfFileName"></div>
                    <button type="submit" class="btn-simpala" id="btnSimpanFoto" disabled>Simpan foto</button>
                </div>
                @error('foto') <div class="form-error" style="margin-top:0.6rem;">{{ $message }}</div> @enderror
                <div class="form-hint" style="margin-top:0.7rem;">JPG, PNG, atau WEBP. Maksimal 2MB.</div>
            </form>

            {{-- Tombol hapus hanya muncul kalau memang sudah ada foto --}}
            @if ($user->foto_profil)
                <form action="{{ route('profil.foto.hapus') }}" method="POST" id="formHapusFoto" style="margin-top:0.8rem;">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-outline" style="width:100%; justify-content:center; border-color:#f5c2c0; color:#b42318;"
                            onclick="showConfirm(document.getElementById('formHapusFoto'), 'Hapus Foto Profil?', 'Foto profilmu akan dihapus dan diganti dengan inisial nama.', 'Ya, Hapus', true)">
                        🗑️ Hapus foto
                    </button>
                </form>
            @endif
        </div>

        {{-- ============ KOLOM KANAN ============ --}}
        <div class="pf-right">
            {{-- ----- Informasi akun ----- --}}
            <div class="card-panel">
                <div class="card-panel-title">Informasi Akun</div>
                <form action="{{ route('profil.info') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Nama Lengkap</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}">
                        @error('name') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label>NIS / NIP</label>
                        <input type="text" class="form-control pf-readonly" value="{{ $user->nis_nip ?? '-' }}" readonly>
                    </div>

                    <div class="form-group">
                        <label>Email</label>
                        <input type="text" class="form-control pf-readonly" value="{{ $user->email }}" readonly>
                        <div class="form-hint">NIS/NIP, email, dan peran hanya bisa diubah oleh Admin lewat menu Pengguna.</div>
                    </div>

                    <button type="submit" class="btn-simpala">Simpan perubahan</button>
                </form>
            </div>

            {{-- ----- Ganti password ----- --}}
            <div class="card-panel">
                <div class="card-panel-title">Ganti Password</div>
                <form action="{{ route('profil.password') }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label>Password Lama</label>
                        <input type="password" name="current_password" class="form-control" autocomplete="current-password">
                        @error('current_password') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label>Password Baru</label>
                        <input type="password" name="password" class="form-control" autocomplete="new-password">
                        @error('password') <div class="form-error">{{ $message }}</div> @enderror
                    </div>

                    <div class="form-group">
                        <label>Ulangi Password Baru</label>
                        {{-- name harus persis "password_confirmation" supaya aturan "confirmed" di controller bisa mencocokkan --}}
                        <input type="password" name="password_confirmation" class="form-control" autocomplete="new-password">
                    </div>

                    <button type="submit" class="btn-simpala">Ganti password</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Preview foto SEBELUM di-upload
        (function () {
            const input = document.getElementById('fotoInput');
            const preview = document.getElementById('pfPreview');
            const fileName = document.getElementById('pfFileName');
            const saveBtn = document.getElementById('btnSimpanFoto');
            const MAX = 2 * 1024 * 1024; // 2MB

            input.addEventListener('change', function (e) {
                const file = e.target.files[0];
                if (!file) return;

                if (file.size > MAX) {
                    fileName.textContent = 'Ukuran file melebihi 2MB, pilih foto lain.';
                    fileName.style.color = '#b42318';
                    input.value = '';
                    saveBtn.disabled = true;
                    return;
                }

                fileName.style.color = '#6b7280';
                fileName.textContent = file.name;
                saveBtn.disabled = false;

                const reader = new FileReader();
                reader.onload = function (ev) {
                    preview.innerHTML = '<img src="' + ev.target.result + '" alt="Preview foto">';
                };
                reader.readAsDataURL(file);
            });
        })();
    </script>

    <!-- ===== FOOTER UTAMA SIMPALA ===== -->
    <footer style="background: #16213e; color: #9aa7c7; margin-top: 2.5rem; border-radius: 16px; border-top: 1px solid rgba(255,255,255,0.08); padding: 2rem; box-shadow: 0 10px 25px -5px rgba(22, 33, 62, 0.2);">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.8rem; padding-bottom: 1.5rem; border-bottom: 1px solid rgba(255,255,255,0.08);">
            
            <!-- Kolom 1: Identity & School -->
            <div>
                <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.8rem;">
                    <span style="background: #ffffff; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem;">{{ $user->isAdmin() ? '⚙️' : '📦' }}</span>
                    <span style="font-weight: 800; color: #ffffff; font-size: 1.1rem; letter-spacing: 0.03em;">SIMPALA {{ $user->isAdmin() ? 'ADMIN' : '' }}</span>
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

            <!-- Kolom 3: Tautan Cepat -->
            <div>
                <h4 style="color: #ffffff; font-size: 0.85rem; font-weight: 700; margin-bottom: 0.8rem; text-transform: uppercase; letter-spacing: 0.05em;">
                    🔗 Tautan Cepat
                </h4>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.8rem; display: flex; flex-direction: column; gap: 0.4rem;">
                    @if($user->isAdmin())
                        <li><a href="#" style="color: #5fd0e8; text-decoration: none;">📦 Kelola Master Data Alat</a></li>
                        <li><a href="#" style="color: #5fd0e8; text-decoration: none;">📑 Verifikasi Peminjaman</a></li>
                        <li><a href="#" style="color: #5fd0e8; text-decoration: none;">📊 Laporan & Transaksi</a></li>
                    @else
                        <li><a href="{{ route('jelajah') }}" style="color: #5fd0e8; text-decoration: none;">🔍 Katalog Alat Praktikum</a></li>
                        <li><a href="{{ route('pinjam.saya') }}" style="color: #5fd0e8; text-decoration: none;">📋 Riwayat Peminjaman Saya</a></li>
                        <li><a href="#" style="color: #5fd0e8; text-decoration: none;">👤 Pengaturan Profil Akun</a></li>
                    @endif
                </ul>
            </div>

        </div>

        <!-- Bottom Footer (Copyright) -->
        <div style="display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 0.8rem; padding-top: 1.2rem; font-size: 0.75rem; color: #7c88a8;">
            <div>
                © 2026 <strong>SMK Negeri 5 Surakarta</strong> • Rekayasa Perangkat Lunak (RPL).
            </div>
            <div>
                {{ $user->isAdmin() ? 'Administrator Panel SIMPALA v1.0' : 'Dikembangkan dengan ❤️ untuk SIMPALA.' }}
            </div>
        </div>
    </footer>
@endsection