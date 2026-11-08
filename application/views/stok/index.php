<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary); letter-spacing: -0.01em;">
            Riwayat Mutasi Keluar & Masuk
        </h2>
        <p class="text-muted small mb-0">
            Audit trail pergerakan stok barang, mutasi masuk, pengeluaran FIFO, dan log operator.
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?= site_url('api/stok/export/excel'); ?>" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1.5 px-3 py-1.5 fw-semibold">
            <i class="bi bi-file-earmark-excel"></i> Ekspor Excel
        </a>
        <a href="<?= site_url('api/stok/export/pdf'); ?>" target="_blank" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1.5 px-3 py-1.5 fw-semibold">
            <i class="bi bi-printer"></i> Cetak / PDF
        </a>
        <a href="<?= site_url('stok/masuk'); ?>" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 px-3 py-1.5 fw-semibold text-white">
            <i class="bi bi-arrow-down-left"></i> Masuk
        </a>
        <a href="<?= site_url('stok/keluar'); ?>" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1 px-3 py-1.5 fw-semibold text-white">
            <i class="bi bi-arrow-up-right"></i> Keluar
        </a>
    </div>
</div>

<div class="mb-card mb-4 p-3">
    <div class="row g-2 align-items-center">
        <div class="col-md-3">
            <input type="date" id="filter-start-date" class="form-control form-control-sm" placeholder="Dari Tanggal">
        </div>
        <div class="col-md-3">
            <input type="date" id="filter-end-date" class="form-control form-control-sm" placeholder="Sampai Tanggal">
        </div>
        <div class="col-md-3">
            <select id="filter-type" class="form-select form-select-sm">
                <option value="">-- Semua Tipe Mutasi --</option>
                <option value="masuk">Stok Masuk</option>
                <option value="keluar">Stok Keluar</option>
            </select>
        </div>
        <div class="col-md-3 text-end">
            <button type="button" class="btn btn-sm btn-primary w-100" onclick="loadStockHistory()">
                <i class="bi bi-funnel me-1"></i> Terapkan Filter
            </button>
        </div>
    </div>
</div>

<div class="mb-card mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0" id="stock-history-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th>Waktu Mutasi</th>
                    <th>Nama Produk</th>
                    <th>SKU</th>
                    <th>Lot / Batch</th>
                    <th class="text-center" style="width: 100px;">Tipe</th>
                    <th class="text-end" style="width: 120px;">Jumlah Unit</th>
                    <th>Petugas / User</th>
                    <th>Catatan</th>
                </tr>
            </thead>
            <tbody id="history-tbody">
                <tr>
                    <td colspan="9" class="text-center py-4 text-muted">
                        <div class="spinner-border spinner-border-sm text-primary me-2" role="status"></div>
                        Memuat data riwayat mutasi...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadStockHistory();
});

function loadStockHistory() {
    const tbody = document.getElementById('history-tbody');
    const startDate = document.getElementById('filter-start-date').value;
    const endDate = document.getElementById('filter-end-date').value;
    const type = document.getElementById('filter-type').value;

    let url = window.BASE_URL + 'api/stok/history?limit=50';
    if (startDate) url += '&start_date=' + encodeURIComponent(startDate);
    if (endDate) url += '&end_date=' + encodeURIComponent(endDate);
    if (type) url += '&tipe=' + encodeURIComponent(type);

    fetch(url)
        .then(res => res.json())
        .then(res => {
            if (!res.status || !res.data || res.data.length === 0) {
                tbody.innerHTML = '<tr><td colspan="9" class="text-center py-5 text-muted"><i class="bi bi-inbox fs-2 d-block mb-2 text-secondary"></i>Belum ada data mutasi yang sesuai filter.</td></tr>';
                return;
            }

            let html = '';
            res.data.forEach((item, index) => {
                const isMasuk = item.tipe === 'masuk';
                const badgeClass = isMasuk ? 'bg-success-subtle text-success border border-success-subtle' : 'bg-danger-subtle text-danger border border-danger-subtle';
                const icon = isMasuk ? 'bi-arrow-down-left' : 'bi-arrow-up-right';

                html += `
                    <tr>
                        <td class="text-center text-muted fw-semibold">${index + 1}</td>
                        <td class="small">${item.tanggal}</td>
                        <td class="fw-bold" style="color: var(--mb-text-primary);">${item.nama_produk || '-'}</td>
                        <td><code class="text-primary">${item.sku || '-'}</code></td>
                        <td>${item.nomor_batch ? '<span class="badge bg-light text-dark border">' + item.nomor_batch + '</span>' : '<span class="text-muted">-</span>'}</td>
                        <td class="text-center">
                            <span class="badge ${badgeClass} d-inline-flex align-items-center gap-1">
                                <i class="bi ${icon}"></i> ${item.tipe.toUpperCase()}
                            </span>
                        </td>
                        <td class="text-end fw-bold tabular-nums ${isMasuk ? 'text-success' : 'text-danger'}">
                            ${isMasuk ? '+' : '-'}${Number(item.jumlah).toLocaleString()}
                        </td>
                        <td class="small text-muted"><i class="bi bi-person me-1"></i>${item.user_name || 'System'}</td>
                        <td class="small text-muted">${item.catatan || '-'}</td>
                    </tr>
                `;
            });
            tbody.innerHTML = html;
        })
        .catch(err => {
            console.error(err);
            tbody.innerHTML = '<tr><td colspan="9" class="text-center py-4 text-danger">Gagal memuat data mutasi dari server.</td></tr>';
        });
}
</script>
