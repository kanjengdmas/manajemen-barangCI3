<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Batch_model extends MY_Model {

    protected $table = 'batch';

    public function get_with_relations($id = null) {
        $this->db->select('batch.*, produk.nama_produk, produk.sku');
        $this->db->from($this->table);
        $this->db->join('produk', 'produk.id = batch.produk_id', 'left');

        if ($id !== null) {
            $this->db->where('batch.id', $id);
            $row = $this->db->get()->row_array();
            return $row ? $this->append_status($row) : null;
        }

        $this->db->order_by('batch.id', 'DESC');
        $rows = $this->db->get()->result_array();
        foreach ($rows as &$row) {
            $row = $this->append_status($row);
        }
        return $rows;
    }

    /**
     * Compute dynamic batch status based on stock availability (FIFO)
     */
    public function append_status(array $batch) {
        $stok = (int)($batch['stok_saat_ini'] ?? 0);
        $jumlahAwal = (int)($batch['jumlah_awal'] ?? 0);

        if ($stok <= 0) {
            $batch['status'] = 'habis';
        } elseif ($jumlahAwal > 0 && $stok <= ($jumlahAwal * 0.2)) {
            $batch['status'] = 'hampir_habis';
        } else {
            $batch['status'] = 'tersedia';
        }

        return $batch;
    }

    /**
     * Get batches for product ordered by FIFO recommendation (First In, First Out)
     */
    public function get_by_produk_fifo($produkId, $onlyAvailable = true) {
        $this->db->where('produk_id', $produkId);
        if ($onlyAvailable) {
            $this->db->where('stok_saat_ini >', 0);
        }
        $this->db->order_by('tanggal_masuk ASC, id ASC');
        return $this->db->get($this->table)->result_array();
    }

    /**
     * Search batch by QR code or batch code
     */
    public function find_by_code_or_qr($code) {
        $this->db->select('batch.*, produk.nama_produk, produk.sku, produk.stok as total_stok_produk');
        $this->db->from($this->table);
        $this->db->join('produk', 'produk.id = batch.produk_id', 'left');
        $this->db->group_start();
        $this->db->where('batch.nomor_batch', $code);
        $this->db->or_where('batch.qr_code', $code);
        $this->db->group_end();
        return $this->db->get()->row_array();
    }

    /**
     * Get oldest active batches for FIFO priority recommendation (Oldest In First)
     */
    public function get_fifo_priority_batches($limit = 5) {
        $this->db->select('batch.*, produk.nama_produk, produk.sku, DATEDIFF(CURDATE(), batch.tanggal_masuk) as days_in_warehouse');
        $this->db->from($this->table);
        $this->db->join('produk', 'produk.id = batch.produk_id', 'inner');
        $this->db->where('batch.stok_saat_ini >', 0);
        $this->db->order_by('batch.tanggal_masuk', 'ASC');
        $this->db->order_by('batch.id', 'ASC');
        $this->db->limit($limit);

        return $this->db->get()->result_array();
    }

    /**
     * Alias for backward compatibility with dashboard polling
     */
    public function get_expiring_batches($days = 30, $limit = 5) {
        return $this->get_fifo_priority_batches($limit);
    }
}
