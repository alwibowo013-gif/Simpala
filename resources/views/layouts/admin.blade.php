<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin') - SIMPALA</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, 'Segoe UI', Roboto, Arial, sans-serif; background: #f4f6fb; color: #1f2937; }

        .admin-shell { display: flex; min-height: 100vh; }

        /* ===== SIDEBAR ===== */
        .sidebar {
            width: 250px;
            background: #16213e;
            color: white;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            padding: 1.5rem 1rem;
            flex-shrink: 0;
        }
        .sidebar .brand { display: flex; align-items: center; gap: 0.7rem; padding: 0 0.5rem; margin-bottom: 2rem; }
        .sidebar .brand .icon-box {
            width: 38px; height: 38px; background: white; border-radius: 9px;
            display: flex; align-items: center; justify-content: center; font-size: 18px; flex-shrink: 0;
        }
        .sidebar .brand .name { font-weight: 700; font-size: 1rem; line-height: 1.2; }
        .sidebar .brand .subname { font-size: 0.62rem; color: #9aa7c7; letter-spacing: 0.03em; }

        .sidebar nav a {
            display: flex; align-items: center; gap: 0.65rem;
            padding: 0.65rem 0.9rem; border-radius: 8px;
            color: #b7c0d8; text-decoration: none; font-size: 0.88rem;
            margin-bottom: 0.2rem; position: relative;
        }
        .sidebar nav a.active { background: #1f6f82; color: white; font-weight: 600; }
        .sidebar nav a:not(.active):not(.disabled):hover { background: rgba(255,255,255,0.07); color: white; }
        .sidebar nav a.disabled { color: #5b6584; cursor: default; }
        .sidebar nav a .soon-tag {
            margin-left: auto; font-size: 0.62rem; background: rgba(255,255,255,0.08);
            padding: 0.15rem 0.45rem; border-radius: 999px; color: #8b95b3;
        }

        /* Styling Widget Jam Sidebar */
        .sidebar-clock-box {
            background: rgba(255, 255, 255, 0.06);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
            padding: 0.65rem 0.85rem;
            margin-top: 1rem;
            margin-bottom: 0.4rem;
            display: flex;
            align-items: center;
            gap: 0.6rem;
        }
        .sidebar-clock-box .clock-icon {
            font-size: 1rem;
        }
        .sidebar-clock-box .clock-details {
            line-height: 1.2;
        }
        .sidebar-clock-box .clock-time {
            font-size: 0.85rem;
            font-weight: 700;
            color: #2dd4bf;
        }
        .sidebar-clock-box .clock-date {
            font-size: 0.68rem;
            color: #9aa7c7;
            margin-top: 0.1rem;
        }

        .sidebar .user-box {
            display: flex; align-items: center; justify-content: space-between; gap: 0.6rem;
            padding: 0.8rem 0.5rem 0.2rem 0.5rem; border-top: 1px solid rgba(255,255,255,0.1); margin-top: 0.6rem;
        }
        .sidebar .user-box .avatar {
            width: 34px; height: 34px; border-radius: 50%; background: #1f6f82;
            display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.85rem; flex-shrink:0;
        }
        .sidebar .user-box .info { font-size: 0.8rem; line-height: 1.2; flex: 1; }
        .sidebar .user-box .info .name { font-weight: 600; }
        .sidebar .user-box .info .role { color: #9aa7c7; font-size: 0.72rem; }
        
        .sidebar .user-box .profile-link {
            display: flex; align-items: center; gap: 0.6rem; flex: 1; min-width: 0;
            color: inherit; text-decoration: none;
        }
        .sidebar .user-box img.avatar { object-fit: cover; background: #1f6f82; }
        
        /* Tombol Logout Style */
        .sidebar .user-box .btn-logout {
            background: transparent;
            border: none;
            color: #9aa7c7;
            cursor: pointer;
            padding: 6px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .sidebar .user-box .btn-logout:hover {
            color: #f87171;
            background-color: rgba(239, 68, 68, 0.15);
        }

        /* ===== MAIN CONTENT ===== */
        .main { flex: 1; padding: 2rem 2.5rem; min-width: 0; }
        .page-title h1 { font-size: 1.6rem; font-weight: 700; color: #16213e; margin-bottom: 0.2rem; }
        .page-title p { color: #6b7280; font-size: 0.9rem; }
        .page-header-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 1.5rem; gap: 1rem; flex-wrap: wrap; }

        .alert-error {
            background: #fdecea; color: #b42318; border: 1px solid #f5c2c0;
            padding: 0.7rem 1rem; border-radius: 8px; font-size: 0.88rem; margin-bottom: 1.2rem;
        }
        .alert-success {
            background: #e3f6ea; color: #1f9254; border: 1px solid #b9e8ca;
            padding: 0.7rem 1rem; border-radius: 8px; font-size: 0.88rem; margin-bottom: 1.2rem;
        }

        /* ===== STAT CARDS ===== */
        .stat-grid { display: flex; flex-wrap: wrap; gap: 1rem; margin-bottom: 1.5rem; }
        .stat-card { background: white; border-radius: 12px; padding: 1.1rem 1.3rem; border: 1px solid #e5e9f0; flex: 1 1 220px; }
        .stat-card .top-row { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 0.8rem; }
        .stat-card .label { font-size: 0.85rem; color: #4b5563; }
        .stat-card .icon {
            width: 34px; height: 34px; border-radius: 8px; background: #eef2fb;
            display: flex; align-items: center; justify-content: center; font-size: 0.95rem;
        }
        .stat-card .value { font-size: 1.7rem; font-weight: 700; color: #16213e; }
        .stat-card .sub { font-size: 0.78rem; color: #9aa3b5; margin-top: 0.2rem; }

        /* ===== CARD PANEL / TABLE ===== */
        .card-panel { background: white; border-radius: 12px; border: 1px solid #e5e9f0; padding: 1.3rem; }
        .card-panel-title { font-size: 1rem; font-weight: 700; color: #16213e; margin-bottom: 1rem; }

        table.simple-table { width: 100%; border-collapse: collapse; }
        .simple-table th {
            text-align: left; font-size: 0.72rem; text-transform: uppercase; letter-spacing: 0.03em;
            color: #9aa3b5; padding: 0.6rem 0.5rem; border-bottom: 1px solid #eef1f6;
        }
        .simple-table td { padding: 0.75rem 0.5rem; border-bottom: 1px solid #f3f5f9; font-size: 0.88rem; vertical-align: middle; }
        .simple-table tr:last-child td { border-bottom: none; }

        .badge-status { padding: 0.28rem 0.7rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; display: inline-block; }
        .badge-baik { background: #e3f6ea; color: #1f9254; }
        .badge-rusak { background: #fdf3d8; color: #a1710b; }

        .thumb-box { width: 42px; height: 42px; border-radius: 8px; background: #eef2fb; object-fit: cover; }
        .thumb-empty { width: 42px; height: 42px; border-radius: 8px; background: #eef2fb; display:flex; align-items:center; justify-content:center; font-size:0.65rem; color:#9aa3b5; }

        /* ===== BUTTONS ===== */
        .btn-simpala {
            background: #1f6f82; color: white; border: none; padding: 0.65rem 1.15rem;
            border-radius: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer;
            text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem;
        }
        .btn-simpala:hover { background: #185a68; color: white; }
        .btn-outline { background: white; border: 1px solid #d7dce3; color: #374151; padding: 0.6rem 1rem;
            border-radius: 8px; font-size: 0.85rem; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; }
        .icon-btn { width: 30px; height: 30px; border-radius: 6px; border: 1px solid #e5e9f0; background: white;
            display: inline-flex; align-items: center; justify-content: center; cursor: pointer; color: #374151; text-decoration: none; }
        .icon-btn.danger { color: #b42318; border-color: #f5c2c0; }

        /* ===== FORM ===== */
        .form-group { margin-bottom: 1.1rem; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: #1f2937; margin-bottom: 0.4rem; }
        .form-control {
            width: 100%; padding: 0.6rem 0.85rem; border: 1px solid #d7dce3; border-radius: 8px; font-size: 0.9rem;
        }
        .form-control:focus { outline: none; border-color: #1f6f82; }
        .form-hint { font-size: 0.78rem; color: #9ca3af; margin-top: 0.3rem; }
        .form-error { font-size: 0.78rem; color: #b42318; margin-top: 0.3rem; }

        .search-row { display: flex; gap: 0.7rem; margin-bottom: 1.2rem; flex-wrap: wrap; }
        .search-row .search-input { flex: 1; min-width: 220px; }

        @media (max-width: 760px) {
            .admin-shell { flex-direction: column; }
            .sidebar { width: 100%; flex-direction: row; flex-wrap: wrap; }
            .sidebar nav { display: flex; flex-wrap: wrap; gap: 0.3rem; }
            .sidebar-clock-box { display: none; }
            .sidebar .user-box { display: none; }
            .stat-card { flex: 1 1 100%; }
            .main { padding: 1.2rem; }
        }

        /* ===== MODAL KONFIRMASI (pengganti alert/confirm bawaan browser) ===== */
        .modal-overlay {
            display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(22, 33, 62, 0.45);
            align-items: center; justify-content: center; z-index: 1000;
        }
        .modal-overlay.show { display: flex; }
        .modal-box {
            background: white; border-radius: 12px; padding: 1.6rem;
            width: 100%; max-width: 380px; text-align: center;
            box-shadow: 0 10px 30px rgba(22,33,62,0.2);
        }
        .modal-box .modal-title { font-size: 1.05rem; font-weight: 700; color: #16213e; margin-bottom: 0.5rem; }
        .modal-box .modal-message { font-size: 0.88rem; color: #6b7280; margin-bottom: 1.4rem; line-height: 1.5; }
        .modal-box .modal-actions { display: flex; gap: 0.7rem; justify-content: center; }
        .modal-box .modal-actions button { flex: 1; }
        .btn-danger-solid {
            background: #b42318; color: white; border: none; padding: 0.65rem 1.15rem;
            border-radius: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer;
        }
        .btn-danger-solid:hover { background: #8e1b12; }
    </style>
</head>
<body>
    <div class="admin-shell">
        <aside class="sidebar">
            <div>
                <div class="brand">
                    <div class="icon-box">📦</div>
                    <div>
                        <div class="name">SIMPALA</div>
                        <div class="subname">SMK NEGERI 5 SURAKARTA</div>
                    </div>
                </div>

                <nav>
                    <a href="{{ route('admin.dashboard') }}" class="{{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">📊 Ringkasan</a>
                    <a href="{{ route('admin.alat.index') }}" class="{{ request()->routeIs('admin.alat.*') ? 'active' : '' }}">🗄️ Data Alat</a>
                    <a href="{{ route('admin.kategori.index') }}" class="{{ request()->routeIs('admin.kategori.*') ? 'active' : '' }}">🏷️ Kategori</a>
                    <a href="{{ route('admin.verifikasi.index') }}" class="{{ request()->routeIs('admin.verifikasi.*') ? 'active' : '' }}">✅ Verifikasi</a>
                    <a href="{{ route('admin.pengembalian.index') }}" class="{{ request()->routeIs('admin.pengembalian.*') ? 'active' : '' }}">↩️ Pengembalian</a>
                    <a href="{{ route('admin.gantirugi.index') }}" class="{{ request()->routeIs('admin.gantirugi.*') ? 'active' : '' }}">💸 Ganti Rugi</a>
                    <a href="{{ route('admin.laporan.index') }}" class="{{ request()->routeIs('admin.laporan.*') ? 'active' : '' }}">📄 Laporan</a>
                    <a href="{{ route('admin.pengguna.index') }}" class="{{ request()->routeIs('admin.pengguna.*') ? 'active' : '' }}">👤 Pengguna</a>
                </nav>
            </div>

            <div>
                <!-- Widget Jam Real-Time Sidebar -->
                <div class="sidebar-clock-box">
                    <span class="clock-icon">🕒</span>
                    <div class="clock-details">
                        <div class="clock-time" id="admin-time">--:--:-- WIB</div>
                        <div class="clock-date" id="admin-date">Loading...</div>
                    </div>
                </div>

                <div class="user-box">
                    <a href="{{ route('profil.edit') }}" class="profile-link" title="Lihat & ubah profil">
                        @if (auth()->user()->fotoUrl())
                            <img src="{{ auth()->user()->fotoUrl() }}" class="avatar" alt="Foto profil">
                        @else
                            <div class="avatar">{{ auth()->user()->inisial() }}</div>
                        @endif
                        <div class="info">
                            <div class="name">{{ auth()->user()->name ?? 'Admin' }}</div>
                            <div class="role">Admin Sarpras</div>
                        </div>
                    </a>
                    <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
                        @csrf
                        <button type="submit" class="btn-logout" title="Keluar / Logout">
                            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path>
                                <polyline points="16 17 21 12 16 7"></polyline>
                                <line x1="21" y1="12" x2="9" y2="12"></line>
                            </svg>
                        </button>
                    </form>
                </div>
            </div>
        </aside>

        <main class="main">
           

            @yield('content')
        </main>
    </div>

    {{-- ===== MODAL KONFIRMASI — dipakai bareng oleh semua halaman (hapus, proses, dll) ===== --}}
    <div id="confirmModal" class="modal-overlay">
        <div class="modal-box">
            <div class="modal-title" id="confirmModalTitle">Konfirmasi</div>
            <div class="modal-message" id="confirmModalMessage">Apakah kamu yakin?</div>
            <div class="modal-actions">
                <button type="button" class="btn-outline" onclick="closeConfirmModal()">Batal</button>
                <button type="button" id="confirmModalOkBtn" class="btn-simpala">Ya, Lanjutkan</button>
            </div>
        </div>
    </div>

    @if(session('success'))
    <div x-data="{ show: true }" 
         x-init="setTimeout(() => show = false, 4000)" 
         x-show="show"
         style="position: fixed; top: 20px; right: 20px; z-index: 9999; background: #0f172a; color: #fff; padding: 1rem 1.5rem; border-radius: 12px; border-left: 4px solid #14b8a6; box-shadow: 0 10px 25px -5px rgba(0,0,0,0.3); display: flex; align-items: center; gap: 0.8rem; animation: slideIn 0.3s ease-out;">
        <span style="color: #2dd4bf; font-size: 1.2rem;">✓</span>
        <div>
            <div style="font-weight: 700; font-size: 0.85rem;">Berhasil!</div>
            <div style="font-size: 0.8rem; color: #94a3b8;">{{ session('success') }}</div>
        </div>
    </div>
@endif

    <script>
        // Real-Time Clock Function
        function updateAdminClock() {
            const now = new Date();
            
            const hariList = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
            const bulanList = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agt', 'Sep', 'Okt', 'Nov', 'Des'];

            const hari = hariList[now.getDay()];
            const tanggal = String(now.getDate()).padStart(2, '0');
            const bulan = bulanList[now.getMonth()];
            const tahun = now.getFullYear();

            const jam = String(now.getHours()).padStart(2, '0');
            const menit = String(now.getMinutes()).padStart(2, '0');
            const detik = String(now.getSeconds()).padStart(2, '0');

            const timeString = `${jam}:${menit}:${detik} WIB`;
            const dateString = `${hari}, ${tanggal} ${bulan} ${tahun}`;
            
            const timeEl = document.getElementById('admin-time');
            const dateEl = document.getElementById('admin-date');
            
            if (timeEl) timeEl.textContent = timeString;
            if (dateEl) dateEl.textContent = dateString;
        }

        setInterval(updateAdminClock, 1000);
        document.addEventListener('DOMContentLoaded', updateAdminClock);

        // Modal Confirmation Script
        let _confirmFormTarget = null;
        let _confirmFormAction = null;

        function showConfirm(formEl, title, message, okLabel = 'Ya, Lanjutkan', danger = false, formActionOverride = null) {
            _confirmFormTarget = formEl;
            _confirmFormAction = formActionOverride;
            document.getElementById('confirmModalTitle').textContent = title;
            document.getElementById('confirmModalMessage').textContent = message;

            const okBtn = document.getElementById('confirmModalOkBtn');
            okBtn.textContent = okLabel;
            okBtn.className = danger ? 'btn-danger-solid' : 'btn-simpala';

            document.getElementById('confirmModal').classList.add('show');
        }

        function closeConfirmModal() {
            document.getElementById('confirmModal').classList.remove('show');
            _confirmFormTarget = null;
            _confirmFormAction = null;
        }

        document.getElementById('confirmModalOkBtn').addEventListener('click', function () {
            if (_confirmFormTarget) {
                if (_confirmFormAction) {
                    _confirmFormTarget.action = _confirmFormAction;
                }
                _confirmFormTarget.submit();
            }
            closeConfirmModal();
        });

        document.getElementById('confirmModal').addEventListener('click', function (e) {
            if (e.target === this) closeConfirmModal();
        });
    </script>
</body>
</html>