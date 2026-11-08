<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Settings extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->dbutil();
        $this->load->helper('download');
    }

    /**
     * GET /api/settings
     */
    public function index() {
        $settings = [
            'theme' => $this->preference->get('theme', 'light'),
            'language' => $this->preference->get('language', 'id'),
            'auto_refresh' => (bool)$this->preference->get('auto_refresh', true),
            'refresh_interval' => (int)$this->preference->get('refresh_interval', 5),
            'show_stock' => (bool)$this->preference->get('show_stock', true),
            'date_format' => $this->preference->get('date_format', 'DD/MM/YYYY'),
            'fifo_policy' => (bool)$this->preference->get('fifo_policy', true),
            'default_min_stock' => (int)$this->preference->get('default_min_stock', 10),
            'sku_prefix' => $this->preference->get('sku_prefix', 'PRD-'),
            'lot_prefix' => $this->preference->get('lot_prefix', 'LOT-'),
            'page_length' => (int)$this->preference->get('page_length', 10),
            'session_timeout' => (int)$this->preference->get('session_timeout', 120),
            'sound_effects' => (bool)$this->preference->get('sound_effects', true)
        ];

        $this->json_response([
            'status' => true,
            'data' => $settings
        ]);
    }

    /**
     * POST /api/settings/save
     */
    public function save() {
        $json = json_decode($this->input->raw_input_stream, true);
        $data = is_array($json) ? $json : $this->input->post();

        if (empty($data)) {
            $this->json_response(['status' => false, 'message' => 'Data pengaturan tidak valid.'], 400);
        }

        foreach ($data as $key => $value) {
            $this->preference->set($key, $value);
        }

        $this->json_response([
            'status' => true,
            'message' => 'Pengaturan sistem berhasil disimpan secara otomatis.'
        ]);
    }

    /**
     * GET /api/settings/company
     */
    public function company() {
        $this->json_response([
            'status' => true,
            'data' => $this->preference->get_company()
        ]);
    }

    /**
     * POST /api/settings/company
     */
    public function save_company() {
        $json = json_decode($this->input->raw_input_stream, true);
        $data = is_array($json) ? $json : $this->input->post();

        if (empty($data)) {
            $this->json_response(['status' => false, 'message' => 'Data identitas perusahaan tidak boleh kosong.'], 400);
        }

        $this->preference->save_company($data);

        $this->json_response([
            'status' => true,
            'message' => 'Profil perusahaan & kop surat berhasil disimpan.',
            'data' => $this->preference->get_company()
        ]);
    }

    /**
     * GET /api/settings/backup
     * JSON snapshot backup
     */
    public function backup() {
        $snapshot = [
            'meta' => [
                'app' => 'Manajemen Barang',
                'version' => '2.6.0',
                'exported_at' => date('Y-m-d H:i:s'),
                'operator' => $this->current_user['name']
            ],
            'kategori' => $this->db->get('kategori')->result_array(),
            'pemasok' => $this->db->get('pemasok')->result_array(),
            'produk' => $this->db->get('produk')->result_array(),
            'batch' => $this->db->get('batch')->result_array(),
            'stok_transaksi' => $this->db->get('stok_transaksi')->result_array(),
            'pengaturan' => $this->db->get('pengaturan')->result_array()
        ];

        $filename = 'backup-gudang-' . date('Y-m-d-His') . '.json';
        force_download($filename, json_encode($snapshot, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    }

    /**
     * GET /api/settings/backup-sql
     * SQL Dump backup 1-click
     */
    public function backup_sql() {
        $prefs = [
            'format' => 'txt',
            'filename' => 'backup-gudang-' . date('Y-m-d-His') . '.sql',
            'add_drop' => TRUE,
            'add_insert' => TRUE,
            'newline' => "\n"
        ];

        $backup = $this->dbutil->backup($prefs);
        $filename = 'backup-gudang-' . date('Y-m-d-His') . '.sql';
        force_download($filename, $backup);
    }

    /**
     * POST /api/settings/clear-cache
     */
    public function clear_cache() {
        $this->load->driver('cache', ['adapter' => 'file']);
        $this->cache->clean();

        $this->json_response([
            'status' => true,
            'message' => 'Cache sistem dan memori query berhasil dikosongkan.'
        ]);
    }
}
