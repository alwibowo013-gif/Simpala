@extends('layouts.siswa')
@section('title', 'Ajukan Peminjaman')

@section('content')
    <div class="page-title" style="margin-bottom:1.5rem;">
        <h1>Ajukan Peminjaman</h1>
        <p>Lengkapi informasi berikut. Pengajuan akan diperiksa petugas.</p>
    </div>

    @if ($errors->any())
        <div class="alert-error">{{ $errors->first() }}</div>
    @endif

    <div style="display:flex; gap:1.5rem; align-items:flex-start; flex-wrap:wrap;">
        {{-- Form utama --}}
        <div style="flex:2 1 400px; background:white; border:1px solid #e5e9f0; border-radius:12px; padding:1.6rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
            <div style="font-weight:700; color:#16213e; margin-bottom:1.2rem;">Detail peminjaman</div>

            <form action="{{ route('pinjam.store') }}" method="POST">
                @csrf

                @if ($alatTerpilih)
                    {{-- Kalau datang dari halaman Jelajah Alat, alat sudah fix, tinggal dikonfirmasi --}}
                    <input type="hidden" name="alat_id" value="{{ $alatTerpilih->id }}">
                    <div class="form-group" style="margin-bottom:1.1rem;">
                        <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.4rem;">Alat dipilih</label>
                        <div style="padding:0.6rem 0.85rem; border:1px solid #d7dce3; border-radius:8px; background:#f9fafb; font-size:0.9rem;">
                            {{ $alatTerpilih->nama_alat }} — {{ $alatTerpilih->stok_tersedia }} tersedia
                        </div>
                    </div>
                @else
                    <div class="form-group" style="margin-bottom:1.1rem;">
                        <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.4rem;">Pilih alat</label>
                        <select name="alat_id" style="width:100%; padding:0.6rem 0.85rem; border:1px solid #d7dce3; border-radius:8px;">
                            <option value="">-- Pilih alat --</option>
                            @foreach ($alatList as $a)
                                <option value="{{ $a->id }}" {{ old('alat_id') == $a->id ? 'selected' : '' }}>
                                    {{ $a->nama_alat }} — {{ $a->stok_tersedia }} tersedia
                                </option>
                            @endforeach
                        </select>
                    </div>
                @endif

                <div class="form-group" style="margin-bottom:1.1rem;">
                    <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.4rem;">Jumlah</label>
                    <input type="number" name="jumlah" min="1" value="{{ old('jumlah', 1) }}"
                           style="width:100%; padding:0.6rem 0.85rem; border:1px solid #d7dce3; border-radius:8px;">
                </div>

                <div style="display:flex; gap:1rem;">
                    <div class="form-group" style="margin-bottom:1.1rem; flex:1;">
                        <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.4rem;">Tanggal pinjam</label>
                        <input type="date" name="tanggal_pinjam" value="{{ old('tanggal_pinjam', date('Y-m-d')) }}"
                               style="width:100%; padding:0.6rem 0.85rem; border:1px solid #d7dce3; border-radius:8px;">
                    </div>
                    <div class="form-group" style="margin-bottom:1.1rem; flex:1;">
                        <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.4rem;">Rencana tanggal kembali</label>
                        <input type="date" name="tanggal_kembali_rencana" value="{{ old('tanggal_kembali_rencana') }}"
                               style="width:100%; padding:0.6rem 0.85rem; border:1px solid #d7dce3; border-radius:8px;">
                    </div>
                </div>

                <div class="form-group" style="margin-bottom:1.1rem;">
                    <label style="display:block; font-size:0.85rem; font-weight:600; margin-bottom:0.4rem;">Keperluan peminjaman</label>
                    <textarea name="keperluan" rows="2" placeholder="Contoh: Dokumentasi kegiatan OSIS"
                              style="width:100%; padding:0.6rem 0.85rem; border:1px solid #d7dce3; border-radius:8px; font-family:inherit;">{{ old('keperluan') }}</textarea>
                </div>

                <label style="display:flex; align-items:flex-start; gap:0.5rem; font-size:0.83rem; color:#4b5563; margin-bottom:1.3rem;">
                    <input type="checkbox" name="setuju" value="1" style="margin-top:0.2rem;">
                    Saya bertanggung jawab menjaga alat dan mengembalikannya tepat waktu dalam kondisi baik.
                </label>

                <button type="submit" class="btn-simpala">📨 Kirim pengajuan</button>
                <a href="{{ route('jelajah') }}" class="btn-outline">Batal</a>
            </form>
        </div>

        {{-- Preview alat, cuma muncul kalau datang dari Jelajah Alat --}}
        @if ($alatTerpilih)
            <div style="flex:1 1 260px; background:white; border:1px solid #e5e9f0; border-radius:12px; overflow:hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                
                {{-- Area Tampilan Gambar Alat --}}
                <div style="height:150px; background:#eef2fb; display:flex; align-items:center; justify-content:center; overflow:hidden;">
                    @if ($alatTerpilih->gambar)
                        @php
                            $pathGambar = \Illuminate\Support\Str::after($alatTerpilih->gambar, 'public/');
                        @endphp
                        <img src="{{ asset('storage/' . $pathGambar) }}" alt="{{ $alatTerpilih->nama_alat }}" style="width:100%; height:100%; object-fit:contain; padding:0.8rem;">
                    @else
                        <span style="font-size:2.5rem;">📦</span>
                    @endif
                </div>

                <div style="padding:1.2rem;">
                    <span style="font-size:0.7rem; background:#dceef0; color:#1f6f82; padding:0.2rem 0.6rem; border-radius:999px; font-weight:600;">
                        {{ $alatTerpilih->kategori->nama_kategori ?? '-' }}
                    </span>
                    <div style="font-weight:700; margin:0.6rem 0 0.2rem 0; color:#16213e;">{{ $alatTerpilih->nama_alat }}</div>
                    <div style="font-size:0.8rem; color:#6b7280; margin-bottom:0.5rem;">
                        {{ $alatTerpilih->kode_alat }} • Kondisi {{ ucfirst($alatTerpilih->kondisi) }}
                    </div>
                    <div style="font-size:0.8rem; color:#1f9254; font-weight:600;">● {{ $alatTerpilih->stok_tersedia }} unit tersedia</div>
                </div>
            </div>
        @endif
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
                    Sistem Pengelolaan Sarana dan Prasarana terintegrasi SMK Negeri 5 Surakarta. Layanan peminjaman alat praktikum sekolah yang cepat, tertib, dan transparan[cite: 1].
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