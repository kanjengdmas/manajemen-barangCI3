<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends MY_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['Produk_model', 'Batch_model', 'Kategori_model', 'Pemasok_model', 'Stok_model']);
    }

    /**
     * GET /api/dashboard/stats
     */
    public function stats()
    {
        $totalProduk = $this->Produk_model->count();
        $totalStok = $this->Produk_model->get_total_stok();
        $totalKategori = $this->Kategori_model->count();
        $totalPemasok = $this->Pemasok_model->count();
        $totalBatch = $this->Batch_model->count();
        $stokMenipis = $this->Produk_model->count_low_stock();
        $stokHabis = $this->Produk_model->count_out_of_stock();

        $data = [
            'status' => true,
            'data' => [
                'total_produk' => $totalProduk,
                'total_stok' => $totalStok,
                'total_kategori' => $totalKategori,
                'total_pemasok' => $totalPemasok,
                'total_batch' => $totalBatch,
                'produk_stok_menipis' => $stokMenipis,
                'produk_stok_habis' => $stokHabis,
                'trends' => [
                    'total_produk' => ['val' => $totalProduk . ' Item', 'up' => true],
                    'total_stok' => ['val' => number_format($totalStok) . ' Unit', 'up' => true],
                    'stok_menipis' => ['val' => ($stokMenipis > 0 ? $stokMenipis . ' Perlu Restock' : 'Aman'), 'isDanger' => ($stokMenipis > 0)],
                    'stok_habis' => ['val' => ($stokHabis > 0 ? $stokHabis . ' Kosong' : 'Aman'), 'isDanger' => ($stokHabis > 0)]
                ]
            ]
        ];

        $this->json_response($data);
    }

    /**
     * GET /api/dashboard/stok-chart
     */
    public function stok_chart()
    {
        $kategoriId = $this->input->get('kategori_id', true);
        $categories = $this->Produk_model->get_category_distribution();

        $labels = [];
        $values = [];
        $counts = [];

        foreach ($categories as $cat) {
            $labels[] = $cat['nama_kategori'] ?? 'Tanpa Kategori';
            $values[] = (int) ($cat['total_stok'] ?? 0);
            $counts[] = (int) ($cat['total_produk'] ?? 0);
        }

        $data = [
            'status' => true,
            'data' => [
                'labels' => $labels,
                'values' => $values,
                'counts' => $counts,
                'total_categories' => count($categories),
                'total_units' => array_sum($values)
            ]
        ];

        $this->json_response($data);
    }

    /**
     * GET /api/dashboard/low-stock
     */
    public function low_stock()
    {
        $limit = (int) ($this->input->get('limit', true) ?: 5);
        $products = $this->Produk_model->get_low_stock($limit);

        $data = [
            'status' => true,
            'data' => $products
        ];

        $this->json_response($data);
    }

    /**
     * GET /api/dashboard/movement-chart
     */
    public function movement_chart()
    {
        $days = $this->input->get('days', true) ?: '7d';
        $movement = $this->Stok_model->get_movement_data($days);

        $data = [
            'status' => true,
            'data' => $movement
        ];

        $this->json_response($data);
    }

    /**
     * GET /api/dashboard/recent-activities
     */
    public function recent_activities()
    {
        $limit = (int) ($this->input->get('limit', true) ?: 15);
        $activities = $this->Stok_model->get_recent_activities($limit);

        $data = [
            'status' => true,
            'data' => $activities
        ];

        $this->json_response($data);
    }

    /**
     * GET /api/batch/expiring
     */
    public function expiring_batches()
    {
        $days = (int) ($this->input->get('days', true) ?: 30);
        $limit = (int) ($this->input->get('limit', true) ?: 5);
        $batches = $this->Batch_model->get_expiring_batches($days, $limit);

        $data = [
            'status' => true,
            'data' => $batches
        ];

        $this->json_response($data);
    }
}
