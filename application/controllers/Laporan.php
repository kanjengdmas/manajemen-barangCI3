<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Stok_model', 'Produk_model', 'Batch_model', 'Kategori_model']);
    }

    public function index() {
        $totalMasuk = $this->db->select_sum('jumlah')->where('tipe', 'masuk')->get('stok_transaksi')->row_array()['jumlah'] ?? 0;
        $totalKeluar = $this->db->select_sum('jumlah')->where('tipe', 'keluar')->get('stok_transaksi')->row_array()['jumlah'] ?? 0;

        $data = [
            'page_title' => 'Pusat Ekspor & Laporan Inventaris',
            'active_menu' => 'laporan',
            'products' => $this->Produk_model->get_all([], 'nama_produk ASC'),
            'categories' => $this->Kategori_model->get_all([], 'nama_kategori ASC'),
            'stats' => [
                'total_transactions' => $this->Stok_model->count(),
                'total_products' => $this->Produk_model->count(),
                'total_batches' => $this->Batch_model->count(),
                'total_stock' => $this->Produk_model->get_total_stok(),
                'total_masuk' => (int)$totalMasuk,
                'total_keluar' => (int)$totalKeluar,
            ]
        ];
        $this->render('laporan/index', $data);
    }
}
