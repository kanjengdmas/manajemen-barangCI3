<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Produk_model', 'Batch_model', 'Kategori_model', 'Pemasok_model', 'Stok_model', 'Pengaturan_model']);
    }

    public function index()
    {
        // 1. Gather stats
        $totalProduk = $this->Produk_model->count();
        $totalStok = $this->Produk_model->get_total_stok();
        $totalKategori = $this->Kategori_model->count();
        $totalPemasok = $this->Pemasok_model->count();
        $totalBatch = $this->Batch_model->count();
        $stokMenipis = $this->Produk_model->count_low_stock();
        $stokHabis = $this->Produk_model->count_out_of_stock();

        // 2. Low stock items (top 5)
        $lowStockProducts = $this->Produk_model->get_low_stock(5);

        // 3. Expiring batches (FEFO early warning, 30 days)
        $expiringBatches = $this->Batch_model->get_expiring_batches(30, 4);

        // 4. Movement data (default 7 days)
        $movementData = $this->Stok_model->get_movement_data('7d');

        // 5. Category distribution
        $categoryDistribution = $this->Produk_model->get_category_distribution();

        // 6. Recent activities (15 latest transactions for scrollable stream)
        $recentActivities = $this->Stok_model->get_recent_activities(15);

        // 7. System & user settings
        $settings = [
            'auto_refresh' => $this->Pengaturan_model->get_value('auto_refresh') ?? '1',
            'refresh_interval' => $this->Pengaturan_model->get_value('refresh_interval') ?? '5',
            'show_stock' => $this->Pengaturan_model->get_value('show_stock') ?? '1',
            'enable_fifo' => $this->Pengaturan_model->get_value('enable_fifo') ?? '1'
        ];

        $data = [
            'page_title' => 'Dashboard Gudang & Inventaris',
            'active_menu' => 'dashboard',
            'stats' => [
                'total_produk' => $totalProduk,
                'total_stok' => $totalStok,
                'total_kategori' => $totalKategori,
                'total_pemasok' => $totalPemasok,
                'total_batch' => $totalBatch,
                'produk_stok_menipis' => $stokMenipis,
                'produk_stok_habis' => $stokHabis
            ],
            'low_stock_products' => $lowStockProducts,
            'expiring_batches' => $expiringBatches,
            'movement_data' => $movementData,
            'category_distribution' => $categoryDistribution,
            'recent_activities' => $recentActivities,
            'settings' => $settings
        ];

        $this->render('dashboard/index', $data);
    }
}
