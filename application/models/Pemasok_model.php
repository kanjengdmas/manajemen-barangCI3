<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemasok_model extends MY_Model {
    protected $table = 'pemasok';

    public function count_all() {
        return $this->db->count_all($this->table);
    }
}
