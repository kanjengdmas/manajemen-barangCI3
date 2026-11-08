<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Mutasi Stok Gudang</title>
    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 10pt;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .header-table {
            width: 100%;
            border-bottom: 2px solid #dc2626;
            padding-bottom: 10px;
            margin-bottom: 15px;
        }
        .company-name {
            font-size: 16pt;
            font-weight: bold;
            color: #b91c1c;
            margin: 0;
        }
        .company-tagline {
            font-size: 9pt;
            color: #64748b;
            margin: 2px 0;
        }
        .company-address {
            font-size: 8.5pt;
            color: #475569;
            margin: 0;
        }
        .report-title {
            text-align: center;
            font-size: 13pt;
            font-weight: bold;
            text-transform: uppercase;
            margin: 10px 0 5px 0;
            letter-spacing: 0.5px;
        }
        .report-meta {
            text-align: center;
            font-size: 8.5pt;
            color: #64748b;
            margin-bottom: 15px;
        }
        table.data-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }
        table.data-table th {
            background-color: #f1f5f9;
            color: #1e293b;
            font-size: 8.5pt;
            font-weight: bold;
            border: 1px solid #cbd5e1;
            padding: 6px 8px;
            text-align: left;
        }
        table.data-table td {
            font-size: 8.5pt;
            border: 1px solid #e2e8f0;
            padding: 5px 8px;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .badge-masuk {
            color: #15803d;
            font-weight: bold;
        }
        .badge-keluar {
            color: #b91c1c;
            font-weight: bold;
        }
        .footer-table {
            width: 100%;
            margin-top: 20px;
        }
        .sig-box {
            text-align: center;
            width: 33%;
        }
        .sig-line {
            border-bottom: 1px solid #333;
            width: 70%;
            margin: 50px auto 5px auto;
        }
        .sig-title {
            font-size: 8.5pt;
            color: #64748b;
        }
        .note-box {
            margin-top: 20px;
            font-size: 8pt;
            color: #64748b;
            font-style: italic;
            border-top: 1px dashed #cbd5e1;
            padding-top: 5px;
        }
    </style>
</head>
<body>

    <!-- Header Letterhead -->
    <table class="header-table">
        <tr>
            <td>
                <div class="company-name"><?= html_escape($company['nama_perusahaan'] ?? 'PT. Gudang Solusi Indonesia'); ?></div>
                <div class="company-tagline"><?= html_escape($company['tagline'] ?? 'Sistem Pergudangan & Manajemen Inventaris'); ?></div>
                <div class="company-address">
                    <?= html_escape($company['alamat'] ?? ''); ?> | Telp: <?= html_escape($company['telepon'] ?? ''); ?> | Email: <?= html_escape($company['email'] ?? ''); ?>
                </div>
            </td>
        </tr>
    </table>

    <div class="report-title">Laporan Rekapitulasi Mutasi Stok Gudang</div>
    <div class="report-meta">Dicetak pada: <?= $generated_at; ?></div>

    <!-- Data Table -->
    <table class="data-table">
        <thead>
            <tr>
                <th width="4%" class="text-center">No</th>
                <th width="15%">Waktu Transaksi</th>
                <th width="22%">Nama Produk</th>
                <th width="12%">SKU</th>
                <th width="13%">Lot / Batch</th>
                <th width="8%" class="text-center">Tipe</th>
                <th width="8%" class="text-right">Jumlah</th>
                <th width="18%">Catatan</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($transactions)): ?>
                <tr>
                    <td colspan="8" class="text-center" style="padding: 15px; color: #94a3b8;">Tidak ada data mutasi stok yang sesuai kriteria filter.</td>
                </tr>
            <?php else: ?>
                <?php $no = 1; foreach ($transactions as $t): ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= format_date_by_preference($t['tanggal'], null, true); ?></td>
                        <td><strong><?= html_escape($t['nama_produk']); ?></strong></td>
                        <td><?= html_escape($t['sku']); ?></td>
                        <td><?= html_escape($t['nomor_batch'] ?? '-'); ?></td>
                        <td class="text-center">
                            <span class="<?= $t['tipe'] === 'masuk' ? 'badge-masuk' : 'badge-keluar'; ?>">
                                <?= strtoupper($t['tipe']); ?>
                            </span>
                        </td>
                        <td class="text-right"><strong><?= number_format($t['jumlah']); ?></strong></td>
                        <td><?= html_escape($t['catatan'] ?? '-'); ?></td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Legal Signature Block -->
    <table class="footer-table">
        <tr>
            <td class="sig-box">
                <div class="sig-title">Diserahkan Oleh,</div>
                <div class="sig-line"></div>
                <div style="font-weight: bold; font-size: 9pt;">( .................................... )</div>
                <div class="sig-title">Pengirim / Supplier</div>
            </td>
            <td class="sig-box">
                <div class="sig-title">Diterima Oleh,</div>
                <div class="sig-line"></div>
                <div style="font-weight: bold; font-size: 9pt;">( .................................... )</div>
                <div class="sig-title">Operator / Staf Gudang</div>
            </td>
            <td class="sig-box">
                <div class="sig-title">Mengetahui & Menyetujui,</div>
                <div class="sig-line"></div>
                <div style="font-weight: bold; font-size: 9pt;"><?= html_escape($company['pic_name'] ?? 'Dimas Rizqi'); ?></div>
                <div class="sig-title"><?= html_escape($company['pic_jabatan'] ?? 'Kepala Gudang & Operasional'); ?></div>
            </td>
        </tr>
    </table>

    <?php if (!empty($company['footer_note'])): ?>
        <div class="note-box">
            <?= html_escape($company['footer_note']); ?>
        </div>
    <?php endif; ?>

</body>
</html>
