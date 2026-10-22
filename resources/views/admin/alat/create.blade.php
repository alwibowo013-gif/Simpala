@extends('layouts.admin')
@section('title', 'Tambah Alat')

@section('content')
<div style="display: flex; flex-direction: column; min-height: 85vh; justify-content: space-between;">
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        
        <!-- Executive Hero Banner (Sesuai Standar SIMPALA) -->
        <div style="background: linear-gradient(135deg, #0f172a 0%, #16213e 50%, #1f6f82 100%); padding: 1.8rem; border-radius: 16px; border: 1px solid rgba(31, 111, 130, 0.3); box-shadow: 0 10px 15px -3px rgba(0,0,0,0.1); display: flex; justify-content: space-between; align-items: center; color: #fff;">
            <div>
                <div style="font-size: 0.75rem; font-weight: 700; color: #2dd4bf; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 0.2rem;">
                    SIMPALA &bull; MANAJEMEN INVENTARIS
                </div>
                <h1 style="font-size: 1.6rem; font-weight: 800; margin: 0; letter-spacing: -0.025em;">Tambah Data Alat</h1>
                <p style="font-size: 0.88rem; color: #94a3b8; margin: 0.3rem 0 0 0;">Lengkapi informasi alat yang akan didaftarkan ke inventaris.</p>
            </div>
            <div>
                <a href="{{ route('admin.alat.index') }}" style="display: inline-flex; align-items: center; gap: 0.5rem; padding: 0.6rem 1.2rem; border-radius: 10px; font-size: 0.85rem; font-weight: 600; background: rgba(15, 23, 42, 0.6); color: #cbd5e1; border: 1px solid rgba(255,255,255,0.1); text-decoration: none; transition: 0.2s;">
                    &larr; Kembali
                </a>
            </div>
        </div>

        <!-- Layout Grid 2 Kolom (Form & Bantuan) -->
        <div style="display: grid; grid-template-columns: 1fr 340px; gap: 1.5rem; align-items: start;">
            
            <!-- Panel Form Utama (Kiri) -->
            <div class="card-panel" style="background: #ffffff; padding: 1.8rem; border-radius: 16px; border: 1px solid #e5e9f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05); max-width: 100%;">
                <form action="{{ route('admin.alat.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Kategori</label>
                            <select name="kategori_id" id="kategori_select" class="form-control" onchange="toggleJurusanField()" style="width: 100%; padding: 0.65rem 0.9rem; border: 1px solid #cbd5e1; border-radius: 9px; font-size: 0.9rem; background: #fff;">
                                <option value="">-- Pilih Kategori --</option>
                                @foreach ($kategoriList as $kat)
                                    <option value="{{ $kat->id }}" data-nama="{{ strtolower($kat->nama_kategori) }}" {{ old('kategori_id') == $kat->id ? 'selected' : '' }}>
                                        {{ $kat->nama_kategori }}
                                    </option>
                                @endforeach
                            </select>
                            @error('kategori_id') <div class="form-error" style="color: #e63946; font-size: 0.78rem; margin-top: 0.3rem;">{{ $message }}</div> @enderror
                        </div>

                        <!-- Field Jurusan (Khusus Alat Praktikum) -->
                        <div class="form-group" id="jurusan_wrapper" style="display: none;">
                            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Jurusan <span style="color:#e63946;">*</span></label>
                            <select name="jurusan" id="jurusan_select" class="form-control" style="width: 100%; padding: 0.65rem 0.9rem; border: 1px solid #cbd5e1; border-radius: 9px; font-size: 0.9rem; background: #fff;">
                                <option value="">-- Pilih Jurusan --</option>
                                <option value="Rekayasa Perangkat Lunak (RPL)" {{ old('jurusan') == 'Rekayasa Perangkat Lunak (RPL)' ? 'selected' : '' }}>Rekayasa Perangkat Lunak (RPL)</option>
                                <option value="Teknik Listrik" {{ old('jurusan') == 'Teknik Listrik' ? 'selected' : '' }}>Teknik Listrik</option>
                                <option value="Teknik Mesin" {{ old('jurusan') == 'Teknik Mesin' ? 'selected' : '' }}>Teknik Mesin</option>
                                <option value="Teknik Elektronika" {{ old('jurusan') == 'Teknik Elektronika' ? 'selected' : '' }}>Teknik Elektronika</option>
                                <option value="Desain Permodelan Informasi Bangunan (DPIB)" {{ old('jurusan') == 'Desain Permodelan Informasi Bangunan (DPIB)' ? 'selected' : '' }}>Desain Permodelan Informasi Bangunan (DPIB)</option>
                                <option value="Teknik Otomotif" {{ old('jurusan') == 'Teknik Otomotif' ? 'selected' : '' }}>Teknik Otomotif</option>
                                <option value="Teknik Otomasi" {{ old('jurusan') == 'Teknik Otomasi' ? 'selected' : '' }}>Teknik Otomasi</option>
                            </select>
                            @error('jurusan') <div class="form-error" style="color: #e63946; font-size: 0.78rem; margin-top: 0.3rem;">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <!-- Informasi Kode Alat Otomatis -->
                        <div class="form-group">
                            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Kode Alat</label>
                            <div style="background: #f8fafc; border: 1px dashed #cbd5e1; border-radius: 9px; padding: 0.65rem 0.9rem; font-size: 0.88rem; color: #64748b; display: flex; align-items: center; gap: 0.5rem;">
                                <span>⚡</span> <span>Dibuat otomatis oleh sistem</span>
                            </div>
                        </div>

                        <div class="form-group">
                            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Nama Alat</label>
                            <input type="text" name="nama_alat" class="form-control" value="{{ old('nama_alat') }}" placeholder="Contoh: Multimeter Digital" style="width: 100%; padding: 0.65rem 0.9rem; border: 1px solid #cbd5e1; border-radius: 9px; font-size: 0.9rem;">
                            @error('nama_alat') <div class="form-error" style="color: #e63946; font-size: 0.78rem; margin-top: 0.3rem;">{{ $message }}</div> @enderror
                        </div>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-bottom: 1rem;">
                        <div class="form-group">
                            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Stok Total</label>
                            <input type="number" name="stok_total" min="0" class="form-control" value="{{ old('stok_total', 0) }}" style="width: 100%; padding: 0.65rem 0.9rem; border: 1px solid #cbd5e1; border-radius: 9px; font-size: 0.9rem;">
                            @error('stok_total') <div class="form-error" style="color: #e63946; font-size: 0.78rem; margin-top: 0.3rem;">{{ $message }}</div> @enderror
                        </div>

                        <div class="form-group">
                            <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Kondisi</label>
                            <select name="kondisi" class="form-control" style="width: 100%; padding: 0.65rem 0.9rem; border: 1px solid #cbd5e1; border-radius: 9px; font-size: 0.9rem; background: #fff;">
                                <option value="baik" {{ old('kondisi') == 'baik' ? 'selected' : '' }}>Baik</option>
                                <option value="rusak" {{ old('kondisi') == 'rusak' ? 'selected' : '' }}>Rusak</option>
                            </select>
                        </div>
                    </div>

                    <div class="form-group" style="margin-bottom: 1rem;">
                        <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Foto Alat (opsional)</label>
                        <input type="file" name="gambar" class="form-control" style="width: 100%; padding: 0.5rem; border: 1px solid #cbd5e1; border-radius: 9px; font-size: 0.88rem; background: #f8fafc;">
                        @error('gambar') <div class="form-error" style="color: #e63946; font-size: 0.78rem; margin-top: 0.3rem;">{{ $message }}</div> @enderror
                    </div>

                    <div style="margin-top: 1.5rem; display: flex; gap: 0.8rem;">
                        <button type="submit" style="padding: 0.65rem 1.5rem; border-radius: 10px; font-size: 0.9rem; font-weight: 700; background: linear-gradient(to right, #2dd4bf, #14b8a6); color: #0f172a; border: none; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(20, 184, 166, 0.2);">Simpan</button>
                        <a href="{{ route('admin.alat.index') }}" style="padding: 0.65rem 1.2rem; border-radius: 10px; font-size: 0.9rem; font-weight: 600; background: #f1f5f9; color: #475569; text-decoration: none;">Batal</a>
                    </div>
                </form>
            </div>

            <!-- Panel Informasi & Bantuan (Kanan) -->
            <div style="display: flex; flex-direction: column; gap: 1rem;">
                <div style="background: #ffffff; padding: 1.4rem; border-radius: 16px; border: 1px solid #e5e9f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
                    <h4 style="font-size: 0.95rem; font-weight: 700; color: #16213e; margin-bottom: 0.8rem; display: flex; align-items: center; gap: 0.4rem;">
                        💡 Ketentuan Pendataan Alat
                    </h4>
                    <ul style="font-size: 0.83rem; color: #6b7280; line-height: 1.6; padding-left: 1.1rem; margin: 0;">
                        <li style="margin-bottom: 0.4rem;"><strong>Kode Alat:</strong> Dibuat otomatis oleh sistem sesuai format jurusan/kategori (misal: <code>JRPL-0001</code>, <code>MLT-0001</code>)[cite: 12].</li>
                        <li style="margin-bottom: 0.4rem;"><strong>Alat Praktikum:</strong> Pilih jurusan spesifik jika alat diperuntukkan untuk bengkel/lab tertentu[cite: 12].</li>
                        <li><strong>Foto Alat:</strong> Disarankan menggunakan gambar dengan latar bersih agar mudah dikenali siswa[cite: 12].</li>
                    </ul>
                </div>

                <div style="background: #f4f6fb; padding: 1.2rem; border-radius: 16px; border: 1px solid #e2e8f0; font-size: 0.82rem; color: #4b5563; line-height: 1.5; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.02);">
                    📦 <strong>Manajemen Stok:</strong> Stok tersedia akan otomatis disamakan dengan stok total saat pertama kali alat dibuat[cite: 12].
                </div>
            </div>

        </div>
    </div>

    <!-- Footer Card Gelap 3 Kolom (Sesuai Gambar Pertama) -->
    <footer style="margin-top: 3rem; background: #0f172a; border-radius: 16px; padding: 2rem; color: #94a3b8; border: 1px solid #1e293b; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3);">
        <div style="display: grid; grid-template-columns: 2fr 1.2fr 1.2fr; gap: 2rem; align-items: start; margin-bottom: 1.5rem; border-bottom: 1px solid #1e293b; padding-bottom: 1.5rem;">
            <div>
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #fff; font-weight: 700; font-size: 1rem; margin-bottom: 0.5rem;">
                    <span>🛡️</span> SIMPALA ADMIN
                </div>
                <p style="font-size: 0.82rem; line-height: 1.5; margin: 0; color: #94a3b8;">
                    Portal Manajemen Sarana & Prasarana SMK Negeri 5 Surakarta. Layanan pengawasan inventaris, verifikasi peminjaman, dan cetak laporan berkala[cite: 15].
                </p>
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #fff; font-weight: 700; font-size: 0.9rem; margin-bottom: 0.5rem;">
                    <span>⏰</span> JAM OPERASIONAL
                </div>
                <div style="font-size: 0.81rem; line-height: 1.5; color: #94a3b8;">
                    Senin – Kamis: <strong>07.00 – 15.30 WIB</strong><br>
                    Jumat: <strong>07.00 – 14.00 WIB</strong><br>
                    Status Server: <span style="color: #4ade80; font-weight: 600;">&bull; Online</span>
                </div>
            </div>
            <div>
                <div style="display: flex; align-items: center; gap: 0.5rem; color: #fff; font-weight: 700; font-size: 0.9rem; margin-bottom: 0.5rem;">
                    <span>📌</span> PINTASAN ADMIN
                </div>
                <ul style="list-style: none; padding: 0; margin: 0; font-size: 0.81rem; line-height: 1.6;">
                    <li><a href="{{ route('admin.alat.index') }}" style="color: #38bdf8; text-decoration: none;">Kelola Master Data Alat</a></li>
                    <li><a href="#" style="color: #38bdf8; text-decoration: none;">Verifikasi Peminjaman</a></li>
                    <li><a href="#" style="color: #38bdf8; text-decoration: none;">Laporan & Transaksi</a></li>
                </ul>
            </div>
        </div>
        <div style="display: flex; justify-content: space-between; align-items: center; font-size: 0.78rem; color: #64748b;">
            <div>&copy; 2026 SMK Negeri 5 Surakarta &bull; Rekayasa Perangkat Lunak (RPL).</div>
            <div>Administrator Panel SIMPALA v1.0</div>
        </div>
    </footer>
</div>

<script>
    function toggleJurusanField() {
        const selectKategori = document.getElementById('kategori_select');
        const selectedOption = selectKategori.options[selectKategori.selectedIndex];
        const namaKategori = selectedOption ? (selectedOption.getAttribute('data-nama') || '') : '';
        const jurusanWrapper = document.getElementById('jurusan_wrapper');

        if (namaKategori.includes('praktikum')) {
            jurusanWrapper.style.display = 'block';
        } else {
            jurusanWrapper.style.display = 'none';
            document.getElementById('jurusan_select').value = '';
        }
    }

    document.addEventListener('DOMContentLoaded', toggleJurusanField);
</script>
@endsection