<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Batch extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Batch_model', 'Stok_model', 'Produk_model']);
        $this->load->library('qr_service');
        $this->load->helper('download');
    }

    /**
     * GET /api/batch/(:num)/history
     */
    public function history($id) {
        $history = $this->Stok_model->get_history(['batch_id' => $id]);
        $this->json_response([
            'status' => true,
            'data' => $history
        ]);
    }

    /**
     * GET /api/batch/scan/(:any)
     */
    public function scan($code) {
        $batch = $this->Batch_model->find_by_code_or_qr(urldecode($code));
        if ($batch) {
            $this->json_response([
                'status' => true,
                'type' => 'batch',
                'data' => $batch
            ]);
        }

        // Also check if code belongs to a Product SKU or QR
        $produk = $this->Produk_model->get_where(['sku' => urldecode($code)]);
        if ($produk) {
            $this->json_response([
                'status' => true,
                'type' => 'produk',
                'data' => $produk
            ]);
        }

        $this->json_response([
            'status' => false,
            'message' => 'Kode QR atau Batch tidak ditemukan dalam sistem.'
        ], 404);
    }

    /**
     * GET /api/batch/produk/(:num)/batches
     * Returns batches sorted by FIFO
     */
    public function by_produk($produkId) {
        $batches = $this->Batch_model->get_by_produk_fifo($produkId);
        $this->json_response([
            'status' => true,
            'data' => $batches
        ]);
    }

    /**
     * GET /api/batch/recommendations?produk_id=X
     * Returns batches prioritized by FIFO recommendation
     */
    public function recommendations() {
        $produkId = (int)$this->input->get('produk_id');
        $batches = $this->Batch_model->get_by_produk_fifo($produkId);
        $this->json_response([
            'status' => true,
            'data' => $batches
        ]);
    }

    /**
     * POST /api/batch/(:num)/generate-qr
     */
    public function generate_qr($id) {
        $batch = $this->Batch_model->get_by_id($id);
        if (!$batch) {
            $this->json_response(['status' => false, 'message' => 'Batch tidak ditemukan.'], 404);
        }

        $qrData = $batch['nomor_batch'];
        $filename = 'batch_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $batch['nomor_batch']) . '.png';
        $path = $this->qr_service->generate($qrData, $filename);

        $this->Batch_model->update($id, ['qr_code' => $path]);

        $this->json_response([
            'status' => true,
            'message' => 'QR Code batch berhasil dibuat.',
            'qr_code_url' => base_url($path),
            'qr_code_path' => $path
        ]);
    }

    /**
     * GET /api/batch/(:num)/download-qr
     */
    public function download_qr($id) {
        $batch = $this->Batch_model->get_by_id($id);
        if (!$batch) {
            show_404();
        }

        $fullPath = FCPATH . $batch['qr_code'];
        if (empty($batch['qr_code']) || !file_exists($fullPath)) {
            $filename = 'batch_' . preg_replace('/[^A-Za-z0-9_\-]/', '_', $batch['nomor_batch']) . '.png';
            $path = $this->qr_service->generate($batch['nomor_batch'], $filename);
            $this->Batch_model->update($id, ['qr_code' => $path]);
            $fullPath = FCPATH . $path;
        }

        force_download($fullPath, NULL);
    }

    /**
     * GET /api/batch/export/excel
     */
    public function export_excel() {
        $this->load->library('export_excel');

        $batches = $this->Batch_model->get_with_relations();

        $headers = ['No', 'Nomor Batch / Lot', 'Nama Produk', 'SKU', 'Kapasitas', 'Jumlah Awal', 'Stok Saat Ini', 'Lokasi Rak', 'Tanggal Masuk (FIFO)', 'Status'];
        $rows = [];
        $no = 1;

        foreach ($batches as $b) {
            $statusLabel = ucfirst(str_replace('_', ' ', $b['status'] ?? 'tersedia'));

            $rows[] = [
                $no++,
                $b['nomor_batch'],
                $b['nama_produk'] ?? '-',
                $b['sku'] ?? '-',
                $b['kapasitas'],
                $b['jumlah_awal'],
                $b['stok_saat_ini'],
                $b['lokasi_rak'] ?? '-',
                !empty($b['tanggal_masuk']) ? format_date_by_preference($b['tanggal_masuk']) : '-',
                $statusLabel
            ];
        }

        $filename = 'Laporan-Batch-Lot-Gudang-' . date('Y-m-d') . '.xlsx';
        $this->export_excel->download($filename, 'Laporan Master Batch & Lokasi Rak Kardus', $headers, $rows);
    }
}
