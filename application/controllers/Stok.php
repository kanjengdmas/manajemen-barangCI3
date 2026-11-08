<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Stok_model', 'Produk_model', 'Batch_model']);
    }

    public function index() {
        $data = [
            'page_title' => 'Riwayat Mutasi Keluar & Masuk',
            'active_menu' => 'stok',
            'products' => $this->Produk_model->get_all([], 'nama_produk ASC')
        ];
        $this->render('stok/index', $data);
    }

    public function masuk() {
        $data = [
            'page_title' => 'Pencatatan Stok Masuk',
            'active_menu' => 'stok_masuk',
            'products' => $this->Produk_model->get_all([], 'nama_produk ASC'),
            'user_prefs' => $this->preference->get_user_preferences($this->current_user['id'])
        ];
        $this->render('stok/masuk', $data);
    }

    public function masuk_store() {
        $this->form_validation->set_rules('produk_id', 'Produk', 'required|numeric');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|numeric|greater_than[0]');

        if ($this->form_validation->run() === FALSE) {
            if ($this->input->is_ajax_request()) {
                $this->json_response(['status' => false, 'message' => validation_errors(' ', ' ')], 422);
            }
            $this->session->set_flashdata('error', validation_errors(' ', ' '));
            redirect('stok/masuk');
        }

        $txId = $this->Stok_model->record_masuk([
            'produk_id' => $this->input->post('produk_id'),
            'batch_id' => $this->input->post('batch_id') ? $this->input->post('batch_id') : null,
            'user_id' => $this->current_user['id'],
            'jumlah' => (int)$this->input->post('jumlah'),
            'catatan' => $this->input->post('catatan', TRUE),
            'scan_mode' => $this->input->post('scan_mode') ?? 'manual'
        ]);

        if ($txId) {
            if ($this->input->is_ajax_request()) {
                $this->json_response([
                    'status' => true,
                    'message' => 'Transaksi stok masuk berhasil dicatat.',
                    'id' => $txId
                ]);
            }
            $this->session->set_flashdata('success', 'Stok masuk berhasil dicatat.');
            redirect('stok');
        }

        if ($this->input->is_ajax_request()) {
            $this->json_response(['status' => false, 'message' => 'Gagal mencatat mutasi stok masuk.'], 500);
        }
        $this->session->set_flashdata('error', 'Gagal mencatat mutasi stok masuk.');
        redirect('stok/masuk');
    }

    public function keluar() {
        $data = [
            'page_title' => 'Pencatatan Stok Keluar (FIFO)',
            'active_menu' => 'stok_keluar',
            'products' => $this->Produk_model->get_all(['stok >' => 0], 'nama_produk ASC'),
            'fifo_policy' => (bool)$this->preference->get('fifo_policy', true),
            'user_prefs' => $this->preference->get_user_preferences($this->current_user['id'])
        ];
        $this->render('stok/keluar', $data);
    }

    public function keluar_store() {
        $this->form_validation->set_rules('produk_id', 'Produk', 'required|numeric');
        $this->form_validation->set_rules('jumlah', 'Jumlah', 'required|numeric|greater_than[0]');

        if ($this->form_validation->run() === FALSE) {
            if ($this->input->is_ajax_request()) {
                $this->json_response(['status' => false, 'message' => validation_errors(' ', ' ')], 422);
            }
            $this->session->set_flashdata('error', validation_errors(' ', ' '));
            redirect('stok/keluar');
        }

        $result = $this->Stok_model->record_keluar([
            'produk_id' => $this->input->post('produk_id'),
            'batch_id' => $this->input->post('batch_id') ? $this->input->post('batch_id') : null,
            'user_id' => $this->current_user['id'],
            'jumlah' => (int)$this->input->post('jumlah'),
            'catatan' => $this->input->post('catatan', TRUE),
            'scan_mode' => $this->input->post('scan_mode') ?? 'manual'
        ]);

        if ($result['status']) {
            if ($this->input->is_ajax_request()) {
                $this->json_response([
                    'status' => true,
                    'message' => 'Transaksi pengeluaran stok berhasil dicatat.',
                    'id' => $result['id']
                ]);
            }
            $this->session->set_flashdata('success', 'Stok keluar berhasil dicatat.');
            redirect('stok');
        }

        if ($this->input->is_ajax_request()) {
            $this->json_response(['status' => false, 'message' => $result['message']], 422);
        }
        $this->session->set_flashdata('error', $result['message']);
        redirect('stok/keluar');
    }

    /**
     * Bukti Serah Terima / Surat Jalan
     */
    public function bukti($id) {
        $transaction = $this->Stok_model->get_transaction_detail($id);
        if (!$transaction) {
            show_404();
        }

        $data = [
            'page_title' => 'Bukti Serah Terima Mutasi #' . str_pad($transaction['id'], 6, '0', STR_PAD_LEFT),
            'tx' => $transaction,
            'company' => get_company_profile(),
            'printed_at' => format_date_by_preference(date('Y-m-d H:i:s'), null, true)
        ];

        $this->load->view('stok/bukti', $data);
    }
}
