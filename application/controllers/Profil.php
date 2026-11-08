<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profil extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    public function index() {
        $data = [
            'page_title' => 'Profil Saya & Preferensi Akun',
            'active_menu' => 'profil',
            'user' => $this->current_user,
            'preferences' => $this->preference->get_user_preferences($this->current_user['id'])
        ];
        $this->render('profil/index', $data);
    }
}
