<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?= isset($page_title) ? html_escape($page_title) . ' — Manajemen Barang' : 'Manajemen Barang Gudang'; ?></title>
    <meta name="description" content="Sistem Manajemen Barang dan Inventaris Gudang Real-Time">
    <link rel="icon" type="image/x-icon" href="<?= base_url('assets/img/favicon.ico'); ?>">

    <!-- Bootstrap 5.3 & Icons CDN -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    <!-- SweetAlert2 -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.min.css">

    <!-- Design System Theme CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/theme.css'); ?>">

    <script>
        // Tab-scoped session guard: if opened in a fresh tab without login, force redirect to logout
        if (!sessionStorage.getItem('mb_tab_auth')) {
            window.location.replace('<?= site_url("logout"); ?>');
        }

        // Inline early script to set theme without flash
        (function() {
            var savedTheme = localStorage.getItem('theme') || 'system';
            var effectiveTheme = savedTheme === 'system'
                ? (window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light')
                : savedTheme;
            document.documentElement.setAttribute('data-bs-theme', effectiveTheme);
        })();
        window.BASE_URL = '<?= rtrim(base_url(), '/') . '/'; ?>';
        window.CSRF_TOKEN_NAME = '<?= $csrf_token_name ?? 'csrf_token'; ?>';
        window.CSRF_HASH = '<?= $csrf_hash ?? ''; ?>';
    </script>
</head>
<body>
    <div class="app-wrapper">
        <!-- Sidebar Navigation -->
        <?php $this->load->view('partials/sidebar'); ?>
        <div class="sidebar-backdrop"></div>

        <!-- Main Content Wrapper -->
        <div class="app-main">
            <!-- Header Bar -->
            <?php $this->load->view('partials/header'); ?>

            <!-- Page Body View -->
            <main class="app-content">
                <?php if ($this->session->flashdata('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show mb-4 rounded-3 d-flex align-items-center gap-2" role="alert">
                        <i class="bi bi-check-circle-fill fs-5"></i>
                        <div><?= html_escape($this->session->flashdata('success')); ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if ($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show mb-4 rounded-3 d-flex align-items-center gap-2" role="alert">
                        <i class="bi bi-exclamation-triangle-fill fs-5"></i>
                        <div><?= html_escape($this->session->flashdata('error')); ?></div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php $this->load->view($content_view); ?>
            </main>

            <!-- Footer -->
            <?php $this->load->view('partials/footer'); ?>
        </div>
    </div>

    <!-- Mobile Bottom Navigation Dock (Visible on Tablets & Phones) -->
    <?php $this->load->view('partials/bottom_nav'); ?>

    <!-- Bootstrap 5 Bundle JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js 4.4 UMD -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.2/dist/chart.umd.min.js"></script>

    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.10.8/dist/sweetalert2.all.min.js"></script>

    <!-- Theme JS -->
    <script src="<?= base_url('assets/js/theme.js'); ?>"></script>

    <!-- Optional Page Specific Scripts -->
    <?php if (isset($extra_js)): ?>
        <?php foreach ($extra_js as $js): ?>
            <script src="<?= base_url($js); ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>
