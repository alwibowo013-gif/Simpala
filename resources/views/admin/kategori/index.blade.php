@extends('layouts.admin')
@section('title', 'Kategori Alat')

@section('content')
<div style="display: flex; flex-direction: column; min-height: 85vh; justify-content: space-between;">
    <div style="display: flex; flex-direction: column; gap: 1.5rem;">
        
        <!-- Executive Hero Banner Control Center (Kategori Alat) -->
        <div style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 55%, #0f766e 100%); border-radius: 16px; padding: 1.8rem 2.2rem; color: white; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 1.5rem; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.25);">
            <div>
                <div style="display: flex; align-items: center; gap: 0.6rem; margin-bottom: 0.5rem;">
                    <span style="background: rgba(20, 184, 166, 0.2); color: #2dd4bf; font-size: 0.72rem; font-weight: 700; padding: 0.25rem 0.75rem; border-radius: 999px; border: 1px solid rgba(45, 212, 191, 0.3); letter-spacing: 0.05em; text-transform: uppercase;">
                        🏷️ KLASIFIKASI INVENTARIS
                    </span>
                    <span style="font-size: 0.78rem; color: #94a3b8; font-weight: 500;">
                        • Sarpras SMK Negeri 5 Surakarta
                    </span>
                </div>
                <h1 style="font-size: 1.6rem; font-weight: 800; margin-bottom: 0.3rem; letter-spacing: -0.02em;">
                    Kategori Alat
                </h1>
                <p style="font-size: 0.88rem; color: #cbd5e1; margin: 0; max-width: 600px;">
                    Kelola kelompok jenis dan pengelompokan barang untuk mempermudah pemilahan katalog serta pencarian siswa.
                </p>
            </div>

            <div>
                <!-- Tombol Memicu Modal Tambah -->
                <button type="button" onclick="openTambahModal()" style="background: #14b8a6; color: #0f172a; font-weight: 700; padding: 0.75rem 1.3rem; border-radius: 10px; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; border: none; font-size: 0.88rem; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(20, 184, 166, 0.2);">
                    + Tambah Kategori
                </button>
            </div>
        </div>

        <!-- Tabel Data Kategori -->
        <div class="card-panel" style="background: #ffffff; padding: 1.5rem; border-radius: 16px; border: 1px solid #e5e9f0; box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05);">
            <table class="simple-table" style="width: 100%; border-collapse: collapse;">
                <thead>
                    <tr style="border-bottom: 1px solid #e2e8f0; text-align: left; font-size: 0.82rem; color: #64748b; text-transform: uppercase;">
                        <th style="padding: 0.75rem 1rem; width:60px;">No</th>
                        <th style="padding: 0.75rem 1rem;">Nama Kategori</th>
                        <th style="padding: 0.75rem 1rem; width:120px; text-align: right;">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($kategori as $item)
                        <tr style="border-bottom: 1px solid #f1f5f9; font-size: 0.9rem; color: #334155;">
                            <td style="padding: 0.9rem 1rem; color: #64748b;">{{ $loop->iteration }}</td>
                            <td style="padding: 0.9rem 1rem;"><strong>{{ $item->nama_kategori }}</strong></td>
                            <td style="padding: 0.9rem 1rem; text-align: right;">
                                <div style="display: inline-flex; gap: 0.4rem; justify-content: flex-end;">
                                    <!-- Tombol Memicu Modal Edit -->
                                    <button type="button" onclick="openEditModal('{{ $item->id }}', '{{ $item->nama_kategori }}')" class="icon-btn" title="Edit" style="background: #f1f5f9; border: 1px solid #cbd5e1; padding: 0.4rem 0.6rem; border-radius: 8px; cursor: pointer;">✏️</button>
                                    
                                    <form action="{{ route('admin.kategori.destroy', $item->id) }}" method="POST" style="display:inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" class="icon-btn danger" title="Hapus"
                                                onclick="showConfirm(this.closest('form'), 'Hapus Kategori?', 'Kategori \'{{ $item->nama_kategori }}\' akan dihapus permanen. Alat yang memakai kategori ini juga ikut terhapus.', 'Hapus', true)" style="background: #fef2f2; border: 1px solid #fecaca; padding: 0.4rem 0.6rem; border-radius: 8px; cursor: pointer;">🗑️</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" style="text-align:center; color:#9aa3b5; padding:2rem;">Belum ada data kategori.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- ===== MODAL POP-UP TAMBAH & EDIT KATEGORI ===== -->
    <div id="kategoriModal" style="display: none; position: fixed; inset: 0; background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(4px); z-index: 999; align-items: center; justify-content: center; padding: 1rem;">
        <div style="background: #ffffff; width: 100%; max-width: 450px; border-radius: 20px; padding: 2rem; box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04); border: 1px solid #e2e8f0; position: relative; animation: modalPop 0.25s ease-out;">
            
            <!-- Header Modal -->
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.2rem;">
                <h3 id="modalTitle" style="font-size: 1.15rem; font-weight: 800; color: #0f172a; margin: 0;">Tambah Kategori Alat</h3>
                <button type="button" onclick="closeKategoriModal()" style="background: transparent; border: none; font-size: 1.2rem; color: #64748b; cursor: pointer; padding: 0.2rem;">✕</button>
            </div>

            <!-- Form Dinamis (Action & Method diatur oleh JavaScript) -->
            <form id="kategoriForm" action="{{ route('admin.kategori.store') }}" method="POST">
                @csrf
                <div id="methodContainer"></div>

                <div style="margin-bottom: 1.2rem;">
                    <label style="display: block; font-size: 0.82rem; font-weight: 700; color: #334155; margin-bottom: 0.4rem;">Nama Kategori</label>
                    <input type="text" id="nama_kategori_input" name="nama_kategori" required placeholder="Contoh: Elektronik, Alat Praktikum..." style="width: 100%; padding: 0.65rem 0.9rem; border: 1px solid #cbd5e1; border-radius: 9px; font-size: 0.9rem; outline: none;">
                </div>

                <div style="display: flex; justify-content: flex-end; gap: 0.6rem; margin-top: 1.5rem;">
                    <button type="button" onclick="closeKategoriModal()" style="padding: 0.6rem 1.1rem; border-radius: 9px; font-size: 0.88rem; font-weight: 600; background: #f1f5f9; color: #475569; border: none; cursor: pointer;">Batal</button>
                    <button type="submit" id="modalSubmitBtn" style="padding: 0.6rem 1.3rem; border-radius: 9px; font-size: 0.88rem; font-weight: 700; background: linear-gradient(to right, #2dd4bf, #14b8a6); color: #0f172a; border: none; cursor: pointer; box-shadow: 0 4px 6px -1px rgba(20, 184, 166, 0.2);">Simpan Kategori</button>
                </div>
            </form>
        </div>
    </div>

    <!-- ===== FOOTER UTAMA ADMIN SIMPALA ===== -->
    <footer style="background: #0f172a; color: #9aa7c7; margin-top: 3rem; border-radius: 16px; border: 1px solid #1e293b; padding: 2rem; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.3);">
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
                    <li><a href="{{ route('admin.alat.index') }}" style="color: #5fd0e8; text-decoration: none;">📦 Kelola Master Data Alat</a></li>
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
</div>

<!-- Script JavaScript Pengaturan Modal Pop-Up -->
<script>
    function openTambahModal() {
        document.getElementById('modalTitle').innerText = 'Tambah Kategori Alat';
        document.getElementById('kategoriForm').action = "{{ route('admin.kategori.store') }}";
        document.getElementById('methodContainer').innerHTML = ''; // POST standar
        document.getElementById('nama_kategori_input').value = '';
        document.getElementById('modalSubmitBtn').innerText = 'Simpan Kategori';
        
        document.getElementById('kategoriModal').style.display = 'flex';
    }

    function openEditModal(id, nama) {
        document.getElementById('modalTitle').innerText = 'Edit Kategori Alat';
        // Mengarahkan route update ke ID yang sesuai
        document.getElementById('kategoriForm').action = "{{ url('admin/kategori') }}/" + id;
        // Menambahkan directive @method('PUT') untuk update Laravel
        document.getElementById('methodContainer').innerHTML = '<input type="hidden" name="_method" value="PUT">';
        document.getElementById('nama_kategori_input').value = nama;
        document.getElementById('modalSubmitBtn').innerText = 'Update Kategori';
        
        document.getElementById('kategoriModal').style.display = 'flex';
    }

    function closeKategoriModal() {
        document.getElementById('kategoriModal').style.display = 'none';
    }

    // Tutup modal jika klik di luar kotak putihnya
    window.onclick = function(event) {
        let modal = document.getElementById('kategoriModal');
        if (event.target == modal) {
            closeKategoriModal();
        }
    }
</script>
@endsection