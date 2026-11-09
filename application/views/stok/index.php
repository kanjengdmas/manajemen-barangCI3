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
    <div class="d-flex flex-wrap align-items-center gap-2">
        <a href="<?= site_url('api/stok/export/excel'); ?>" class="btn btn-sm btn-outline-success d-inline-flex align-items-center gap-1.5 px-2.5 py-1.5 fw-semibold flex-fill flex-sm-grow-0 justify-content-center">
            <i class="bi bi-file-earmark-excel"></i> Ekspor Excel
        </a>
        <a href="<?= site_url('api/stok/export/pdf'); ?>" target="_blank" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1.5 px-2.5 py-1.5 fw-semibold flex-fill flex-sm-grow-0 justify-content-center">
            <i class="bi bi-printer"></i> Cetak / PDF
        </a>
        <a href="<?= site_url('stok/masuk'); ?>" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1 px-3 py-1.5 fw-semibold text-white flex-fill flex-sm-grow-0 justify-content-center">
            <i class="bi bi-arrow-down-left"></i> Masuk
        </a>
        <a href="<?= site_url('stok/keluar'); ?>" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1 px-3 py-1.5 fw-semibold text-white flex-fill flex-sm-grow-0 justify-content-center">
            <i class="bi bi-arrow-up-right"></i> Keluar
        </a>
    </div>
</div>

<div class="table-card mb-4">
    <!-- Filter Toolbar -->
    <div class="table-toolbar">
        <div class="d-flex flex-wrap align-items-center gap-2 flex-grow-1">
            <div class="table-search-input" style="max-width: 220px;">
                <i class="bi bi-calendar3"></i>
                <input type="date" id="filter-start-date" class="form-control" title="Dari Tanggal">
            </div>
            <div class="table-search-input" style="max-width: 220px;">
                <i class="bi bi-calendar-check"></i>
                <input type="date" id="filter-end-date" class="form-control" title="Sampai Tanggal">
            </div>
            <div style="min-width: 170px;">
                <select id="filter-type" class="form-select form-select-sm" style="height: 36px; border-radius: 8px; background-color: var(--mb-card-raised); border: 1px solid var(--mb-border); color: var(--mb-text-primary);">
                    <option value="">Semua Tipe Mutasi</option>
                    <option value="masuk">Stok Masuk (IN)</option>
                    <option value="keluar">Stok Keluar (OUT)</option>
                </select>
            </div>
            <button type="button" class="btn btn-sm btn-danger px-3 d-inline-flex align-items-center gap-1.5" style="height: 36px; border-radius: 8px; background-color: var(--mb-brand); border-color: var(--mb-brand);" onclick="loadStockHistory()">
                <i class="bi bi-funnel"></i> Terapkan
            </button>
            <button type="button" class="btn btn-sm btn-outline-secondary px-2.5 d-inline-flex align-items-center" style="height: 36px; border-radius: 8px;" onclick="resetStockFilter()" title="Reset Filter">
                <i class="bi bi-arrow-counterclockwise"></i>
            </button>
        </div>
        <div>
            <span class="badge-soft badge-soft-secondary" id="stokCountBadge">
                <i class="bi bi-arrow-left-right"></i> Riwayat Mutasi
            </span>
        </div>
    </div>

    <!-- 1. Desktop High-Density Table (Hidden on Phones) -->
    <div class="table-responsive d-none d-md-block">
        <table class="table-modern" id="stock-history-table">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th style="width: 150px;">Waktu & Tanggal</th>
                    <th>Nama Produk</th>
                    <th style="width: 140px;">SKU Barang</th>
                    <th style="width: 140px;">Lot / Batch</th>
                    <th class="text-center" style="width: 120px;">Tipe Mutasi</th>
                    <th class="text-end" style="width: 130px;">Jumlah Unit</th>
                    <th style="width: 140px;">Operator / User</th>
                    <th>Catatan & Referensi</th>
                </tr>
            </thead>
            <tbody id="history-tbody">
                <tr>
                    <td colspan="9" class="text-center py-5 text-muted">
                        <div class="spinner-border spinner-border-sm text-danger me-2" role="status"></div>
                        Memuat data audit trail mutasi...
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- 2. Mobile Feed Activity Cards (Visible on Phones < 768px) -->
    <div class="mobile-card-list d-md-none p-3" id="history-mobile-cards">
        <div class="text-center py-4 text-muted">
            <div class="spinner-border spinner-border-sm text-danger me-2" role="status"></div>
            Memuat transaksi mutasi...
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    loadStockHistory();
});

function resetStockFilter() {
    document.getElementById('filter-start-date').value = '';
    document.getElementById('filter-end-date').value = '';
    document.getElementById('filter-type').value = '';
    loadStockHistory();
}

function loadStockHistory() {
    const tbody = document.getElementById('history-tbody');
    const mobileContainer = document.getElementById('history-mobile-cards');
    const startDate = document.getElementById('filter-start-date').value;
    const endDate = document.getElementById('filter-end-date').value;
    const type = document.getElementById('filter-type').value;
    const countBadge = document.getElementById('stokCountBadge');

    let url = window.BASE_URL + 'api/stok/history?limit=50';
    if (startDate) url += '&start_date=' + encodeURIComponent(startDate);
    if (endDate) url += '&end_date=' + encodeURIComponent(endDate);
    if (type) url += '&tipe=' + encodeURIComponent(type);

    fetch(url)
        .then(res => res.json())
        .then(res => {
            if (!res.status || !res.data || res.data.length === 0) {
                if (countBadge) countBadge.innerHTML = `<i class="bi bi-arrow-left-right"></i> 0 Transaksi`;
                const emptyMarkup = `
                    <div class="table-empty-state py-4 text-center">
                        <div class="table-empty-icon mb-2">
                            <i class="bi bi-inbox fs-2 text-muted"></i>
                        </div>
                        <h6 class="fw-bold mb-1" style="color: var(--mb-text-primary);">Tidak Ada Data Mutasi</h6>
                        <p class="text-muted small mb-0">Belum ada transaksi pergerakan stok yang sesuai dengan filter.</p>
                    </div>
                `;
                tbody.innerHTML = `<tr><td colspan="9">${emptyMarkup}</td></tr>`;
                mobileContainer.innerHTML = emptyMarkup;
                return;
            }

            if (countBadge) {
                countBadge.innerHTML = `<i class="bi bi-arrow-left-right"></i> ${res.data.length} Transaksi Terkini`;
            }

            let html = '';
            let mobileHtml = '';

            res.data.forEach((item, index) => {
                const isMasuk = item.tipe === 'masuk';
                const badgeClass = isMasuk ? 'badge-soft-success' : 'badge-soft-danger';
                const dotClass = isMasuk ? 'bg-success' : 'bg-danger';
                const icon = isMasuk ? 'bi-arrow-down-left' : 'bi-arrow-up-right';
                const qtyColor = isMasuk ? 'text-success' : 'text-danger';
                const sign = isMasuk ? '+' : '-';
                const iconClass = isMasuk ? 'in' : 'out';
                const qtyClass = isMasuk ? 'in' : 'out';

                // Desktop Table Row
                html += `
                    <tr>
                        <td class="text-center text-muted fw-semibold tabular-nums">${index + 1}</td>
                        <td>
                            <div class="tabular-nums font-monospace small" style="color: var(--mb-text-primary); font-size: 0.8rem;">
                                ${item.tanggal}
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold" style="color: var(--mb-text-primary); font-size: 0.88rem;">
                                ${item.nama_produk || '-'}
                            </div>
                        </td>
                        <td>
                            <span class="sku-badge">
                                <i class="bi bi-upc-scan text-muted" style="font-size: 0.68rem;"></i>
                                ${item.sku || '-'}
                            </span>
                        </td>
                        <td>
                            ${item.nomor_batch
                                ? '<span class="sku-badge font-monospace"><i class="bi bi-box text-muted" style="font-size: 0.68rem;"></i> ' + item.nomor_batch + '</span>'
                                : '<span class="text-muted small">-</span>'}
                        </td>
                        <td class="text-center">
                            <span class="badge-soft ${badgeClass}">
                                <span class="badge-dot ${dotClass}"></span>
                                <i class="bi ${icon}" style="font-size: 0.72rem;"></i>
                                ${item.tipe.toUpperCase()}
                            </span>
                        </td>
                        <td class="text-end">
                            <span class="fw-bold tabular-nums font-monospace ${qtyColor}" style="font-size: 0.88rem;">
                                ${sign}${Number(item.jumlah).toLocaleString()} <span class="fw-normal text-muted" style="font-size: 0.7rem;">unit</span>
                            </span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center gap-1.5 small text-muted">
                                <i class="bi bi-person-circle text-secondary" style="font-size: 0.82rem;"></i>
                                <span class="text-truncate" style="max-width: 110px;">${item.user_name || 'System'}</span>
                            </div>
                        </td>
                        <td>
                            <span class="text-muted small">${item.catatan || '-'}</span>
                        </td>
                    </tr>
                `;

                // Mobile Feed Card
                mobileHtml += `
                    <div class="mobile-mutation-card">
                        <div class="mobile-mutation-icon ${iconClass}">
                            <i class="bi ${icon}"></i>
                        </div>
                        <div class="mobile-mutation-details">
                            <div class="mobile-mutation-prod">${item.nama_produk || 'Barang Inventaris'}</div>
                            <div class="mobile-mutation-meta">
                                <span class="badge-soft badge-soft-secondary font-monospace" style="font-size: 0.68rem;">${item.sku || '-'}</span>
                                ${item.nomor_batch ? `<span class="badge-soft badge-soft-warning font-monospace" style="font-size: 0.68rem;">Lot: ${item.nomor_batch}</span>` : ''}
                                <span>· ${item.user_name || 'System'}</span>
                            </div>
                            ${item.catatan ? `<div class="text-muted small mt-1 text-truncate" style="font-size: 0.72rem;"><i class="bi bi-chat-left-text me-1"></i>${item.catatan}</div>` : ''}
                        </div>
                        <div class="mobile-mutation-qty">
                            <div class="mobile-qty-pill ${qtyClass}">${sign}${Number(item.jumlah).toLocaleString()}</div>
                            <div class="mobile-mutation-time tabular-nums">${item.tanggal ? item.tanggal.substring(11, 16) : ''}</div>
                        </div>
                    </div>
                `;
            });

            tbody.innerHTML = html;
            mobileContainer.innerHTML = mobileHtml;
        })
        .catch(err => {
            console.error(err);
            const errorMarkup = '<div class="text-center py-4 text-danger"><i class="bi bi-exclamation-triangle me-1"></i> Gagal memuat data mutasi dari server.</div>';
            tbody.innerHTML = `<tr><td colspan="9">${errorMarkup}</td></tr>`;
            mobileContainer.innerHTML = errorMarkup;
        });
}
</script>
