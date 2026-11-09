<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index() {
        $userId = $this->current_user['id'];
        $userTransactions = $this->db->where('user_id', $userId)->count_all_results('stok_transaksi');

        $data = [
            'page_title' => 'Profil Saya & Pengaturan Akun',
            'active_menu' => 'profil',
            'user' => $this->current_user,
            'preferences' => $this->preference->get_user_preferences($userId),
            'stats' => [
                'total_actions' => $userTransactions,
                'total_login' => (int)($this->current_user['total_login'] ?? 1),
                'member_since' => $this->current_user['created_at'],
                'last_updated' => $this->current_user['updated_at'] ?? $this->current_user['created_at'],
            ],
            'client_session' => [
                'ip_address' => $this->input->ip_address(),
                'user_agent' => $this->input->user_agent()
            ]
        ];
        $this->render('profil/index', $data);
    }
}
