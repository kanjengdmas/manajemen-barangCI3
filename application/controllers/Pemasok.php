<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemasok extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Pemasok_model', 'Produk_model']);
    }

    public function index() {
        $suppliers = $this->Pemasok_model->get_all([], 'id DESC');
        foreach ($suppliers as &$sup) {
            $sup['total_produk'] = $this->Produk_model->count(['pemasok_id' => $sup['id']]);
        }

        $data = [
            'page_title' => 'Master Data Pemasok & Vendor',
            'active_menu' => 'pemasok',
            'suppliers' => $suppliers
        ];
        $this->render('pemasok/index', $data);
    }

    public function create() {
        $data = [
            'page_title' => 'Tambah Pemasok Baru',
            'active_menu' => 'pemasok'
        ];
        $this->render('pemasok/create', $data);
    }

    public function store() {
        $this->form_validation->set_rules('nama_pemasok', 'Nama Pemasok', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('kontak', 'Nomor Kontak', 'trim|required|max_length[50]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors(' ', ' '));
            redirect('pemasok/create');
        }

        $this->Pemasok_model->insert([
            'nama_pemasok' => $this->input->post('nama_pemasok', TRUE),
            'kontak' => $this->input->post('kontak', TRUE),
            'alamat' => $this->input->post('alamat', TRUE),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Pemasok baru berhasil ditambahkan.');
        redirect('pemasok');
    }

    public function edit($id) {
        $supplier = $this->Pemasok_model->get_by_id($id);
        if (!$supplier) {
            show_404();
        }

        $data = [
            'page_title' => 'Edit Pemasok: ' . $supplier['nama_pemasok'],
            'active_menu' => 'pemasok',
            'supplier' => $supplier
        ];
        $this->render('pemasok/edit', $data);
    }

    public function update($id) {
        $this->form_validation->set_rules('nama_pemasok', 'Nama Pemasok', 'trim|required|max_length[150]');
        $this->form_validation->set_rules('kontak', 'Nomor Kontak', 'trim|required|max_length[50]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors(' ', ' '));
            redirect('pemasok/edit/' . $id);
        }

        $this->Pemasok_model->update($id, [
            'nama_pemasok' => $this->input->post('nama_pemasok', TRUE),
            'kontak' => $this->input->post('kontak', TRUE),
            'alamat' => $this->input->post('alamat', TRUE),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Data pemasok berhasil diperbarui.');
        redirect('pemasok');
    }

    public function delete($id) {
        $this->Pemasok_model->delete($id);
        $this->session->set_flashdata('success', 'Pemasok berhasil dihapus.');
        redirect('pemasok');
    }
}
