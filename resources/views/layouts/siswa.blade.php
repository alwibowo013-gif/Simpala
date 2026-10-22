<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'SIMPALA')</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: -apple-system, 'Segoe UI', Roboto, Arial, sans-serif; background: #f4f6fb; color: #1f2937; }

        .topbar {
            background: white; border-bottom: 1px solid #e5e9f0;
            display: flex; align-items: center; justify-content: space-between;
            padding: 0.9rem 2rem;
        }
        .topbar .brand { display: flex; align-items: center; gap: 0.6rem; }
        .topbar .brand .icon-box {
            width: 34px; height: 34px; background: #1f6f82; border-radius: 8px;
            display: flex; align-items: center; justify-content: center; font-size: 16px;
        }
        .topbar .brand .name { font-weight: 700; font-size: 0.95rem; color: #16213e; line-height:1.1; }
        .topbar .brand .subname { font-size: 0.62rem; color: #9aa3b5; }

        .topbar nav { display: flex; gap: 1.8rem; }
        .topbar nav a { color: #6b7280; text-decoration: none; font-size: 0.9rem; font-weight: 500; }
        .topbar nav a.active { color: #1f6f82; font-weight: 700; }

        .topbar .right { display: flex; align-items: center; gap: 0.8rem; }
        
        /* Widget Jam Realtime Topbar */
        .topbar-clock {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            padding: 0.35rem 0.75rem;
            border-radius: 8px;
            font-size: 0.78rem;
            color: #475569;
            font-weight: 600;
            display: flex;
            align-items: center;
            gap: 0.4rem;
        }

        .topbar .right .avatar {
            width: 32px; height: 32px; border-radius: 50%; background: #dbe6f5;
            display: flex; align-items: center; justify-content: center; font-weight: 700; font-size: 0.8rem; color: #1f6f82;
        }
        .topbar .right .profile-link {
            display: flex; align-items: center; gap: 0.5rem; color: inherit; text-decoration: none;
            padding: 0.2rem 0.5rem; border-radius: 8px; transition: background 0.2s;
        }
        .topbar .right .profile-link:hover {
            background: #f3f4f6;
        }
        .topbar .right img.avatar { object-fit: cover; }
        
        /* Styling Tombol Logout Topbar */
        .topbar .right .btn-logout {
            background: none;
            border: none;
            color: #9ca3af;
            cursor: pointer;
            padding: 6px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
        }
        .topbar .right .btn-logout:hover {
            color: #dc2626;
            background-color: #fee2e2;
        }

        .container { max-width: 100%; margin: 0 auto; padding: 2rem 3rem; }
        
        .alert-success {
            background: #e3f6ea; color: #1f9254; border: 1px solid #b9e8ca;
            padding: 0.7rem 1rem; border-radius: 8px; font-size: 0.88rem; margin-bottom: 1.2rem;
        }
        .alert-error {
            background: #fdecea; color: #b42318; border: 1px solid #f5c2c0;
            padding: 0.7rem 1rem; border-radius: 8px; font-size: 0.88rem; margin-bottom: 1.2rem;
        }

        .card-panel { background: white; border-radius: 12px; border: 1px solid #e5e9f0; padding: 1.3rem; }
        .card-panel-title { font-size: 1rem; font-weight: 700; color: #16213e; margin-bottom: 1rem; }
        .page-title h1 { font-size: 1.4rem; font-weight: 700; color: #16213e; margin-bottom: 0.2rem; }
        .page-title p { color: #6b7280; font-size: 0.9rem; }

        .btn-simpala {
            background: #1f6f82; color: white; border: none; padding: 0.65rem 1.15rem;
            border-radius: 8px; font-weight: 600; font-size: 0.85rem; cursor: pointer;
            text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem;
        }
        .btn-simpala:hover { background: #185a68; color: white; }
        .btn-outline { background: white; border: 1px solid #d7dce3; color: #374151; padding: 0.6rem 1rem;
            border-radius: 8px; font-size: 0.85rem; cursor: pointer; text-decoration: none; display: inline-flex; align-items: center; gap: 0.4rem; }

        .badge-status { padding: 0.28rem 0.7rem; border-radius: 999px; font-size: 0.75rem; font-weight: 600; display: inline-block; }
        .badge-pending { background: #fdf3d8; color: #a1710b; }
        .badge-approved { background: #e3f6ea; color: #1f9254; }
        .badge-rejected { background: #fdecea; color: #b42318; }
        .badge-returned { background: #e6edf7; color: #3856a8; }

        .form-group { margin-bottom: 1.1rem; }
        .form-group label { display: block; font-size: 0.85rem; font-weight: 600; color: #1f2937; margin-bottom: 0.4rem; }
        .form-control { width: 100%; padding: 0.6rem 0.85rem; border: 1px solid #d7dce3; border-radius: 8px; font-size: 0.9rem; }
        .form-control:focus { outline: none; border-color: #1f6f82; }
        .form-error { font-size: 0.78rem; color: #b42318; margin-top: 0.3rem; }
        .form-hint { font-size: 0.78rem; color: #9ca3af; }

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

        @media (max-width: 760px) {
            .topbar nav { display: none; }
            .topbar-clock { display: none; }
            .container { padding: 1.2rem; }
        }
    </style>
</head>
<body>
    <div class="topbar">
        <div class="brand">
            <div class="icon-box">📦</div>
            <div>
                <div class="name">SIMPALA</div>
                <div class="subname">SMK NEGERI 5 SURAKARTA</div>
            </div>
        </div>

        <nav>
            <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Beranda</a>
            <a href="{{ route('jelajah') }}" class="{{ request()->routeIs('jelajah') ? 'active' : '' }}">Jelajah Alat</a>
            <a href="{{ route('pinjam.saya') }}" class="{{ request()->routeIs('pinjam.*') ? 'active' : '' }}">Peminjaman Saya</a>
        </nav>

        <div class="right">
            <!-- Widget Jam Real-Time -->
            <div class="topbar-clock">
                🕒 <span id="realtime-clock">--:--:-- WIB</span>
            </div>

            <a href="{{ route('profil.edit') }}" class="profile-link" title="Lihat & ubah profil">
                @if (auth()->user()->fotoUrl())
                    <img src="{{ auth()->user()->fotoUrl() }}" class="avatar" alt="Foto profil">
                @else
                    <span class="avatar">{{ auth()->user()->inisial() }}</span>
                @endif
                <span style="font-size:0.88rem; font-weight:600;">{{ auth()->user()->name }}</span>
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

    <div class="container">
        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if (session('error'))
            <div class="alert-error">{{ session('error') }}</div>
        @endif

        @yield('content')
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

    <script>
        // Real-Time Clock Function
        function updateClock() {
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

            const clockString = `${hari}, ${tanggal} ${bulan} ${tahun} • ${jam}:${menit}:${detik} WIB`;
            
            const clockEl = document.getElementById('realtime-clock');
            if (clockEl) {
                clockEl.textContent = clockString;
            }
        }

        setInterval(updateClock, 1000);
        document.addEventListener('DOMContentLoaded', updateClock);

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