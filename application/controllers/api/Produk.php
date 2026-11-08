<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Produk_model', 'Stok_model']);
        $this->load->library('qr_service');
        $this->load->helper('download');
    }

    /**
     * GET /api/produk/(:num)/history
     */
    public function history($id) {
        $history = $this->Stok_model->get_history(['produk_id' => $id]);
        $this->json_response([
            'status' => true,
            'data' => $history
        ]);
    }

    /**
     * POST /api/produk/(:num)/generate-qr
     */
    public function generate_qr($id) {
        $produk = $this->Produk_model->get_by_id($id);
        if (!$produk) {
            $this->json_response(['status' => false, 'message' => 'Produk tidak ditemukan.'], 404);
        }

        $qrData = $produk['sku'];
        $filename = 'prd_' . $produk['sku'] . '.png';
        $path = $this->qr_service->generate($qrData, $filename);

        $this->Produk_model->update($id, ['qr_code' => $path]);

        $this->json_response([
            'status' => true,
            'message' => 'QR Code produk berhasil dibuat.',
            'qr_code_url' => base_url($path),
            'qr_code_path' => $path
        ]);
    }

    /**
     * GET /api/produk/(:num)/download-qr
     */
    public function download_qr($id) {
        $produk = $this->Produk_model->get_by_id($id);
        if (!$produk) {
            show_404();
        }

        $fullPath = FCPATH . $produk['qr_code'];
        if (empty($produk['qr_code']) || !file_exists($fullPath)) {
            // Generate on the fly
            $path = $this->qr_service->generate($produk['sku'], 'prd_' . $produk['sku'] . '.png');
            $this->Produk_model->update($id, ['qr_code' => $path]);
            $fullPath = FCPATH . $path;
        }

        force_download($fullPath, NULL);
    }

    /**
     * POST /api/produk/(:num)/delete-qr
     */
    public function delete_qr($id) {
        $produk = $this->Produk_model->get_by_id($id);
        if (!$produk) {
            $this->json_response(['status' => false, 'message' => 'Produk tidak ditemukan.'], 404);
        }

        if (!empty($produk['qr_code']) && file_exists(FCPATH . $produk['qr_code'])) {
            @unlink(FCPATH . $produk['qr_code']);
        }

        $this->Produk_model->update($id, ['qr_code' => null]);

        $this->json_response([
            'status' => true,
            'message' => 'QR Code berhasil dihapus.'
        ]);
    }
}
