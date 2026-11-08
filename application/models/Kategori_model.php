<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kategori_model extends MY_Model {

    protected $table = 'kategori';

    public function count_all()
    {
        return $this->db->count_all($this->table);
    }
}
