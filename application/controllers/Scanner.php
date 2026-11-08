<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Scanner extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Produk_model', 'Batch_model']);
    }

    public function index() {
        $data = [
            'page_title' => 'Pemindai Kamera QR Code Fisik',
            'active_menu' => 'scan',
            'sound_effects' => (bool)$this->preference->get('sound_effects', true)
        ];
        $this->render('scanner/index', $data);
    }
}
