<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pengaturan_model extends MY_Model {
    protected $table = 'pengaturan';

    public function get_value($key, $userId = null) {
        $this->db->where('kunci', $key);
        if ($userId !== null) {
            $this->db->where('user_id', $userId);
        } else {
            $this->db->where('user_id IS NULL', null, false);
        }
        $row = $this->db->get($this->table)->row_array();
        if ($row) {
            $val = json_decode($row['nilai'], true);
            return (json_last_error() === JSON_ERROR_NONE) ? $val : $row['nilai'];
        }
        return null;
    }
}
