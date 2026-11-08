<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Batch extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Batch_model', 'Produk_model', 'Stok_model']);
        $this->load->library('qr_service');
    }

    public function index() {
        $data = [
            'page_title' => 'Manajemen Lot & Batch Kardus',
            'active_menu' => 'batch',
            'batches' => $this->Batch_model->get_with_relations(),
            'lot_prefix' => $this->preference->get('lot_prefix', 'LOT-')
        ];
        $this->render('batch/index', $data);
    }

    public function create() {
        $data = [
            'page_title' => 'Registrasi Batch Baru',
            'active_menu' => 'batch',
            'products' => $this->Produk_model->get_all([], 'nama_produk ASC'),
            'lot_prefix' => $this->preference->get('lot_prefix', 'LOT-')
        ];
        $this->render('batch/create', $data);
    }

    public function store() {
        $this->form_validation->set_rules('produk_id', 'Produk', 'required|numeric');
        $this->form_validation->set_rules('nomor_batch', 'Nomor Lot/Batch', 'trim|required|is_unique[batch.nomor_batch]');
        $this->form_validation->set_rules('jumlah_awal', 'Jumlah Awal', 'required|numeric');
        $this->form_validation->set_rules('tanggal_masuk', 'Tanggal Masuk', 'required');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors(' ', ' '));
            redirect('batch/create');
        }

        $nomorBatch = $this->input->post('nomor_batch', TRUE);
        $filename = 'batch_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $nomorBatch) . '.png';
        $qrPath = $this->qr_service->generate($nomorBatch, $filename);

        $insertData = [
            'produk_id' => $this->input->post('produk_id'),
            'nomor_batch' => $nomorBatch,
            'qr_code' => $qrPath,
            'kapasitas' => (int)$this->input->post('kapasitas'),
            'jumlah_awal' => (int)$this->input->post('jumlah_awal'),
            'stok_saat_ini' => (int)$this->input->post('jumlah_awal'),
            'tanggal_masuk' => $this->input->post('tanggal_masuk'),
            'tanggal_kadaluarsa' => $this->input->post('tanggal_kadaluarsa') ? $this->input->post('tanggal_kadaluarsa') : null,
            'lokasi_rak' => $this->input->post('lokasi_rak', TRUE),
            'created_at' => date('Y-m-d H:i:s')
        ];

        $batchId = $this->Batch_model->insert($insertData);

        // Also update product stock
        $this->db->where('id', $insertData['produk_id']);
        $this->db->set('stok', 'stok + ' . $insertData['jumlah_awal'], false);
        $this->db->update('produk');
        $this->Produk_model->sync_sisa_stok($insertData['produk_id']);

        // Record initial stock in transaction
        $this->Stok_model->insert([
            'produk_id' => $insertData['produk_id'],
            'batch_id' => $batchId,
            'user_id' => $this->current_user['id'],
            'tipe' => 'masuk',
            'jumlah' => $insertData['jumlah_awal'],
            'catatan' => 'Penerimaan batch awal: ' . $nomorBatch,
            'scan_mode' => 'manual',
            'tanggal' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Batch baru berhasil didaftarkan.');
        redirect('batch');
    }

    public function detail($id) {
        $batch = $this->Batch_model->get_with_relations($id);
        if (!$batch) {
            show_404();
        }

        $data = [
            'page_title' => 'Detail Batch: ' . $batch['nomor_batch'],
            'active_menu' => 'batch',
            'batch' => $batch,
            'history' => $this->Stok_model->get_history(['batch_id' => $id])
        ];
        $this->render('batch/detail', $data);
    }

    public function delete($id) {
        $batch = $this->Batch_model->get_by_id($id);
        if ($batch) {
            if (!empty($batch['qr_code']) && file_exists(FCPATH . $batch['qr_code'])) {
                @unlink(FCPATH . $batch['qr_code']);
            }
            $this->Batch_model->delete($id);
            $this->session->set_flashdata('success', 'Data batch berhasil dihapus.');
        }
        redirect('batch');
    }
}
