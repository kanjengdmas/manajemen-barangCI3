<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produk_model extends MY_Model {

    protected $table = 'produk';

    public function get_with_relations($id = null) {
        $this->db->select('produk.*, kategori.nama_kategori, pemasok.nama_pemasok');
        $this->db->from($this->table);
        $this->db->join('kategori', 'kategori.id = produk.kategori_id', 'left');
        $this->db->join('pemasok', 'pemasok.id = produk.pemasok_id', 'left');

        if ($id !== null) {
            $this->db->where('produk.id', $id);
            return $this->db->get()->row_array();
        }

        $this->db->order_by('produk.id', 'DESC');
        return $this->db->get()->result_array();
    }

    public function get_low_stock($limit = 5) {
        $this->db->select('produk.*, kategori.nama_kategori');
        $this->db->from($this->table);
        $this->db->join('kategori', 'kategori.id = produk.kategori_id', 'left');
        $this->db->where('produk.stok <= produk.stok_minimal');
        $this->db->order_by('produk.stok', 'ASC');
        if ($limit) {
            $this->db->limit($limit);
        }
        return $this->db->get()->result_array();
    }

    public function count_low_stock() {
        $this->db->from($this->table);
        $this->db->where('stok > 0 AND stok <= stok_minimal', null, false);
        return $this->db->count_all_results();
    }

    public function count_out_of_stock() {
        $this->db->from($this->table);
        $this->db->where('stok <= 0', null, false);
        return $this->db->count_all_results();
    }

    public function get_total_stok() {
        $this->db->select_sum('stok');
        $query = $this->db->get($this->table)->row();
        return (int) ($query->stok ?? 0);
    }

    public function sync_sisa_stok($produkId) {
        $produk = $this->get_by_id($produkId);
        if (!$produk) {
            return false;
        }

        $existing = $this->db->get_where('sisa_stok', ['produk_id' => $produkId])->row_array();
        if ($existing) {
            $this->db->where('produk_id', $produkId);
            return $this->db->update('sisa_stok', [
                'total_stok' => $produk['stok'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            return $this->db->insert('sisa_stok', [
                'produk_id' => $produkId,
                'total_stok' => $produk['stok'],
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    public function get_category_distribution() {
        $this->db->select('kategori.nama_kategori, SUM(produk.stok) as total_stok, COUNT(produk.id) as total_produk');
        $this->db->from($this->table);
        $this->db->join('kategori', 'kategori.id = produk.kategori_id', 'left');
        $this->db->group_by('kategori.id');
        return $this->db->get()->result_array();
    }
}
