<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary); letter-spacing: -0.01em;">
            Pusat Ekspor & Laporan Inventaris
        </h2>
        <p class="text-muted small mb-0">
            Cetak laporan rekapitulasi mutasi barang, ekspor spreadsheet Excel native, dan dokumen bertanda tangan resmi.
        </p>
    </div>
</div>

<div class="row g-4">
    <!-- Filter Card -->
    <div class="col-12 col-lg-7">
        <div class="mb-card p-4">
            <h5 class="fw-bold mb-3" style="color: var(--mb-text-primary);">Kriteria Laporan Mutasi Stok</h5>
            <form id="report-filter-form" target="_blank" method="GET">
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Mulai</label>
                        <input type="date" name="start_date" id="rep-start-date" class="form-control" value="<?= date('Y-m-01'); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tanggal Selesai</label>
                        <input type="date" name="end_date" id="rep-end-date" class="form-control" value="<?= date('Y-m-d'); ?>">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Spesifik Produk</label>
                        <select name="produk_id" id="rep-product" class="form-select">
                            <option value="">-- Semua Produk --</option>
                            <?php foreach ($products as $p): ?>
                                <option value="<?= $p['id']; ?>"><?= html_escape($p['nama_produk']); ?> (<?= html_escape($p['sku']); ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Tipe Transaksi</label>
                        <select name="tipe" id="rep-type" class="form-select">
                            <option value="">-- Semua Mutasi (Masuk & Keluar) --</option>
                            <option value="masuk">Hanya Stok Masuk</option>
                            <option value="keluar">Hanya Stok Keluar</option>
                        </select>
                    </div>
                    <div class="col-12 pt-3 border-top d-flex gap-2 justify-content-end">
                        <button type="button" class="btn btn-outline-success px-3 fw-semibold" onclick="exportReport('excel')">
                            <i class="bi bi-file-earmark-excel me-1"></i> Ekspor Excel (.xls)
                        </button>
                        <button type="button" class="btn btn-danger px-3 fw-semibold" style="background-color: var(--mb-brand); border-color: var(--mb-brand);" onclick="exportReport('pdf')">
                            <i class="bi bi-printer me-1"></i> Cetak Dokumen PDF
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Quick Info & Instructions -->
    <div class="col-12 col-lg-5">
        <div class="mb-card p-4">
            <h5 class="fw-bold mb-3" style="color: var(--mb-text-primary);">Standar Ekspor Murni CI3</h5>
            <div class="d-flex align-items-start gap-3 mb-3">
                <div class="p-2.5 rounded bg-success-subtle text-success">
                    <i class="bi bi-file-earmark-spreadsheet fs-4"></i>
                </div>
                <div>
                    <div class="fw-semibold">Spreadsheet Native</div>
                    <div class="small text-muted">Dibuat murni dengan struktur XML/HTML tabel resmi tanpa library PHPSpreadsheet/Composer. Kompatibel dengan MS Excel, WPS Office, dan LibreOffice.</div>
                </div>
            </div>
            <div class="d-flex align-items-start gap-3 mb-3">
                <div class="p-2.5 rounded bg-danger-subtle text-danger">
                    <i class="bi bi-file-earmark-pdf fs-4"></i>
                </div>
                <div>
                    <div class="fw-semibold">Kop Surat & Legal Signatures</div>
                    <div class="small text-muted">Format A4 resmi lengkap dengan kop surat profil gudang dan kolom tanda tangan Pengirim, Petugas Gudang, dan Penanggung Jawab.</div>
                </div>
            </div>
            <div class="d-flex align-items-start gap-3">
                <div class="p-2.5 rounded bg-primary-subtle text-primary">
                    <i class="bi bi-shield-check fs-4"></i>
                </div>
                <div>
                    <div class="fw-semibold">1-Click Backup Database</div>
                    <div class="small text-muted">Unduh arsip SQL database utuh untuk cadangan keamanan inventaris melalui menu Pengaturan Sistem.</div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function exportReport(format) {
    const startDate = document.getElementById('rep-start-date').value;
    const endDate = document.getElementById('rep-end-date').value;
    const prodId = document.getElementById('rep-product').value;
    const type = document.getElementById('rep-type').value;

    let baseUrl = window.BASE_URL + 'api/stok/export/' + format + '?';
    const params = new URLSearchParams();
    if (startDate) params.append('start_date', startDate);
    if (endDate) params.append('end_date', endDate);
    if (prodId) params.append('produk_id', prodId);
    if (type) params.append('tipe', type);

    window.open(baseUrl + params.toString(), '_blank');
}
</script>
