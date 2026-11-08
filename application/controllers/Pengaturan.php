<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan extends MY_Controller {

    public function __construct() {
        parent::__construct();
    }

    public function index() {
        $data = [
            'page_title' => 'Pengaturan Sistem & Pergudangan',
            'active_menu' => 'pengaturan',
            'settings' => [
                'theme' => $this->preference->get('theme', 'light'),
                'language' => $this->preference->get('language', 'id'),
                'auto_refresh' => $this->preference->get('auto_refresh', true),
                'refresh_interval' => $this->preference->get('refresh_interval', 5),
                'show_stock' => $this->preference->get('show_stock', true),
                'date_format' => $this->preference->get('date_format', 'DD/MM/YYYY'),
                'fifo_policy' => $this->preference->get('fifo_policy', true),
                'default_min_stock' => $this->preference->get('default_min_stock', 10),
                'sku_prefix' => $this->preference->get('sku_prefix', 'PRD-'),
                'lot_prefix' => $this->preference->get('lot_prefix', 'LOT-'),
                'page_length' => $this->preference->get('page_length', 10),
                'session_timeout' => $this->preference->get('session_timeout', 120),
                'sound_effects' => $this->preference->get('sound_effects', true)
            ],
            'company' => $this->preference->get_company()
        ];
        $this->render('pengaturan/index', $data);
    }
}
