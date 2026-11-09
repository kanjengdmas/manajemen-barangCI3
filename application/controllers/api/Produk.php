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

    /**
     * GET /api/produk/export/excel
     */
    public function export_excel() {
        $this->load->library('export_excel');

        $kategoriId = $this->input->get('kategori_id');

        $this->db->select('produk.*, kategori.nama_kategori, pemasok.nama_pemasok');
        $this->db->from('produk');
        $this->db->join('kategori', 'kategori.id = produk.kategori_id', 'left');
        $this->db->join('pemasok', 'pemasok.id = produk.pemasok_id', 'left');
        if (!empty($kategoriId)) {
            $this->db->where('produk.kategori_id', $kategoriId);
        }
        $this->db->order_by('produk.nama_produk', 'ASC');
        $products = $this->db->get()->result_array();

        $headers = ['No', 'Nama Produk', 'SKU', 'Kategori', 'Pemasok / Vendor', 'Stok Saat Ini', 'Stok Minimal', 'Status Stok'];
        $rows = [];
        $no = 1;

        foreach ($products as $p) {
            $stok = (int)($p['stok'] ?? 0);
            $min = (int)($p['stok_minimal'] ?? 0);
            $status = 'Aman';
            if ($stok <= 0) {
                $status = 'Habis / Kosong';
            } elseif ($stok <= $min) {
                $status = 'Menipis';
            }

            $rows[] = [
                $no++,
                $p['nama_produk'],
                $p['sku'],
                $p['nama_kategori'] ?? '-',
                $p['nama_pemasok'] ?? '-',
                $stok,
                $min,
                $status
            ];
        }

        $filename = 'Laporan-Katalog-Produk-' . date('Y-m-d') . '.xlsx';
        $this->export_excel->download($filename, 'Laporan Master Produk & Stok Inventaris', $headers, $rows);
    }
}
