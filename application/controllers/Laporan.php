<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Laporan extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Stok_model', 'Produk_model']);
    }

    public function index() {
        $data = [
            'page_title' => 'Pusat Ekspor & Laporan Inventaris',
            'active_menu' => 'laporan',
            'products' => $this->Produk_model->get_all([], 'nama_produk ASC')
        ];
        $this->render('laporan/index', $data);
    }
}
