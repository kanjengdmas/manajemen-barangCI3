<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends MY_Model {

    protected $table = 'users';

    public function authenticate($email, $password) {
        $user = $this->db->get_where($this->table, ['email' => $email])->row_array();
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function increment_login($userId) {
        $this->db->where('id', $userId);
        $this->db->set('total_login', 'total_login + 1', false);
        return $this->db->update($this->table);
    }

    public function update_password($userId, $newHash) {
        return $this->update($userId, [
            'password' => $newHash,
            'updated_at' => date('Y-m-d H:i:s')
        ]);
    }
}
