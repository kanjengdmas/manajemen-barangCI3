<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Application Model
 * Provides common Query Builder abstraction, CRUD helpers, and transactional support.
 */
class MY_Model extends CI_Model {

    /**
     * @var string Database table name
     */
    protected $table = '';

    /**
     * @var string Primary key column name
     */
    protected $primary_key = 'id';

    public function __construct() {
        $this->load->database();
    }

    /**
     * Get all records with optional filtering, sorting, and pagination
     */
    public function get_all($conditions = [], $order_by = null, $limit = null, $offset = null) {
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }

        if ($order_by !== null) {
            $this->db->order_by($order_by);
        }

        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get($this->table)->result_array();
    }

    /**
     * Find single record by primary key
     */
    public function get_by_id($id) {
        return $this->db->get_where($this->table, [$this->primary_key => $id])->row_array();
    }

    /**
     * Find single record by arbitrary conditions
     */
    public function get_where($conditions) {
        return $this->db->get_where($this->table, $conditions)->row_array();
    }

    /**
     * Insert new record and return inserted ID
     */
    public function insert($data) {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    /**
     * Update record by primary key or conditions
     */
    public function update($id, $data) {
        if (is_array($id)) {
            $this->db->where($id);
        } else {
            $this->db->where($this->primary_key, $id);
        }
        return $this->db->update($this->table, $data);
    }

    /**
     * Delete record by primary key or conditions
     */
    public function delete($id) {
        if (is_array($id)) {
            $this->db->where($id);
        } else {
            $this->db->where($this->primary_key, $id);
        }
        return $this->db->delete($this->table);
    }

    /**
     * Count rows matching conditions
     */
    public function count($conditions = []) {
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        return $this->db->count_all_results($this->table);
    }

    // --- Transaction Helper Wrappers ---

    public function trans_start() {
        $this->db->trans_start();
    }

    public function trans_complete() {
        $this->db->trans_complete();
        return $this->db->trans_status();
    }

    public function trans_status() {
        return $this->db->trans_status();
    }

    public function trans_rollback() {
        $this->db->trans_rollback();
    }
}
