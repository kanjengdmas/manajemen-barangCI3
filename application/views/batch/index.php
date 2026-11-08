<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="d-flex flex-column flex-md-row align-items-start align-items-md-center justify-content-between gap-3 mb-4">
    <div>
        <h2 class="h4 fw-bold mb-1" style="color: var(--mb-text-primary); letter-spacing: -0.01em;">
            Manajemen Lot & Batch Kardus
        </h2>
        <p class="text-muted small mb-0">
            Pelacakan nomor lot kardus fisik, tanggal kadaluarsa, dan alokasi stok masuk/keluar metode FEFO/FIFO.
        </p>
    </div>
    <div class="d-flex align-items-center gap-2">
        <a href="<?= site_url('batch/create'); ?>" class="btn btn-sm btn-danger d-inline-flex align-items-center gap-1.5 px-3 py-1.5 fw-semibold text-white shadow-sm" style="background-color: var(--mb-brand); border-color: var(--mb-brand);">
            <i class="bi bi-plus-lg"></i> Registrasi Batch Baru
        </a>
    </div>
</div>

<div class="mb-card mb-4">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead>
                <tr>
                    <th class="text-center" style="width: 50px;">No</th>
                    <th style="width: 90px;">QR Batch</th>
                    <th>Nomor Lot / Batch</th>
                    <th>Nama Produk</th>
                    <th>Tgl Masuk</th>
                    <th>Tgl Kadaluarsa</th>
                    <th class="text-end">Stok Awal</th>
                    <th class="text-end">Sisa Saat Ini</th>
                    <th class="text-center">Status</th>
                    <th class="text-center" style="width: 100px;">Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($batches)): ?>
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <i class="bi bi-layers fs-2 d-block mb-2 text-secondary"></i>
                            Belum ada registrasi batch barang. Silakan daftarkan lot/batch kardus baru.
                        </td>
                    </tr>
                <?php else: ?>
                    <?php $no = 1; foreach ($batches as $b): ?>
                        <tr>
                            <td class="text-center text-muted fw-semibold"><?= $no++; ?></td>
                            <td>
                                <?php if (!empty($b['qr_code']) && file_exists(FCPATH . $b['qr_code'])): ?>
                                    <img src="<?= base_url($b['qr_code']); ?>" alt="QR" class="img-thumbnail" style="width: 44px; height: 44px;">
                                <?php else: ?>
                                    <span class="badge bg-secondary">No QR</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="fw-bold font-monospace text-dark"><?= html_escape($b['nomor_batch']); ?></span>
                            </td>
                            <td>
                                <div class="fw-semibold" style="color: var(--mb-text-primary);"><?= html_escape($b['nama_produk'] ?? '-'); ?></div>
                                <code class="small text-muted"><?= html_escape($b['sku'] ?? ''); ?></code>
                            </td>
                            <td><?= !empty($b['tanggal_masuk']) ? format_date_by_preference($b['tanggal_masuk'], null, false) : '-'; ?></td>
                            <td>
                                <?php if (!empty($b['tanggal_kadaluarsa'])): ?>
                                    <?php
                                        $diffDays = (strtotime($b['tanggal_kadaluarsa']) - time()) / 86400;
                                        $isExpiring = $diffDays <= 30 && $diffDays > 0;
                                        $isExpired = $diffDays <= 0;
                                    ?>
                                    <span class="<?= $isExpired ? 'text-danger fw-bold' : ($isExpiring ? 'text-warning fw-semibold' : ''); ?>">
                                        <?= format_date_by_preference($b['tanggal_kadaluarsa'], null, false); ?>
                                    </span>
                                <?php else: ?>
                                    <span class="text-muted">Tidak Ada</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-end text-muted tabular-nums"><?= number_format($b['jumlah_awal']); ?></td>
                            <td class="text-end fw-bold tabular-nums <?= $b['stok_saat_ini'] <= 0 ? 'text-danger' : 'text-success'; ?>">
                                <?= number_format($b['stok_saat_ini']); ?>
                            </td>
                            <td class="text-center">
                                <?php
                                    $stok = (int)($b['stok_saat_ini'] ?? 0);
                                    $status = $b['status'] ?? ($isExpired ? 'kadaluarsa' : ($stok <= 0 ? 'habis' : ($isExpiring ? 'hampir_habis' : 'tersedia')));
                                ?>
                                <?php if ($status === 'kadaluarsa'): ?>
                                    <span class="badge bg-danger">Kadaluarsa</span>
                                <?php elseif ($status === 'habis'): ?>
                                    <span class="badge bg-secondary">Habis</span>
                                <?php elseif ($status === 'hampir_habis'): ?>
                                    <span class="badge bg-warning text-dark">Hampir Habis</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Tersedia</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <a href="<?= site_url('api/batch/' . $b['id'] . '/download-qr'); ?>" target="_blank" class="btn btn-sm btn-outline-secondary" title="Unduh Label QR">
                                    <i class="bi bi-qr-code"></i>
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
