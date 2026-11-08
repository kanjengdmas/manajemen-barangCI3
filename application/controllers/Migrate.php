<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Migrate extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library('migration');
        $this->load->database();
    }

    /**
     * Run migration to latest schema version
     * CLI: php index.php migrate latest
     */
    public function latest() {
        if ($this->migration->latest() === FALSE) {
            echo "Gagal migrasi: " . $this->migration->error_string() . PHP_EOL;
            return;
        }

        echo "Berhasil: Migrasi skema database telah selesai dieksekusi." . PHP_EOL;
    }

    /**
     * Seed initial required data
     * CLI: php index.php migrate seed
     */
    public function seed() {
        // 1. Seed Default Admin
        $existingAdmin = $this->db->get_where('users', ['email' => 'admin@admin.com'])->row_array();
        if (!$existingAdmin) {
            $this->db->insert('users', [
                'name' => 'Administrator Gudang',
                'email' => 'admin@admin.com',
                'role' => 'admin',
                'password' => password_hash('password', PASSWORD_BCRYPT),
                'avatar' => null,
                'total_login' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            echo " [OK] Akun admin default dibuat (admin@admin.com / password)" . PHP_EOL;
        } else {
            echo " [-] Akun admin default sudah ada." . PHP_EOL;
        }

        // Also add admin@gmail.com as secondary alias if needed per PRD
        $existingAdmin2 = $this->db->get_where('users', ['email' => 'admin@gmail.com'])->row_array();
        if (!$existingAdmin2) {
            $this->db->insert('users', [
                'name' => 'Admin Gudang',
                'email' => 'admin@gmail.com',
                'role' => 'admin',
                'password' => password_hash('password', PASSWORD_BCRYPT),
                'avatar' => null,
                'total_login' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ]);
            echo " [OK] Akun admin sekunder dibuat (admin@gmail.com / password)" . PHP_EOL;
        }

        // 2. Seed Default Categories
        $categories = ['Elektronik & Sparepart', 'Bahan Baku & Material', 'Kemasan & Kardus', 'Peralatan & Perkakas'];
        foreach ($categories as $cat) {
            $exists = $this->db->get_where('kategori', ['nama_kategori' => $cat])->row_array();
            if (!$exists) {
                $this->db->insert('kategori', [
                    'nama_kategori' => $cat,
                    'created_at' => date('Y-m-d H:i:s')
                ]);
            }
        }
        echo " [OK] Data kategori bawaan berhasil disiapkan." . PHP_EOL;

        // 3. Seed Default Suppliers
        $suppliers = [
            [
                'nama_pemasok' => 'PT. Sumber Berkah Logistik',
                'kontak' => '081234567890',
                'alamat' => 'Kawasan Industri Pulogadung Blok B4, Jakarta Timur'
            ],
            [
                'nama_pemasok' => 'CV. Mitra Prima Komponen',
                'kontak' => '089876543210',
                'alamat' => 'Jl. Soekarno Hatta No. 88, Bandung'
            ]
        ];
        foreach ($suppliers as $sup) {
            $exists = $this->db->get_where('pemasok', ['nama_pemasok' => $sup['nama_pemasok']])->row_array();
            if (!$exists) {
                $this->db->insert('pemasok', array_merge($sup, ['created_at' => date('Y-m-d H:i:s')]));
            }
        }
        echo " [OK] Data pemasok bawaan berhasil disiapkan." . PHP_EOL;

        // 4. Seed Company Profile & System Preferences
        $companyProfile = [
            'nama_perusahaan' => 'PT. Gudang Solusi Indonesia',
            'tagline' => 'Sistem Pergudangan & Manajemen Inventaris Terpadu',
            'alamat' => 'Jl. Kawasan Industri No. 45, Jakarta Pusat',
            'telepon' => '021-5550192',
            'email' => 'admin@gudangsolusi.id',
            'pic_name' => 'Dimas Rizqi',
            'pic_jabatan' => 'Kepala Gudang & Operasional',
            'footer_note' => 'Dokumen ini sah dan diterbitkan secara elektronik oleh Sistem Manajemen Barang.'
        ];
        $this->preference->save_company($companyProfile);

        // System Settings
        $this->preference->set('theme', 'light');
        $this->preference->set('language', 'id');
        $this->preference->set('auto_refresh', true);
        $this->preference->set('auto_refresh_interval', 5);
        $this->preference->set('date_format', 'DD/MM/YYYY');
        $this->preference->set('fifo_policy', true);
        $this->preference->set('default_min_stock', 10);
        $this->preference->set('sku_prefix', 'PRD-');
        $this->preference->set('lot_prefix', 'LOT-');
        $this->preference->set('page_length', 10);

        echo " [OK] Preferensi sistem & profil perusahaan berhasil disimpan." . PHP_EOL;

        // 5. Seed Comprehensive Master Products, Batches & Realistic Transactions
        $adminUser = $this->db->get_where('users', ['email' => 'admin@admin.com'])->row_array();
        $adminId = $adminUser ? $adminUser['id'] : 1;

        $catMap = [];
        foreach ($this->db->get('kategori')->result_array() as $c) {
            $catMap[$c['nama_kategori']] = $c['id'];
        }
        $supMap = [];
        foreach ($this->db->get('pemasok')->result_array() as $s) {
            $supMap[$s['nama_pemasok']] = $s['id'];
        }
        $defaultSupId = !empty($supMap) ? reset($supMap) : 1;

        $mockProducts = [
            [
                'sku' => 'PRD-001',
                'nama_produk' => 'Kardus Corrugated Double Wall 40x30x20',
                'kategori' => 'Kemasan & Kardus',
                'stok' => 350,
                'stok_minimal' => 50,
                'satuan' => 'Pcs',
                'harga_beli' => 4500,
                'harga_jual' => 6500,
                'batch' => [
                    'nomor_batch' => 'LOT-2026-001',
                    'kadaluarsa' => '2027-12-31',
                    'lokasi_rak' => 'RAK-A-01'
                ]
            ],
            [
                'sku' => 'PRD-002',
                'nama_produk' => 'Bubble Wrap Heavy Duty Roll 1.25x50m',
                'kategori' => 'Kemasan & Kardus',
                'stok' => 8,
                'stok_minimal' => 15,
                'satuan' => 'Roll',
                'harga_beli' => 85000,
                'harga_jual' => 110000,
                'batch' => [
                    'nomor_batch' => 'LOT-2026-002',
                    'kadaluarsa' => date('Y-m-d', strtotime('+17 days')), // FEFO warning
                    'lokasi_rak' => 'RAK-A-02'
                ]
            ],
            [
                'sku' => 'PRD-003',
                'nama_produk' => 'Lakban Coklat Fragile 48mm x 100m',
                'kategori' => 'Kemasan & Kardus',
                'stok' => 0,
                'stok_minimal' => 20,
                'satuan' => 'Roll',
                'harga_beli' => 9500,
                'harga_jual' => 13500,
                'batch' => null
            ],
            [
                'sku' => 'PRD-004',
                'nama_produk' => 'Sensor Inframerah Proximity E18-D80NK',
                'kategori' => 'Elektronik & Sparepart',
                'stok' => 120,
                'stok_minimal' => 25,
                'satuan' => 'Unit',
                'harga_beli' => 32000,
                'harga_jual' => 48000,
                'batch' => [
                    'nomor_batch' => 'LOT-2026-004',
                    'kadaluarsa' => date('Y-m-d', strtotime('+9 days')), // FEFO warning
                    'lokasi_rak' => 'RAK-B-01'
                ]
            ],
            [
                'sku' => 'PRD-005',
                'nama_produk' => 'Power Supply Switching 24V 10A Industrial',
                'kategori' => 'Elektronik & Sparepart',
                'stok' => 4,
                'stok_minimal' => 10,
                'satuan' => 'Unit',
                'harga_beli' => 145000,
                'harga_jual' => 195000,
                'batch' => [
                    'nomor_batch' => 'LOT-2026-005',
                    'kadaluarsa' => '2028-06-30',
                    'lokasi_rak' => 'RAK-B-03'
                ]
            ],
            [
                'sku' => 'PRD-006',
                'nama_produk' => 'Pelumas Mesin Conveyor ISO VG 220 5L',
                'kategori' => 'Bahan Baku & Material',
                'stok' => 45,
                'stok_minimal' => 10,
                'satuan' => 'Galon',
                'harga_beli' => 210000,
                'harga_jual' => 275000,
                'batch' => [
                    'nomor_batch' => 'LOT-2026-006',
                    'kadaluarsa' => date('Y-m-d', strtotime('+5 days')), // FEFO Critical warning
                    'lokasi_rak' => 'RAK-C-02'
                ]
            ],
            [
                'sku' => 'PRD-007',
                'nama_produk' => 'Hand Pallet Truck 3 Ton Fork 1220mm',
                'kategori' => 'Peralatan & Perkakas',
                'stok' => 6,
                'stok_minimal' => 2,
                'satuan' => 'Unit',
                'harga_beli' => 3800000,
                'harga_jual' => 4600000,
                'batch' => [
                    'nomor_batch' => 'LOT-2026-007',
                    'kadaluarsa' => '2030-12-31',
                    'lokasi_rak' => 'ZONE-HEAVY'
                ]
            ],
            [
                'sku' => 'PRD-008',
                'nama_produk' => 'Barcode Scanner Wireless 2D QR Bluetooth',
                'kategori' => 'Peralatan & Perkakas',
                'stok' => 18,
                'stok_minimal' => 5,
                'satuan' => 'Unit',
                'harga_beli' => 380000,
                'harga_jual' => 520000,
                'batch' => [
                    'nomor_batch' => 'LOT-2026-008',
                    'kadaluarsa' => '2029-08-20',
                    'lokasi_rak' => 'RAK-D-01'
                ]
            ]
        ];

        foreach ($mockProducts as $p) {
            $catId = $catMap[$p['kategori']] ?? 1;
            $existingProd = $this->db->get_where('produk', ['sku' => $p['sku']])->row_array();

            $prodData = [
                'sku' => $p['sku'],
                'nama_produk' => $p['nama_produk'],
                'kategori_id' => $catId,
                'pemasok_id' => $defaultSupId,
                'stok' => $p['stok'],
                'stok_minimal' => $p['stok_minimal'],
                'deskripsi' => 'Item stok inventaris pergudangan standar.',
                'created_at' => date('Y-m-d H:i:s')
            ];

            if ($existingProd) {
                $this->db->where('id', $existingProd['id'])->update('produk', $prodData);
                $prodId = $existingProd['id'];
            } else {
                $this->db->insert('produk', $prodData);
                $prodId = $this->db->insert_id();
            }

            // Batch lot
            if (!empty($p['batch'])) {
                $existingBatch = $this->db->get_where('batch', ['nomor_batch' => $p['batch']['nomor_batch']])->row_array();
                $bData = [
                    'produk_id' => $prodId,
                    'nomor_batch' => $p['batch']['nomor_batch'],
                    'kapasitas' => $p['stok'] + 50,
                    'jumlah_awal' => $p['stok'] + 50,
                    'stok_saat_ini' => $p['stok'],
                    'tanggal_masuk' => date('Y-m-d', strtotime('-15 days')),
                    'tanggal_kadaluarsa' => $p['batch']['kadaluarsa'],
                    'lokasi_rak' => $p['batch']['lokasi_rak'],
                    'created_at' => date('Y-m-d H:i:s')
                ];
                if ($existingBatch) {
                    $this->db->where('id', $existingBatch['id'])->update('batch', $bData);
                } else {
                    $this->db->insert('batch', $bData);
                }
            }
        }
        echo " [OK] Produk master dan batch lot berhasil disiapkan." . PHP_EOL;

        // 6. Seed Realistic Recent Stock Movements across last 7 days
        $this->db->empty_table('stok_transaksi');

        $sampleMovements = [
            ['sku' => 'PRD-001', 'tipe' => 'masuk', 'qty' => 150, 'days_ago' => 6, 'note' => 'Penerimaan kontainer batch pabrik'],
            ['sku' => 'PRD-001', 'tipe' => 'keluar', 'qty' => 40, 'days_ago' => 5, 'note' => 'Pengiriman pesanan packing warehouse B'],
            ['sku' => 'PRD-004', 'tipe' => 'masuk', 'qty' => 100, 'days_ago' => 4, 'note' => 'Restock modul sensor import'],
            ['sku' => 'PRD-006', 'tipe' => 'masuk', 'qty' => 30, 'days_ago' => 4, 'note' => 'Pengadaan rutin oli maintenance'],
            ['sku' => 'PRD-001', 'tipe' => 'keluar', 'qty' => 60, 'days_ago' => 3, 'note' => 'Pengeluaran logistik cabang Tangerang'],
            ['sku' => 'PRD-002', 'tipe' => 'keluar', 'qty' => 12, 'days_ago' => 2, 'note' => 'Penggunaan departemen packing'],
            ['sku' => 'PRD-004', 'tipe' => 'keluar', 'qty' => 15, 'days_ago' => 2, 'note' => 'Instalasi jalur konveyor 3'],
            ['sku' => 'PRD-008', 'tipe' => 'masuk', 'qty' => 20, 'days_ago' => 1, 'note' => 'Pengadaan alat scanner nirkabel'],
            ['sku' => 'PRD-006', 'tipe' => 'keluar', 'qty' => 5, 'days_ago' => 1, 'note' => 'Servis berkala lift gudang'],
            ['sku' => 'PRD-001', 'tipe' => 'masuk', 'qty' => 200, 'days_ago' => 0, 'note' => 'Restock stok aman awal pekan']
        ];

        foreach ($sampleMovements as $m) {
            $prod = $this->db->get_where('produk', ['sku' => $m['sku']])->row_array();
            if (!$prod) continue;

            $batch = $this->db->get_where('batch', ['produk_id' => $prod['id']])->row_array();
            $batchId = $batch ? $batch['id'] : null;
            $txDate = date('Y-m-d H:i:s', strtotime("-{$m['days_ago']} days -4 hours"));

            $this->db->insert('stok_transaksi', [
                'produk_id' => $prod['id'],
                'batch_id' => $batchId,
                'user_id' => $adminId,
                'tipe' => $m['tipe'],
                'jumlah' => $m['qty'],
                'catatan' => $m['note'],
                'scan_mode' => 'manual',
                'tanggal' => $txDate,
                'created_at' => $txDate
            ]);
        }
        echo " [OK] 10 Transaksi mutasi stok historis berhasil disiapkan." . PHP_EOL;

        echo "=== Seeding Selesai ===" . PHP_EOL;
    }
}
