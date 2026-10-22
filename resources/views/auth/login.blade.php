<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SIMPALA</title>
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            font-family: -apple-system, 'Segoe UI', Roboto, Arial, sans-serif;
            overflow-x: hidden;
            background: #ffffff;
        }

        /* ===== OVERLAY INTRO SPLASH SCREEN ===== */
        #intro-splash {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100vh;
            background: linear-gradient(135deg, #0f172a 0%, #16213e 60%, #1f6f82 100%);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 9999;
            animation: fadeOutSplash 0.5s ease 1.8s forwards;
        }

        .splash-content {
            text-align: center;
            color: white;
            animation: zoomInSplash 0.7s cubic-bezier(0.16, 1, 0.3, 1);
        }

        .splash-icon {
            width: 64px;
            height: 64px;
            background: white;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 32px;
            margin: 0 auto 1.2rem auto;
            box-shadow: 0 10px 25px rgba(0,0,0,0.25);
            animation: pulseIcon 1.5s infinite ease-in-out;
        }

        .splash-title {
            font-size: 2.2rem;
            font-weight: 800;
            letter-spacing: 0.06em;
            color: #ffffff;
            line-height: 1.1;
        }

        .splash-sub {
            font-size: 0.8rem;
            color: #2dd4bf;
            letter-spacing: 0.15em;
            font-weight: 700;
            margin-top: 0.4rem;
            margin-bottom: 1.8rem;
            text-transform: uppercase;
        }

        .splash-loader {
            width: 140px;
            height: 4px;
            background: rgba(255,255,255,0.15);
            margin: 0 auto;
            border-radius: 999px;
            overflow: hidden;
            position: relative;
        }

        .splash-loader::after {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: #14b8a6;
            animation: loadingBar 1.5s ease-in-out;
        }

        @keyframes zoomInSplash {
            from { opacity: 0; transform: scale(0.85); }
            to { opacity: 1; transform: scale(1); }
        }

        @keyframes pulseIcon {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.08); }
        }

        @keyframes loadingBar {
            from { left: -100%; }
            to { left: 100%; }
        }

        @keyframes fadeOutSplash {
            to { opacity: 0; visibility: hidden; pointer-events: none; }
        }

        /* ===== ANIMASI INTERAKTIF & LOGO BERGERAK ===== */
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        @keyframes floatGlow {
            0%, 100% { transform: translateY(0px) scale(1); }
            50% { transform: translateY(-12px) scale(1.06); }
        }
        @keyframes floatBoxes {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-18px) rotate(4deg); }
        }
        @keyframes floatLogo {
            0%, 100% { transform: translateY(0px) rotate(0deg); }
            50% { transform: translateY(-6px) rotate(-3deg); }
        }
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .animate-fade-up {
            animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards;
            opacity: 0;
        }

        .moving-logo {
            animation: floatLogo 4s ease-in-out infinite;
        }

        .glow-orb {
            position: absolute;
            width: 280px;
            height: 280px;
            background: rgba(31, 111, 130, 0.3);
            filter: blur(75px);
            border-radius: 50%;
            pointer-events: none;
            animation: floatGlow 7s ease-in-out infinite;
        }

        /* Kotak-kotak Transparan Samar di Sisi Kanan */
        .floating-box {
            position: absolute;
            border: 2px solid rgba(31, 111, 130, 0.08);
            background: rgba(31, 111, 130, 0.02);
            border-radius: 16px;
            pointer-events: none;
            animation: floatBoxes 8s ease-in-out infinite;
        }

        /* ===== MAIN WRAPPER ===== */
        .wrapper {
            display: flex;
            min-height: 100vh;
        }

        /* ===== PANEL KIRI (branding, navy dengan gradasi bergerak) ===== */
        .brand-panel {
            background: linear-gradient(135deg, #0f172a, #16213e, #1f6f82, #0f766e);
            background-size: 300% 300%;
            animation: gradientShift 14s ease infinite;
            color: white;
            width: 50%;
            padding: 3rem 4rem;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            position: relative;
            overflow: hidden;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            position: relative;
            z-index: 2;
        }
        .brand-logo .icon-box {
            width: 42px;
            height: 42px;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        }
        .brand-logo .name { font-weight: 700; font-size: 1.1rem; line-height: 1.2; }
        .brand-logo .subname { font-size: 0.7rem; color: #9aa7c7; letter-spacing: 0.03em; }

        .brand-middle { max-width: 480px; position: relative; z-index: 2; }
        .badge-pill {
            display: inline-block;
            background: rgba(56, 189, 210, 0.18);
            color: #7fd4e8;
            font-size: 0.75rem;
            font-weight: 600;
            padding: 0.35rem 0.9rem;
            border-radius: 999px;
            margin-bottom: 1.5rem;
            border: 1px solid rgba(127, 212, 232, 0.3);
        }
        .brand-middle h1 {
            font-size: 2.3rem;
            font-weight: 700;
            line-height: 1.25;
            margin-bottom: 1.2rem;
            letter-spacing: -0.02em;
        }
        .brand-middle p {
            color: #b7c0d8;
            font-size: 1rem;
            line-height: 1.6;
        }

        .brand-footer {
            color: #7c88a8;
            font-size: 0.8rem;
            position: relative;
            z-index: 2;
        }

        /* ===== PANEL KANAN (form login dengan highlight card) ===== */
        .form-panel {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #ffffff;
            padding: 2rem;
            position: relative;
            overflow: hidden;
        }

        .login-card {
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 2;
            background: #ffffff;
            padding: 2.5rem;
            border-radius: 20px;
            border: 1px solid #e2e8f0;
            box-shadow: 0 15px 35px -5px rgba(22, 33, 62, 0.07), 0 5px 15px rgba(0, 0, 0, 0.03);
            position: relative;
            overflow: hidden;
        }

        /* Garis highlight gradasi di atas card */
        .login-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #16213e, #1f6f82, #14b8a6);
        }

        .login-card h2 {
            font-size: 1.6rem;
            font-weight: 700;
            color: #16213e;
            margin-bottom: 0.4rem;
            letter-spacing: -0.02em;
        }
        .login-card > p {
            color: #6b7280;
            font-size: 0.9rem;
            margin-bottom: 1.8rem;
        }

        .form-group { margin-bottom: 1.2rem; }
        .form-group label {
            display: block;
            font-size: 0.85rem;
            font-weight: 600;
            color: #1f2937;
            margin-bottom: 0.4rem;
        }
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 0.7rem 0.95rem;
            border: 1px solid #d7dce3;
            border-radius: 9px;
            font-size: 0.95rem;
            outline: none;
            background: #f8fafc;
            color: #0f172a;
            transition: all 0.25s ease-in-out;
        }
        .form-group input:focus {
            border-color: #1f6f82;
            background: #ffffff;
            box-shadow: 0 0 0 4px rgba(31, 111, 130, 0.12);
            transform: translateY(-1px);
        }

        .row-between {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.3rem;
            font-size: 0.85rem;
        }
        .row-between label { display: flex; align-items: center; gap: 0.4rem; color: #4b5563; cursor: pointer; }
        .row-between input[type="checkbox"] { accent-color: #1f6f82; width: 15px; height: 15px; cursor: pointer; }
        .row-between a { color: #1f6f82; text-decoration: none; font-weight: 600; cursor: pointer; }
        .row-between a:hover { text-decoration: underline; }

        .btn-submit {
            width: 100%;
            background: linear-gradient(135deg, #16213e 0%, #1f6f82 100%);
            color: white;
            border: none;
            padding: 0.8rem;
            border-radius: 9px;
            font-weight: 700;
            font-size: 0.95rem;
            cursor: pointer;
            transition: all 0.25s ease-in-out;
            box-shadow: 0 4px 12px rgba(31, 111, 130, 0.2);
        }
        .btn-submit:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 18px rgba(31, 111, 130, 0.35);
        }

        .help-text {
            text-align: center;
            font-size: 0.8rem;
            color: #9ca3af;
            margin-top: 1.2rem;
        }

        .alert-error {
            background: #fdecea;
            color: #b42318;
            border: 1px solid #f5c2c0;
            padding: 0.6rem 0.9rem;
            border-radius: 8px;
            font-size: 0.85rem;
            margin-bottom: 1.2rem;
            animation: fadeInUp 0.4s ease;
        }

        /* ===== MODAL POP-UP LUPA KATA SANDI ===== */
        .modal-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(22, 33, 62, 0.6);
            backdrop-filter: blur(4px);
            align-items: center;
            justify-content: center;
            z-index: 1000;
            padding: 1rem;
            animation: fadeInUp 0.3s ease;
        }
        .modal-box {
            background: white;
            border-radius: 14px;
            padding: 1.8rem;
            width: 100%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 15px 35px rgba(0,0,0,0.2);
        }

        /* ===== RESPONSIVE ===== */
        @media (max-width: 900px) {
            .wrapper { flex-direction: column; }
            .brand-panel, .form-panel { width: 100%; }
            .brand-panel { padding: 2rem; min-height: 260px; }
            .brand-middle h1 { font-size: 1.6rem; }
            .brand-footer { display: none; }
            .floating-box { display: none; }
            .login-card { padding: 1.5rem; box-shadow: none; border: none; }
        }
    </style>
</head>
<body>

    <!-- ===== INTRO SPLASH SCREEN ANIMATION ===== -->
    <div id="intro-splash">
        <div class="splash-content">
            <div class="splash-icon moving-logo">📦</div>
            <div class="splash-title">SIMPALA</div>
            <div class="splash-sub">SMK NEGERI 5 SURAKARTA</div>
            <div class="splash-loader"></div>
        </div>
    </div>

    <div class="wrapper">
        {{-- PANEL KIRI: branding dengan animasi gradasi & cahaya --}}
        <div class="brand-panel">
            <div class="glow-orb" style="top: 10%; left: 10%;"></div>
            <div class="glow-orb" style="bottom: 10%; right: 10%; background: rgba(20, 184, 166, 0.2); animation-delay: -3.5s;"></div>

            <div class="brand-logo animate-fade-up">
                <div class="icon-box moving-logo">📦</div>
                <div>
                    <div class="name">SIMPALA</div>
                    <div class="subname">SMK NEGERI 5 SURAKARTA</div>
                </div>
            </div>

            <div class="brand-middle animate-fade-up" style="animation-delay: 0.2s;">
                <span class="badge-pill">Sistem Sarana Prasarana</span>
                <h1>Pinjam alat sekolah lebih mudah, tertib, dan transparan.</h1>
                <p>Kelola inventaris, ajukan peminjaman, dan pantau status dalam satu sistem yang terintegrasi.</p>
            </div>

            <div class="brand-footer animate-fade-up" style="animation-delay: 0.4s;">© 2026 SMK Negeri 5 Surakarta</div>
        </div>

        {{-- PANEL KANAN: form login dengan highlight card & background kotak transparan --}}
        <div class="form-panel">
            <!-- Elemen kotak-kotak transparan samar yang melayang pelan -->
            <div class="floating-box" style="width: 140px; height: 140px; top: 8%; right: 10%; animation-duration: 9s;"></div>
            <div class="floating-box" style="width: 220px; height: 220px; bottom: 5%; left: 8%; animation-duration: 12s; animation-delay: -2s;"></div>
            <div class="floating-box" style="width: 80px; height: 80px; top: 45%; right: 18%; animation-duration: 7s; animation-delay: -4s;"></div>

            <div class="login-card animate-fade-up" style="animation-delay: 0.15s;">
                <h2>Selamat datang</h2>
                <p>Masuk menggunakan akun SIMPALA Anda.</p>

                @if ($errors->any())
                    <div class="alert-error">{{ $errors->first() }}</div>
                @endif

                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <div class="form-group">
                        <label>NIS / NIP</label>
                        <input type="text" name="nis_nip" value="{{ old('nis_nip') }}" placeholder="Masukkan NIS atau NIP" required autofocus>
                    </div>

                    <div class="form-group">
                        <label>Kata sandi</label>
                        <input type="password" name="password" placeholder="••••••••" required>
                    </div>

                    <div class="row-between">
                        <label><input type="checkbox" name="remember"> Ingat saya</label>
                        <a href="javascript:void(0)" onclick="openResetInfoModal()">Lupa kata sandi?</a>
                    </div>

                    <button type="submit" class="btn-submit">Masuk ke SIMPALA</button>
                </form>

                <div class="help-text">Butuh bantuan? Hubungi petugas sarpras.</div>
            </div>
        </div>
    </div>

    <!-- ===== MODAL INFO LUPA KATA SANDI ===== -->
    <div id="resetInfoModal" class="modal-overlay">
        <div class="modal-box">
            <div style="font-size: 2.2rem; margin-bottom: 0.5rem;">🔑</div>
            <h3 style="font-size: 1.1rem; font-weight: 700; color: #16213e; margin-bottom: 0.6rem;">Lupa Kata Sandi?</h3>
            <p style="font-size: 0.88rem; color: #6b7280; line-height: 1.5; margin-bottom: 1.2rem;">
                Untuk alasan keamanan, pemulihan kata sandi dilakukan secara terpusat. Silakan hubungi <strong>Petugas Sarpras / Admin SIMPALA</strong> di sekolah untuk me-reset kata sandi Anda.
            </p>

            <div style="background: #f4f6fb; border: 1px solid #e5e9f0; padding: 0.82rem 1rem; border-radius: 10px; font-size: 0.8rem; color: #4b5563; margin-bottom: 1.4rem; text-align: left;">
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    <span>📍</span> <span><strong>Lokasi:</strong> Ruang Sarpras / Ruang Lab RPL</span>
                </div>
                <div style="display: flex; align-items: center; gap: 0.5rem; margin-top: 0.4rem;">
                    <span>🕒</span> <span><strong>Jam Pelayanan:</strong> 07.00 - 15.00 WIB</span>
                </div>
            </div>

            <div style="display: flex; gap: 0.6rem;">
                <button type="button" onclick="closeResetInfoModal()" 
                        style="flex: 1; background: #ffffff; border: 1px solid #d7dce3; color: #4b5563; padding: 0.7rem; border-radius: 8px; font-weight: 600; font-size: 0.88rem; cursor: pointer; transition: all 0.2s;">
                    Tutup
                </button>
                <a href="https://wa.me/6288238398799?text=Halo%20Admin%20SIMPALA,%20saya%20lupa%20password%20akun%20saya.%20Mohon%20bantuannya." 
                   target="_blank" 
                   style="flex: 1.4; background: #1f6f82; color: #ffffff; text-decoration: none; padding: 0.7rem; border-radius: 8px; font-weight: 600; font-size: 0.88rem; display: inline-flex; align-items: center; justify-content: center; gap: 0.4rem; transition: background 0.2s;"
                   onmouseover="this.style.background='#185a68'" 
                   onmouseout="this.style.background='#1f6f82'">
                    Hubungi Admin
                </a>
            </div>
        </div>
    </div>

    {{-- ===== JAVASCRIPT LOGIC ===== --}}
    <script>
        function openResetInfoModal() {
            document.getElementById('resetInfoModal').style.display = 'flex';
        }

        function closeResetInfoModal() {
            document.getElementById('resetInfoModal').style.display = 'none';
        }

        document.getElementById('resetInfoModal')?.addEventListener('click', function(e) {
            if (e.target === this) closeResetInfoModal();
        });
    </script>
</body>
</html>