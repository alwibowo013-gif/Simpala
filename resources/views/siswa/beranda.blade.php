@extends('layouts.siswa')
@section('title', 'Beranda')

@section('content')
    <!-- Banner Ucapan & Hero -->
    <div style="background: linear-gradient(135deg, #16213e 0%, #1f6f82 100%); border-radius: 16px; padding: 2rem; color: white; margin-bottom: 1.8rem; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; box-shadow: 0 10px 25px -5px rgba(31, 111, 130, 0.25);">
        <div>
            <div style="font-size: 0.8rem; font-weight: 700; color: #7fd4e8; letter-spacing: 0.05em; margin-bottom: 0.4rem; text-transform: uppercase;">
                Selamat Datang di SIMPALA
            </div>
            <h1 style="font-size: 1.6rem; font-weight: 700; margin-bottom: 0.4rem;">Halo, {{ auth()->user()->name }} 👋</h1>
            <p style="font-size: 0.9rem; color: #cbd5e1; margin: 0;">Mau pinjam alat praktikum atau fasilitas sekolah apa hari ini?</p>
        </div>
        <div>
            <a href="{{ route('jelajah') }}" class="btn-simpala" style="background: #ffffff; color: #16213e; font-weight: 700; padding: 0.75rem 1.4rem; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.5rem; border: none;">
                🔎 Jelajah Katalog Alat
            </a>
        </div>
    </div>

    <!-- Ringkasan Status Peminjaman Siswa -->
    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.2rem; margin-bottom: 1.8rem;">
        <div style="background: white; border: 1px solid #e5e9f0; border-radius: 14px; padding: 1.2rem 1.4rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #e0f2fe; color: #0369a1; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                ⏳
            </div>
            <div>
                <div style="font-size: 0.78rem; color: #64748b; font-weight: 600;">Menunggu Verifikasi</div>
                <div style="font-size: 1.4rem; font-weight: 700; color: #1e293b;">{{ $menungguVerifikasi ?? 0 }} <span style="font-size: 0.8rem; font-weight: 500; color: #94a3b8;">pengajuan</span></div>
            </div>
        </div>

        <div style="background: white; border: 1px solid #e5e9f0; border-radius: 14px; padding: 1.2rem 1.4rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #fef3c7; color: #d97706; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                📦
            </div>
            <div>
                <div style="font-size: 0.78rem; color: #64748b; font-weight: 600;">Sedang Dipinjam</div>
                <div style="font-size: 1.4rem; font-weight: 700; color: #1e293b;">{{ $sedangDipinjam ?? 0 }} <span style="font-size: 0.8rem; font-weight: 500; color: #94a3b8;">alat</span></div>
            </div>
        </div>

        <div style="background: white; border: 1px solid #e5e9f0; border-radius: 14px; padding: 1.2rem 1.4rem; display: flex; align-items: center; gap: 1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
            <div style="width: 48px; height: 48px; border-radius: 12px; background: #dcfce7; color: #15803d; display: flex; align-items: center; justify-content: center; font-size: 1.4rem; flex-shrink: 0;">
                ✅
            </div>
            <div>
                <div style="font-size: 0.78rem; color: #64748b; font-weight: 600;">Selesai Dikembalikan</div>
                <div style="font-size: 1.4rem; font-weight: 700; color: #1e293b;">{{ $totalSelesai ?? 0 }} <span style="font-size: 0.8rem; font-weight: 500; color: #94a3b8;">kali</span></div>
            </div>
        </div>
    </div>

    <!-- Grid 2 Kolom utama -->
    <div style="display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; align-items: start;">
        
        <!-- Kolom Kiri -->
        <div style="display: flex; flex-direction: column; gap: 1.8rem;">
            
            <!-- Section 1: Panel Katalog Alat Siap Dipinjam (Bermasker Card) -->
            <div style="background: white; border: 1px solid #e5e9f0; border-radius: 16px; padding: 1.4rem; box-shadow: 0 2px 10px rgba(0,0,0,0.02);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem; padding-bottom: 0.8rem; border-bottom: 1px solid #f1f5f9;">
                    <div style="display: flex; align-items: center; gap: 0.6rem;">
                        <span style="background: #e0f2fe; color: #1f6f82; width: 32px; height: 32px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1rem;">🛠️</span>
                        <div>
                            <h3 style="font-size: 1.05rem; font-weight: 700; color: #16213e; margin: 0;">Alat Siap Dipinjam</h3>
                            <div style="font-size: 0.75rem; color: #64748b; margin-top: 0.1rem;">Katalog fasilitas praktikum yang tersedia untuk dipinjam</div>
                        </div>
                    </div>
                    <a href="{{ route('jelajah') }}" style="font-size: 0.82rem; color: #1f6f82; font-weight: 700; text-decoration: none; background: #f0fdfa; padding: 0.4rem 0.8rem; border-radius: 8px; border: 1px solid #ccfbf1;">Lihat Semua &rarr;</a>
                </div>

                <div style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 1rem;">
                    @forelse ($alatPopuler as $item)
                        <div style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; overflow: hidden; display: flex; flex-direction: column; transition: all 0.2s;" onmouseover="this.style.borderColor='#1f6f82'" onmouseout="this.style.borderColor='#e2e8f0'">
                            <div style="height: 120px; background: #f8fafc; display: flex; align-items: center; justify-content: center; overflow: hidden; border-bottom: 1px solid #f1f5f9;">
                                @if ($item->gambar)
                                    @php
                                        $pathGambar = \Illuminate\Support\Str::after($item->gambar, 'public/');
                                    @endphp
                                    <img src="{{ asset('storage/' . $pathGambar) }}" alt="{{ $item->nama_alat }}" style="width: 100%; height: 100%; object-fit: contain; padding: 0.6rem;">
                                @else
                                    <span style="font-size: 2rem;">📦</span>
                                @endif
                            </div>
                            <div style="padding: 0.85rem; display: flex; flex-direction: column; flex: 1;">
                                <span style="font-size: 0.65rem; background: #e0f2fe; color: #0369a1; padding: 0.15rem 0.5rem; border-radius: 999px; font-weight: 700; align-self: flex-start; margin-bottom: 0.3rem;">
                                    {{ $item->kategori->nama_kategori ?? '-' }}
                                </span>
                                <div style="font-weight: 700; font-size: 0.85rem; color: #16213e; line-height: 1.3; margin-bottom: 0.2rem;">{{ $item->nama_alat }}</div>
                                <div style="font-size: 0.75rem; color: #1f9254; font-weight: 600; margin-bottom: 0.8rem;">● {{ $item->stok_tersedia }} tersedia</div>
                                
                                <a href="{{ route('pinjam.create', ['alat_id' => $item->id]) }}" class="btn-simpala" style="width: 100%; justify-content: center; margin-top: auto; padding: 0.45rem; font-size: 0.78rem;">
                                    Ajukan Pinjam
                                </a>
                            </div>
                        </div>
                    @empty
                        <div style="background: #f8fafc; padding: 1.5rem; border-radius: 12px; border: 1px dashed #cbd5e1; text-align: center; color: #94a3b8; grid-column: 1 / -1;">
                            Belum ada data alat tersedia.
                        </div>
                    @endforelse
                </div>
            </div>

            <!-- Section 2: Banner Pengumuman & Ketentuan Peminjaman -->
            <div style="background: white; border: 1px solid #e5e9f0; border-radius: 16px; padding: 1.3rem 1.5rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.8rem;">
                    <span style="font-size: 1.2rem;">📢</span>
                    <h3 style="font-size: 1rem; font-weight: 700; color: #16213e; margin: 0;">Ketentuan & Etika Peminjaman Alat</h3>
                </div>
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; font-size: 0.82rem; color: #475569;">
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 0.85rem 1rem; border-radius: 10px;">
                        <strong style="color: #1e293b; display: block; margin-bottom: 0.2rem;">🛡️ Tanggung Jawab Fisik</strong>
                        Pastikan alat diperiksa kondisinya sebelum meninggalkan ruang Sarpras. Laporkan jika ada cacat fisik awal.
                    </div>
                    <div style="background: #f8fafc; border: 1px solid #e2e8f0; padding: 0.85rem 1rem; border-radius: 10px;">
                        <strong style="color: #1e293b; display: block; margin-bottom: 0.2rem;">⏰ Pengembalian Tepat Waktu</strong>
                        Keterlambatan pengembalian tanpa konfirmasi dapat membekukan hak pengajuan peminjaman selanjutnya.
                    </div>
                </div>
            </div>

        </div>

        <!-- Kolom Kanan: Video, Pintasan & Alur -->
        <div style="display: flex; flex-direction: column; gap: 1.2rem;">
            
            <!-- Video Panduan Ringkas di Sidebar Kanan -->
            <div style="background: white; border: 1px solid #e5e9f0; border-radius: 14px; padding: 1.1rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                <div style="margin-bottom: 0.8rem;">
                    <span style="font-size: 0.65rem; font-weight: 700; color: #1f6f82; background: #e0f2fe; padding: 0.2rem 0.55rem; border-radius: 999px; text-transform: uppercase;">
                        🎬 VIDEO TUTORIAL
                    </span>
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #16213e; margin-top: 0.4rem;">Cara Pinjam Alat</h4>
                </div>
                <div style="width: 100%; border-radius: 10px; overflow: hidden; background: #0f172a;">
                    <video controls style="width: 100%; display: block;">
                        <source src="{{ asset('assets/video/tutorial-peminjaman.mp4') }}" type="video/mp4">
                        Browser tidak mendukung video HTML5.
                    </video>
                </div>
            </div>

            <!-- Kartu Akses Cepat -->
            <div style="background: white; border: 1px solid #e5e9f0; border-radius: 14px; padding: 1.2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: #16213e; margin-bottom: 0.8rem;">📌 Menu Pintasan</h4>
                <div style="display: flex; flex-direction: column; gap: 0.6rem;">
                    <a href="{{ route('jelajah') }}" style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; background: #f8fafc; border-radius: 10px; text-decoration: none; color: #334155; font-size: 0.85rem; font-weight: 600; border: 1px solid #e2e8f0;">
                        <span>🔍 Jelajah Katalog Alat</span>
                        <span>&rsaquo;</span>
                    </a>
                    <a href="{{ route('pinjam.saya') }}" style="display: flex; align-items: center; justify-content: space-between; padding: 0.75rem 1rem; background: #f8fafc; border-radius: 10px; text-decoration: none; color: #334155; font-size: 0.85rem; font-weight: 600; border: 1px solid #e2e8f0;">
                        <span>📋 Peminjaman Saya & Riwayat</span>
                        <span>&rsaquo;</span>
                    </a>
                </div>
            </div>

            <!-- Panduan Tata Cara Peminjaman -->
            <div style="background: #ffffff; border: 1px solid #e5e9f0; border-radius: 14px; padding: 1.2rem; box-shadow: 0 2px 8px rgba(0,0,0,0.02);">
                <h4 style="font-size: 0.95rem; font-weight: 700; color: #16213e; margin-bottom: 1rem; display: flex; align-items: center; gap: 0.4rem;">
                    💡 Alur Peminjaman Alat
                </h4>
                <div style="display: flex; flex-direction: column; gap: 0.9rem; font-size: 0.82rem; color: #475569;">
                    <div style="display: flex; gap: 0.8rem; align-items: flex-start;">
                        <span style="background: #1f6f82; color: white; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.72rem; flex-shrink: 0;">1</span>
                        <div><strong>Pilih Alat:</strong> Cari alat di menu Jelajah Alat sesuai kebutuhan praktik/kegiatan.</div>
                    </div>
                    <div style="display: flex; gap: 0.8rem; align-items: flex-start;">
                        <span style="background: #1f6f82; color: white; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.72rem; flex-shrink: 0;">2</span>
                        <div><strong>Isi Form:</strong> Tentukan jumlah, tanggal kembali, dan keperluan peminjaman.</div>
                    </div>
                    <div style="display: flex; gap: 0.8rem; align-items: flex-start;">
                        <span style="background: #1f6f82; color: white; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.72rem; flex-shrink: 0;">3</span>
                        <div><strong>Verifikasi:</strong> Tunggu petugas Sarpras memverifikasi pengajuanmu.</div>
                    </div>
                    <div style="display: flex; gap: 0.8rem; align-items: flex-start;">
                        <span style="background: #1f6f82; color: white; width: 22px; height: 22px; border-radius: 50%; display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.72rem; flex-shrink: 0;">4</span>
                        <div><strong>Ambil & Kembalikan:</strong> Ambil fisik alat di ruang Sarpras dan kembalikan tepat waktu.</div>
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
    <!-- ===== POP-UP SELAMAT DATANG SISWA (WELCOME NOTIFICATION) ===== -->
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
            Selamat Datang!
        </h3>
        
        <p style="font-size: 0.9rem; color: #475569; line-height: 1.6; margin-bottom: 1.8rem;">
            {{ session('login_success') }}
        </p>

        <button type="button" onclick="closeWelcomePopup()" style="width: 100%; background: linear-gradient(135deg, #16213e 0%, #1f6f82 100%); color: white; border: none; padding: 0.8rem; border-radius: 10px; font-weight: 700; font-size: 0.95rem; cursor: pointer; box-shadow: 0 4px 12px rgba(31, 111, 130, 0.3); transition: all 0.2s;" onmouseover="this.style.transform='translateY(-1px)'" onmouseout="this.style.transform='translateY(0)'">
            Mulai Eksplorasi Alat
        </button>
    </div>
</div>

<style>
    @keyframes fadeInModal {
        from { opacity: 0; }
        to { opacity: 1; }
    }
    
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

    setTimeout(() => {
        closeWelcomePopup();
    }, 5000);
</script>
@endif
@endsection