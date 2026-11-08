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
                'auto_refresh' => (bool)$this->preference->get('auto_refresh', true),
                'refresh_interval' => (int)$this->preference->get('refresh_interval', 5),
                'show_stock' => (bool)$this->preference->get('show_stock', true),
                'date_format' => $this->preference->get('date_format', 'DD/MM/YYYY'),
                'fifo_policy' => (bool)$this->preference->get('fifo_policy', true),
                'default_min_stock' => (int)$this->preference->get('default_min_stock', 10),
                'expiry_warning_days' => (int)$this->preference->get('expiry_warning_days', 30),
                'qr_label_size' => $this->preference->get('qr_label_size', '50x30'),
                'sku_prefix' => $this->preference->get('sku_prefix', 'PRD-'),
                'lot_prefix' => $this->preference->get('lot_prefix', 'LOT-'),
                'page_length' => (int)$this->preference->get('page_length', 10),
                'row_density' => $this->preference->get('row_density', 'normal'),
                'session_timeout' => (int)$this->preference->get('session_timeout', 120),
                'sound_effects' => (bool)$this->preference->get('sound_effects', true)
            ],
            'company' => $this->preference->get_company(),
            'server_info' => [
                'php_version' => PHP_VERSION,
                'ci_version' => CI_VERSION,
                'db_platform' => $this->db->platform(),
                'db_name' => $this->db->database,
                'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Apache/XAMPP',
                'max_execution_time' => ini_get('max_execution_time') . ' detik',
                'memory_limit' => ini_get('memory_limit'),
                'upload_max_filesize' => ini_get('upload_max_filesize')
            ]
        ];
        $this->render('pengaturan/index', $data);
    }
}
