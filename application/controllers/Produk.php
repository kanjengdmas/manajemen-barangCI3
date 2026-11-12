<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Produk_model', 'Kategori_model', 'Pemasok_model', 'Batch_model']);
        $this->load->library('qr_service');
    }

    public function index() {
        $products = $this->Produk_model->get_with_relations();

        // Auto-heal: pastikan setiap produk memiliki QR Code fisik valid
        foreach ($products as &$p) {
            if (empty($p['qr_code']) || !file_exists(FCPATH . $p['qr_code'])) {
                $filename = 'prd_' . $p['sku'] . '.png';
                $p['qr_code'] = $this->qr_service->generate($p['sku'], $filename);
                $this->Produk_model->update($p['id'], ['qr_code' => $p['qr_code']]);
            }
        }
        unset($p);

        $data = [
            'page_title' => 'Master Data Produk',
            'active_menu' => 'produk',
            'products' => $products,
            'sku_prefix' => $this->preference->get('sku_prefix', 'PRD-')
        ];
        $this->render('produk/index', $data);
    }

    public function create() {
        $data = [
            'page_title' => 'Tambah Produk Baru',
            'active_menu' => 'produk',
            'categories' => $this->Kategori_model->get_all([], 'nama_kategori ASC'),
            'suppliers' => $this->Pemasok_model->get_all([], 'nama_pemasok ASC'),
            'default_min_stock' => $this->preference->get('default_min_stock', 10),
            'sku_prefix' => $this->preference->get('sku_prefix', 'PRD-')
        ];
        $this->render('produk/create', $data);
    }

    public function store() {
        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('sku', 'Kode SKU', 'trim|required|max_length[50]|is_unique[produk.sku]');
        $this->form_validation->set_rules('kategori_id', 'Kategori', 'required|numeric');
        $this->form_validation->set_rules('stok_minimal', 'Stok Minimal', 'required|numeric');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors(' ', ' '));
            redirect('produk/create');
        }

        $sku = $this->input->post('sku', TRUE);
        $qrPath = $this->qr_service->generate($sku, 'prd_' . $sku . '.png');

        $insertData = [
            'nama_produk' => $this->input->post('nama_produk', TRUE),
            'sku' => $sku,
            'deskripsi' => $this->input->post('deskripsi', TRUE),
            'kategori_id' => $this->input->post('kategori_id'),
            'pemasok_id' => $this->input->post('pemasok_id') ? $this->input->post('pemasok_id') : null,
            'stok' => (int)$this->input->post('stok_awal'),
            'stok_minimal' => (int)$this->input->post('stok_minimal'),
            'qr_code' => $qrPath,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $productId = $this->Produk_model->insert($insertData);
        $this->Produk_model->sync_sisa_stok($productId);

        $this->session->set_flashdata('success', 'Produk berhasil ditambahkan.');
        redirect('produk');
    }

    public function edit($id) {
        $product = $this->Produk_model->get_by_id($id);
        if (!$product) {
            show_404();
        }

        $data = [
            'page_title' => 'Edit Produk: ' . $product['nama_produk'],
            'active_menu' => 'produk',
            'product' => $product,
            'categories' => $this->Kategori_model->get_all([], 'nama_kategori ASC'),
            'suppliers' => $this->Pemasok_model->get_all([], 'nama_pemasok ASC')
        ];
        $this->render('produk/edit', $data);
    }

    public function update($id) {
        $product = $this->Produk_model->get_by_id($id);
        if (!$product) {
            show_404();
        }

        $this->form_validation->set_rules('nama_produk', 'Nama Produk', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('kategori_id', 'Kategori', 'required|numeric');
        $this->form_validation->set_rules('stok_minimal', 'Stok Minimal', 'required|numeric');

        $sku = $this->input->post('sku', TRUE);
        if ($sku !== $product['sku']) {
            $this->form_validation->set_rules('sku', 'Kode SKU', 'trim|required|max_length[50]|is_unique[produk.sku]');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors(' ', ' '));
            redirect('produk/edit/' . $id);
        }

        $updateData = [
            'nama_produk' => $this->input->post('nama_produk', TRUE),
            'sku' => $sku,
            'deskripsi' => $this->input->post('deskripsi', TRUE),
            'kategori_id' => $this->input->post('kategori_id'),
            'pemasok_id' => $this->input->post('pemasok_id') ? $this->input->post('pemasok_id') : null,
            'stok_minimal' => (int)$this->input->post('stok_minimal'),
            'updated_at' => date('Y-m-d H:i:s')
        ];

        $this->Produk_model->update($id, $updateData);
        $this->session->set_flashdata('success', 'Data produk berhasil diperbarui.');
        redirect('produk');
    }

    public function delete($id) {
        $product = $this->Produk_model->get_by_id($id);
        if ($product) {
            if (!empty($product['qr_code']) && file_exists(FCPATH . $product['qr_code'])) {
                @unlink(FCPATH . $product['qr_code']);
            }
            $this->Produk_model->delete($id);
            $this->session->set_flashdata('success', 'Produk berhasil dihapus.');
        }
        redirect('produk');
    }
}
