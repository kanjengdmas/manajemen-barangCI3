<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="row justify-content-center">
    <div class="col-12 col-lg-8">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div>
                <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary);">Profil Saya & Pengaturan Akun</h2>
                <p class="text-muted small mb-0">Kelola identitas operator, kata sandi, dan preferensi tampilan personal.</p>
            </div>
            <span class="badge bg-primary text-uppercase px-3 py-1.5 fw-semibold">
                <?= html_escape($user['role']); ?>
            </span>
        </div>

        <!-- Profil Form Card -->
        <div class="mb-card p-4 mb-4">
            <h5 class="fw-bold mb-3" style="color: var(--mb-text-primary);">Informasi Personal</h5>
            <form id="profile-form">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Nama Lengkap</label>
                        <input type="text" name="name" id="prof-name" class="form-control" value="<?= html_escape($user['name']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Alamat Email</label>
                        <input type="email" name="email" id="prof-email" class="form-control" value="<?= html_escape($user['email']); ?>" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Peran / Hak Akses</label>
                        <input type="text" class="form-control bg-light" value="<?= ucfirst($user['role']); ?>" readonly disabled>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Bergabung Sejak</label>
                        <input type="text" class="form-control bg-light" value="<?= format_date_by_preference($user['created_at']); ?>" readonly disabled>
                    </div>
                    <div class="col-12 text-end pt-3">
                        <button type="submit" class="btn btn-primary px-4 fw-semibold" id="btn-save-profile">
                            <i class="bi bi-save me-1"></i> Simpan Perubahan Profil
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Password Change Card -->
        <div class="mb-card p-4">
            <h5 class="fw-bold mb-3" style="color: var(--mb-text-primary);">Ganti Kata Sandi</h5>
            <form id="password-form">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kata Sandi Saat Ini</label>
                        <input type="password" name="current_password" id="pass-current" class="form-control" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Kata Sandi Baru</label>
                        <input type="password" name="new_password" id="pass-new" class="form-control" minlength="6" required>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Konfirmasi Sandi Baru</label>
                        <input type="password" name="confirm_password" id="pass-confirm" class="form-control" minlength="6" required>
                    </div>
                    <div class="col-12 text-end pt-3">
                        <button type="submit" class="btn btn-danger px-4 fw-semibold" id="btn-save-password" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
                            <i class="bi bi-key me-1"></i> Perbarui Kata Sandi
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
document.getElementById('profile-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const btn = document.getElementById('btn-save-profile');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

    const formData = new FormData(this);
    formData.append(window.CSRF_TOKEN_NAME, window.CSRF_HASH);

    fetch(window.BASE_URL + 'api/profile', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-save me-1"></i> Simpan Perubahan Profil';
        if (res.status) {
            Swal.fire('Berhasil', res.message, 'success');
        } else {
            Swal.fire('Gagal', res.message || 'Gagal menyimpan profil', 'error');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-save me-1"></i> Simpan Perubahan Profil';
        Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
    });
});

document.getElementById('password-form').addEventListener('submit', function(e) {
    e.preventDefault();
    const newPass = document.getElementById('pass-new').value;
    const confPass = document.getElementById('pass-confirm').value;

    if (newPass !== confPass) {
        Swal.fire('Peringatan', 'Konfirmasi kata sandi tidak cocok.', 'warning');
        return;
    }

    const btn = document.getElementById('btn-save-password');
    btn.disabled = true;
    btn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span> Menyimpan...';

    const formData = new FormData(this);
    formData.append(window.CSRF_TOKEN_NAME, window.CSRF_HASH);

    fetch(window.BASE_URL + 'api/profile/password', {
        method: 'POST',
        headers: { 'X-Requested-With': 'XMLHttpRequest' },
        body: formData
    })
    .then(res => res.json())
    .then(res => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-key me-1"></i> Perbarui Kata Sandi';
        if (res.status) {
            Swal.fire('Berhasil', res.message, 'success');
            document.getElementById('password-form').reset();
        } else {
            Swal.fire('Gagal', res.message || 'Gagal mengganti kata sandi', 'error');
        }
    })
    .catch(() => {
        btn.disabled = false;
        btn.innerHTML = '<i class="bi bi-key me-1"></i> Perbarui Kata Sandi';
        Swal.fire('Error', 'Terjadi kesalahan sistem.', 'error');
    });
});
</script>
