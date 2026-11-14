<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= isset($page_title) ? html_escape($page_title) : 'Masuk ke Sistem — Manajemen Barang'; ?></title>
    <meta name="description" content="Portal Masuk Sistem Manajemen Barang & Pergudangan Terpadu">
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/favicon.ico'); ?>">

    <!-- Bootstrap 5.3 & Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- Design System Theme CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/theme.css?v=' . (file_exists(FCPATH . 'assets/css/theme.css') ? filemtime(FCPATH . 'assets/css/theme.css') : '2.8.3')); ?>">

    <!-- Anti-Flash Theme Script -->
    <script>
        // Inline early script to set theme without flash (Multi-Palette 6 Themes)
        (function() {
            var validThemes = ['light', 'dark', 'sepia', 'navy', 'oled', 'forest'];
            var savedTheme = localStorage.getItem('theme') || 'system';
            var effectiveTheme = savedTheme === 'system'
                ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
                : (validThemes.indexOf(savedTheme) !== -1 ? savedTheme : 'light');
            document.documentElement.setAttribute('data-bs-theme', effectiveTheme);
        })();
    </script>
</head>
<body>
    <div class="auth-wrapper">
        <!-- 1. Left Showcase / Brand Experience Panel (Desktop) -->
        <div class="auth-showcase-panel">
            <div class="auth-showcase-grid-bg"></div>

            <!-- Showcase Header -->
            <div class="auth-showcase-header">
                <a href="<?= site_url('login'); ?>" class="auth-showcase-logo">
                    <div class="auth-showcase-logo-icon">
                        <i class="bi bi-box-seam-fill"></i>
                    </div>
                    <div>
                        <div class="auth-showcase-logo-text">Manajemen<span class="brand-accent">Barang</span></div>
                        <div class="small" style="color: var(--mb-text-muted); font-size: 0.7rem; font-family: var(--mb-font-mono);">Warehouse Pro Edition · v2.6</div>
                    </div>
                </a>
            </div>

            <!-- Showcase Content -->
            <div class="auth-showcase-content">
                <div class="auth-showcase-badge">
                    <i class="bi bi-shield-check"></i> Enterprise Inventory Core
                </div>
                <h1 class="auth-showcase-title">
                    Akurasi Mutasi &amp; Kontrol Logistik Terpadu.
                </h1>
                <p class="auth-showcase-desc">
                    Platform pergudangan terstandarisasi untuk pemantauan arus persediaan, batch kadaluarsa FEFO, dan eksekusi pengeluaran stok berbasis FIFO.
                </p>

                <!-- Value Proposition Highlights -->
                <div class="auth-feature-list">
                    <div class="auth-feature-item">
                        <div class="auth-feature-icon">
                            <i class="bi bi-layers"></i>
                        </div>
                        <div class="auth-feature-text">
                            <h6>Rekomendasi Cerdas FIFO &amp; FEFO</h6>
                            <p>Otomasi peringatan lot batch mendekati masa tenggang kadaluarsa dan alokasi stok tertua.</p>
                        </div>
                    </div>

                    <div class="auth-feature-item">
                        <div class="auth-feature-icon">
                            <i class="bi bi-qr-code-scan"></i>
                        </div>
                        <div class="auth-feature-text">
                            <h6>Pemindai QR &amp; Audit Trail Presisi</h6>
                            <p>Registrasi barang masuk dan keluar dengan verifikasi barcode fisik serta pencatatan identitas operator.</p>
                        </div>
                    </div>

                    <div class="auth-feature-item">
                        <div class="auth-feature-icon">
                            <i class="bi bi-activity"></i>
                        </div>
                        <div class="auth-feature-text">
                            <h6>Silent Background Polling (5m)</h6>
                            <p>Sinkronisasi data multi-perangkat tanpa jeda interupsi dan tanpa kedipan layar (Zero CLS).</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Showcase Footer Metrics -->
            <div class="auth-showcase-footer">
                <div class="auth-stat-item">
                    <span class="auth-stat-val">99.98%</span>
                    <span class="auth-stat-lbl">Reliabilitas Sistem</span>
                </div>
                <div class="auth-stat-item">
                    <span class="auth-stat-val">&lt; 0.08s</span>
                    <span class="auth-stat-lbl">Latensi Respons</span>
                </div>
                <div class="auth-stat-item">
                    <span class="auth-stat-val">SHA-256</span>
                    <span class="auth-stat-lbl">CSRF Proteksi</span>
                </div>
            </div>
        </div>

        <!-- 2. Right Form Panel -->
        <div class="auth-form-panel">
            <!-- Top Controls: Status & Theme Toggle -->
            <div class="auth-top-bar">
                <div class="auth-status-pill">
                    <span class="live-dot" style="width: 6px; height: 6px;"></span>
                    <span class="tabular-nums" style="font-size: 0.72rem;">Sistem Online</span>
                </div>
                <div class="dropdown">
                    <button type="button" class="btn-icon-quiet" id="theme-toggle-btn"
                            data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="true"
                            title="Pilih Tema Warna Sistem" aria-label="Ganti Tema">
                        <i class="bi bi-sun-fill fs-5 text-warning" id="theme-toggle-icon"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end shadow theme-palette-menu" aria-labelledby="theme-toggle-btn">
                        <li class="dropdown-header px-2.5 py-1 text-uppercase fw-bold" style="font-size: 0.68rem; letter-spacing: 0.5px; color: var(--mb-text-muted);">
                            <i class="bi bi-palette me-1"></i> Palet Tema Sistem
                        </li>
                        <li><hr class="dropdown-divider my-1 opacity-25"></li>
                        <li>
                            <button type="button" class="dropdown-item theme-dropdown-option" data-theme-val="light">
                                <div class="d-flex align-items-center gap-2.5">
                                    <span class="theme-swatch" style="background: #ffffff; border: 2px solid #cbd5e1;"></span>
                                    <span>Mode Terang (Clean)</span>
                                </div>
                                <i class="bi bi-check2 text-danger fs-5 d-none theme-check-icon"></i>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item theme-dropdown-option" data-theme-val="dark">
                                <div class="d-flex align-items-center gap-2.5">
                                    <span class="theme-swatch" style="background: #090d16; border: 2px solid #334155;"></span>
                                    <span>Mode Gelap (Slate)</span>
                                </div>
                                <i class="bi bi-check2 text-danger fs-5 d-none theme-check-icon"></i>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item theme-dropdown-option" data-theme-val="sepia">
                                <div class="d-flex align-items-center gap-2.5">
                                    <span class="theme-swatch" style="background: #f6f1e8; border: 2px solid #d5c7b3;"></span>
                                    <span>Kertas Hangat (Sepia)</span>
                                </div>
                                <i class="bi bi-check2 text-danger fs-5 d-none theme-check-icon"></i>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item theme-dropdown-option" data-theme-val="navy">
                                <div class="d-flex align-items-center gap-2.5">
                                    <span class="theme-swatch" style="background: #0b1329; border: 2px solid #38bdf8;"></span>
                                    <span>Midnight Navy (Blue)</span>
                                </div>
                                <i class="bi bi-check2 text-danger fs-5 d-none theme-check-icon"></i>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item theme-dropdown-option" data-theme-val="oled">
                                <div class="d-flex align-items-center gap-2.5">
                                    <span class="theme-swatch" style="background: #000000; border: 2px solid #52525b;"></span>
                                    <span>OLED Pure Black</span>
                                </div>
                                <i class="bi bi-check2 text-danger fs-5 d-none theme-check-icon"></i>
                            </button>
                        </li>
                        <li>
                            <button type="button" class="dropdown-item theme-dropdown-option" data-theme-val="forest">
                                <div class="d-flex align-items-center gap-2.5">
                                    <span class="theme-swatch" style="background: #071712; border: 2px solid #34d399;"></span>
                                    <span>Forest Emerald (Sage)</span>
                                </div>
                                <i class="bi bi-check2 text-danger fs-5 d-none theme-check-icon"></i>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Main Form Card Container -->
            <div class="auth-form-container">
                <!-- Mobile Only Brand Header -->
                <div class="auth-mobile-brand">
                    <div class="d-inline-flex align-items-center justify-content-center text-white rounded-3 shadow mb-2"
                         style="width: 44px; height: 44px; background: linear-gradient(135deg, var(--mb-brand), var(--mb-brand-hover));">
                        <i class="bi bi-box-seam-fill fs-4"></i>
                    </div>
                    <h4 class="fw-bold tracking-tight mb-0" style="color: var(--mb-text-primary);">Manajemen<span class="brand-accent">Barang</span></h4>
                    <p class="text-muted small">Warehouse Inventory Platform v2.6</p>
                </div>

                <!-- Form Header -->
                <div class="auth-form-header">
                    <h2>Masuk ke Sistem</h2>
                    <p>Masukkan kredensial administrator Anda untuk membuka portal gudang.</p>
                </div>

                <!-- Flash Notifications -->
                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger py-2.5 px-3 small rounded-3 d-flex align-items-center gap-2 mb-3" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-5 flex-shrink-0"></i>
                        <div class="fw-medium"><?= html_escape($this->session->flashdata('error')); ?></div>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success py-2.5 px-3 small rounded-3 d-flex align-items-center gap-2 mb-3" role="alert">
                        <i class="bi bi-check-circle-fill fs-5 flex-shrink-0"></i>
                        <div class="fw-medium"><?= html_escape($this->session->flashdata('success')); ?></div>
                    </div>
                <?php endif; ?>

                <!-- One-Click Demo Credentials Chip -->
                <div class="auth-demo-banner" id="btn-quick-fill" title="Klik untuk mengisi akun demo otomatis">
                    <div class="auth-demo-info">
                        <div class="auth-demo-icon">
                            <i class="bi bi-key-fill"></i>
                        </div>
                        <div class="auth-demo-text">
                            <strong>Akun Demo Administrator</strong>
                            <span>admin@admin.com · password</span>
                        </div>
                    </div>
                    <span class="auth-demo-badge">1-Klik Isi</span>
                </div>

                <!-- Authentication Form -->
                <form action="<?= site_url('login'); ?>" method="POST" id="loginForm" autocomplete="on">
                    <input type="hidden" name="<?= $csrf_token_name; ?>" value="<?= $csrf_hash; ?>">

                    <!-- Email Input -->
                    <div class="auth-input-group">
                        <label for="email">Alamat Email Pengguna</label>
                        <div class="auth-input-wrapper">
                            <span class="auth-input-prefix"><i class="bi bi-envelope"></i></span>
                            <input type="email" class="auth-input" id="email" name="email"
                                   placeholder="nama@perusahaan.com" required autofocus
                                   value="<?= html_escape($this->input->post('email') ?? ''); ?>">
                        </div>
                    </div>

                    <!-- Password Input -->
                    <div class="auth-input-group">
                        <div class="d-flex align-items-center justify-content-between mb-1.5">
                            <label for="password" class="mb-0">Kata Sandi</label>
                            <span class="text-muted" style="font-size: 0.72rem;">Min. 6 Karakter</span>
                        </div>
                        <div class="auth-input-wrapper">
                            <span class="auth-input-prefix"><i class="bi bi-shield-lock"></i></span>
                            <input type="password" class="auth-input" id="password" name="password"
                                   placeholder="••••••••" required>
                            <button type="button" class="auth-input-suffix" id="togglePasswordBtn" aria-label="Lihat kata sandi">
                                <i class="bi bi-eye" id="togglePasswordIcon"></i>
                            </button>
                        </div>
                    </div>

                    <!-- Remember Me Option -->
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="form-check m-0">
                            <input class="form-check-input" type="checkbox" id="rememberMe" name="remember" checked>
                            <label class="form-check-label text-muted small" for="rememberMe">
                                Ingat sesi login saya
                            </label>
                        </div>
                        <span class="text-muted small">Default: Admin</span>
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="auth-btn-primary" id="submitBtn">
                        <span id="btnText">Otentikasi &amp; Masuk</span>
                        <i class="bi bi-arrow-right" id="btnIcon"></i>
                    </button>
                </form>
            </div>

            <!-- Form Footer -->
            <div class="auth-footer">
                <div>
                    <span>&copy; <?= date('Y'); ?> <strong>Manajemen Barang</strong> · v2.6</span>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <i class="bi bi-lock-fill text-success"></i>
                    <span>Sesi Terenkripsi</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Password Visibility Toggle
        const toggleBtn = document.getElementById('togglePasswordBtn');
        const passInput = document.getElementById('password');
        const toggleIcon = document.getElementById('togglePasswordIcon');

        if (toggleBtn && passInput) {
            toggleBtn.addEventListener('click', () => {
                const isPassword = passInput.getAttribute('type') === 'password';
                passInput.setAttribute('type', isPassword ? 'text' : 'password');
                toggleIcon.className = isPassword ? 'bi bi-eye-slash text-danger' : 'bi bi-eye';
            });
        }

        // Quick Fill Demo Credentials
        const quickFillBtn = document.getElementById('btn-quick-fill');
        const emailInput = document.getElementById('email');

        if (quickFillBtn) {
            quickFillBtn.addEventListener('click', () => {
                if (emailInput) emailInput.value = 'admin@admin.com';
                if (passInput) passInput.value = 'password';

                // Visual flash feedback
                quickFillBtn.style.transform = 'scale(0.98)';
                setTimeout(() => {
                    quickFillBtn.style.transform = '';
                    if (passInput) passInput.focus();
                }, 120);
            });
        }

        // Clear tab auth token on login page
        sessionStorage.removeItem('mb_tab_auth');

        // Form Submit Loading State
        const loginForm = document.getElementById('loginForm');
        const submitBtn = document.getElementById('submitBtn');
        const btnText = document.getElementById('btnText');
        const btnIcon = document.getElementById('btnIcon');

        if (loginForm && submitBtn) {
            loginForm.addEventListener('submit', () => {
                sessionStorage.setItem('mb_tab_auth', '1');
                submitBtn.disabled = true;
                submitBtn.style.opacity = '0.85';
                btnText.textContent = 'Memverifikasi Kredensial...';
                btnIcon.className = 'spinner-border spinner-border-sm';
            });
        }

        // Theme Switcher Sync with localStorage (6 Themes Palette)
        const THEME_ICONS = {
            light: 'bi bi-sun-fill fs-5 text-warning',
            dark: 'bi bi-moon-stars-fill fs-5 text-warning',
            sepia: 'bi bi-cup-hot-fill fs-5 text-warning',
            navy: 'bi bi-compass-fill fs-5 text-info',
            oled: 'bi bi-circle-fill fs-5 text-light',
            forest: 'bi bi-tree-fill fs-5 text-success'
        };

        function applyTheme(theme) {
            const effective = THEME_ICONS[theme] ? theme : 'light';
            document.documentElement.setAttribute('data-bs-theme', effective);
            localStorage.setItem('theme', effective);

            const iconEl = document.getElementById('theme-toggle-icon');
            if (iconEl && THEME_ICONS[effective]) {
                iconEl.className = THEME_ICONS[effective];
            }

            document.querySelectorAll('.theme-dropdown-option').forEach(opt => {
                const isMatch = opt.getAttribute('data-theme-val') === effective;
                opt.classList.toggle('active', isMatch);
                const check = opt.querySelector('.theme-check-icon');
                if (check) check.classList.toggle('d-none', !isMatch);
            });
        }

        const initialTheme = localStorage.getItem('theme') || document.documentElement.getAttribute('data-bs-theme') || 'light';
        applyTheme(initialTheme);

        document.querySelectorAll('.theme-dropdown-option').forEach(opt => {
            opt.addEventListener('click', (e) => {
                e.preventDefault();
                const chosen = opt.getAttribute('data-theme-val');
                if (chosen) applyTheme(chosen);
            });
        });
    </script>
</body>
</html>
