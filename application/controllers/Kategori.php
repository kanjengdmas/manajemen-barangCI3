<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Kategori_model', 'Produk_model']);
    }

    public function index() {
        $categories = $this->Kategori_model->get_all([], 'id DESC');
        foreach ($categories as &$cat) {
            $cat['total_produk'] = $this->Produk_model->count(['kategori_id' => $cat['id']]);
        }

        $data = [
            'page_title' => 'Master Data Kategori',
            'active_menu' => 'kategori',
            'categories' => $categories
        ];
        $this->render('kategori/index', $data);
    }

    public function create() {
        $data = [
            'page_title' => 'Tambah Kategori Baru',
            'active_menu' => 'kategori'
        ];
        $this->render('kategori/create', $data);
    }

    public function store() {
        $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'trim|required|max_length[100]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors(' ', ' '));
            redirect('kategori');
        }

        $this->Kategori_model->insert([
            'nama_kategori' => $this->input->post('nama_kategori', TRUE),
            'deskripsi' => $this->input->post('deskripsi', TRUE),
            'created_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Kategori baru berhasil ditambahkan.');
        redirect('kategori');
    }

    public function edit($id) {
        $category = $this->Kategori_model->get_by_id($id);
        if (!$category) {
            show_404();
        }

        $data = [
            'page_title' => 'Edit Kategori: ' . $category['nama_kategori'],
            'active_menu' => 'kategori',
            'category' => $category
        ];
        $this->render('kategori/edit', $data);
    }

    public function update($id) {
        $this->form_validation->set_rules('nama_kategori', 'Nama Kategori', 'trim|required|max_length[100]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors(' ', ' '));
            redirect('kategori');
        }

        $this->Kategori_model->update($id, [
            'nama_kategori' => $this->input->post('nama_kategori', TRUE),
            'deskripsi' => $this->input->post('deskripsi', TRUE),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        $this->session->set_flashdata('success', 'Kategori berhasil diperbarui.');
        redirect('kategori');
    }

    public function delete($id) {
        $this->Kategori_model->delete($id);
        $this->session->set_flashdata('success', 'Kategori berhasil dihapus.');
        redirect('kategori');
    }
}
