@extends('layouts.siswa')
@section('title', 'Jelajah Alat')

@section('content')
    <!-- Banner Utama -->
    <div style="background: linear-gradient(135deg, #16213e 0%, #1f6f82 100%); border-radius:16px; padding:1.8rem 2rem; margin-bottom:1.5rem; color:white; display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:1rem; box-shadow: 0 10px 25px -5px rgba(31, 111, 130, 0.25);">
        <div>
            <div style="font-size:0.72rem; color:#7fd4e8; font-weight:700; letter-spacing:0.05em; margin-bottom:0.4rem; text-transform: uppercase;">INVENTARIS SEKOLAH</div>
            <div style="font-size:1.4rem; font-weight:700; margin-bottom:0.3rem;">Temukan alat yang kamu butuhkan</div>
            <div style="font-size:0.85rem; color:#b7c0d8;">Cek ketersediaan dan ajukan peminjaman secara daring.</div>
        </div>
        <form method="GET" style="display:flex;">
            @if(request('kategori_id')) <input type="hidden" name="kategori_id" value="{{ request('kategori_id') }}"> @endif
            @if(request('jurusan')) <input type="hidden" name="jurusan" value="{{ request('jurusan') }}"> @endif
            <input type="text" name="cari" value="{{ request('cari') }}" placeholder="Cari kamera, alat olahraga, perkakas..."
                   style="padding:0.65rem 1rem; border-radius:8px; border:none; width: 280px; font-size: 0.85rem; outline: none;">
        </form>
    </div>

    <!-- Filter Kategori Utama -->
    <div style="display:flex; gap:0.6rem; margin-bottom:1rem; flex-wrap:wrap;">
        <a href="{{ route('jelajah', array_filter(['cari' => request('cari')])) }}"
           style="text-decoration:none; padding:0.4rem 1rem; border-radius:999px; font-size:0.82rem; font-weight:600;
                  background: {{ !request('kategori_id') ? '#16213e' : '#ffffff' }}; color: {{ !request('kategori_id') ? 'white' : '#374151' }}; border: 1px solid {{ !request('kategori_id') ? '#16213e' : '#e2e8f0' }};">
            Semua alat
        </a>
        @foreach ($kategoriList as $kat)
            <a href="{{ route('jelajah', array_filter(['cari' => request('cari'), 'kategori_id' => $kat->id])) }}"
               style="text-decoration:none; padding:0.4rem 1rem; border-radius:999px; font-size:0.82rem; font-weight:600;
                      background: {{ request('kategori_id') == $kat->id ? '#16213e' : '#ffffff' }}; color: {{ request('kategori_id') == $kat->id ? 'white' : '#374151' }}; border: 1px solid {{ request('kategori_id') == $kat->id ? '#16213e' : '#e2e8f0' }};">
                {{ $kat->nama_kategori }}
            </a>
        @endforeach
    </div>

    <!-- Sub-Filter Jurusan (Jika Kategori Praktikum Dipilih) -->
    @php
        $kategoriTerpilih = $kategoriList->firstWhere('id', request('kategori_id'));
        $isPraktikum = $kategoriTerpilih && str_contains(strtolower($kategoriTerpilih->nama_kategori), 'praktikum');
        
        $daftarJurusan = [
            'Rekayasa Perangkat Lunak (RPL)',
            'Teknik Listrik',
            'Teknik Mesin',
            'Teknik Elektronika',
            'Desain Permodelan Informasi Bangunan (DPIB)',
            'Teknik Otomotif',
            'Teknik Otomasi'
        ];
    @endphp

    @if ($isPraktikum)
        <div style="background:#ffffff; border:1px solid #e2e8f0; border-radius:14px; padding:0.9rem 1.2rem; margin-bottom:1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
            <div style="font-size:0.75rem; font-weight:700; color:#1f6f82; margin-bottom:0.6rem; text-transform:uppercase; letter-spacing:0.04em; display:flex; align-items:center; gap:0.4rem;">
                🛠️ PILIH JURUSAN PRAKTIKUM:
            </div>
            <div style="display:flex; gap:0.5rem; flex-wrap:wrap;">
                <a href="{{ route('jelajah', array_filter(['cari' => request('cari'), 'kategori_id' => request('kategori_id')])) }}"
                   style="text-decoration:none; padding:0.3rem 0.8rem; border-radius:8px; font-size:0.78rem; font-weight:600;
                          background: {{ !request('jurusan') ? '#1f6f82' : '#f8fafc' }}; color: {{ !request('jurusan') ? 'white' : '#475569' }}; border:1px solid {{ !request('jurusan') ? '#1f6f82' : '#cbd5e1' }};">
                    Semua Jurusan
                </a>
                @foreach ($daftarJurusan as $jrs)
                    <a href="{{ route('jelajah', array_filter(['cari' => request('cari'), 'kategori_id' => request('kategori_id'), 'jurusan' => $jrs])) }}"
                       style="text-decoration:none; padding:0.3rem 0.8rem; border-radius:8px; font-size:0.78rem; font-weight:600;
                              background: {{ request('jurusan') == $jrs ? '#1f6f82' : '#f8fafc' }}; color: {{ request('jurusan') == $jrs ? 'white' : '#475569' }}; border:1px solid {{ request('jurusan') == $jrs ? '#1f6f82' : '#cbd5e1' }};">
                        {{ $jrs }}
                    </a>
                @endforeach
            </div>
        </div>
    @endif

    <!-- Layout Grid 2 Kolom (Daftar Alat di Kiri, Information Sidebar di Kanan) -->
    <div style="display: grid; grid-template-columns: 1fr 320px; gap: 1.5rem; align-items: start;">
        
        <!-- Panel Kontainer Utama Katalog Alat -->
        <div style="background: white; border: 1px solid #e5e9f0; border-radius: 16px; padding: 1.4rem; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
            
            <!-- Card Header Pembatas Judul "Alat Tersedia" -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem; padding-bottom: 0.8rem; border-bottom: 1px solid #f1f5f9;">
                <div style="display: flex; align-items: center; gap: 0.6rem;">
                    <span style="background: #e0f2fe; color: #1f6f82; width: 34px; height: 34px; border-radius: 10px; display: flex; align-items: center; justify-content: center; font-size: 1.1rem; flex-shrink:0;">📦</span>
                    <div>
                        <h1 style="font-size: 1.1rem; font-weight: 700; color: #16213e; margin: 0; line-height: 1.2;">Alat Tersedia</h1>
                        <p style="font-size: 0.78rem; color: #64748b; margin: 0; margin-top: 0.15rem;">
                            {{ $alat->total() }} alat dapat dipinjam {{ request('jurusan') ? '• Jurusan ' . request('jurusan') : '' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- Grid Kartu Alat -->
            <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 1.1rem;">
                @forelse ($alat as $item)
                    <div style="background:white; border:1px solid #e2e8f0; border-radius:12px; overflow:hidden; display:flex; flex-direction:column; transition: all 0.2s;" onmouseover="this.style.borderColor='#1f6f82'" onmouseout="this.style.borderColor='#e2e8f0'">
                        
                        <div style="height:130px; background:#f8fafc; display:flex; align-items:center; justify-content:center; overflow:hidden; flex-shrink:0; border-bottom: 1px solid #f1f5f9;">
                            @if ($item->gambar)
                                @php
                                    $pathGambar = \Illuminate\Support\Str::after($item->gambar, 'public/');
                                @endphp
                                <img src="{{ asset('storage/' . $pathGambar) }}" alt="{{ $item->nama_alat }}" style="width:100%; height:100%; object-fit:contain; padding:0.7rem;">
                            @else
                                <span style="font-size:2.2rem;">📦</span>
                            @endif
                        </div>

                        <div style="padding:0.9rem; display:flex; flex-direction:column; flex:1;">
                            <div style="display:flex; gap:0.3rem; flex-wrap:wrap; min-height:22px; align-items:flex-start;">
                                <span style="font-size:0.68rem; background:#dceef0; color:#1f6f82; padding:0.15rem 0.5rem; border-radius:999px; font-weight:700;">
                                    {{ $item->kategori->nama_kategori ?? '-' }}
                                </span>
                                @if($item->jurusan)
                                    <span style="font-size:0.68rem; background:#e0f2fe; color:#0369a1; padding:0.15rem 0.5rem; border-radius:999px; font-weight:700;">
                                        {{ $item->jurusan }}
                                    </span>
                                @endif
                            </div>
                            
                            <div style="font-weight:700; font-size:0.88rem; margin:0.4rem 0 0.2rem 0; color:#16213e; line-height:1.3;">{{ $item->nama_alat }}</div>
                            
                            <div style="display:flex; justify-content:space-between; align-items:center; font-size:0.78rem; color:#6b7280; margin-bottom:0.8rem;">
                                <span>{{ $item->kode_alat }}</span>
                                <span style="color:#1f9254; font-weight:600;">● {{ $item->stok_tersedia }} tersedia</span>
                            </div>

                            <a href="{{ route('pinjam.create', ['alat_id' => $item->id]) }}" class="btn-simpala" style="width:100%; justify-content:center; margin-top:auto; padding:0.48rem; font-size:0.8rem;">
                                Ajukan Pinjam
                            </a>
                        </div>
                    </div>
                @empty
                    <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 12px; padding: 2rem; color:#9aa3b5; text-align: center; grid-column: 1 / -1;">
                        Tidak ada alat yang cocok atau tersedia saat ini.
                    </div>
                @endforelse
            </div>

            @if ($alat->hasPages())
                <div style="margin-top:1.5rem;">{{ $alat->links() }}</div>
            @endif
        </div>

        <!-- Kolom Kanan: Sidebar Bantuan & Informasi Operasional -->
        <div style="display: flex; flex-direction: column; gap: 1.2rem;">
            
            <div style="background: white; border: 1px solid #e5e9f0; border-radius: 14px; padding: 1.2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: #16213e; margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.4rem;">
                    🕒 Jam Pelayanan Sarpras
                </h4>
                <div style="font-size: 0.83rem; color: #475569; line-height: 1.6;">
                    <div style="display: flex; justify-content: space-between; padding-bottom: 0.4rem; border-bottom: 1px dashed #e2e8f0; margin-bottom: 0.4rem;">
                        <span>Senin – Kamis</span>
                        <strong>07.00 – 15.30 WIB</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span>Jumat</span>
                        <strong>07.00 – 14.00 WIB</strong>
                    </div>
                </div>
            </div>

            <div style="background: #ffffff; border: 1px solid #e5e9f0; border-radius: 14px; padding: 1.2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: #16213e; margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.4rem;">
                    📌 Syarat & Ketentuan Pinjam
                </h4>
                <ul style="font-size: 0.82rem; color: #64748b; line-height: 1.6; padding-left: 1.1rem; margin: 0;">
                    <li style="margin-bottom: 0.4rem;">Gunakan kartu identitas/akun siswa terverifikasi saat pengambilan.</li>
                    <li style="margin-bottom: 0.4rem;">Jaga kebersihan dan kelengkapan komponen alat.</li>
                    <li>Kembalikan tepat waktu sesuai tanggal batas kembali yang diajukan.</li>
                </ul>
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