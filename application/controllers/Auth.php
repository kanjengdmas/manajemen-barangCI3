<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends Public_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    /**
     * Show login form
     */
    public function index() {
        if ($this->session->userdata('logged_in')) {
            redirect('dashboard');
        }

        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            return $this->attempt();
        }

        $data = [
            'page_title' => 'Masuk ke Sistem — Manajemen Barang',
            'csrf_token_name' => $this->security->get_csrf_token_name(),
            'csrf_hash' => $this->security->get_csrf_hash()
        ];
        $this->load->view('auth/login', $data);
    }

    /**
     * Process login authentication attempt
     */
    public function attempt() {
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
        $this->form_validation->set_rules('password', 'Password', 'required');

        if ($this->form_validation->run() === FALSE) {
            if ($this->input->is_ajax_request()) {
                $this->json_response([
                    'status' => false,
                    'message' => validation_errors(' ', ' ')
                ], 422);
            }
            $this->session->set_flashdata('error', validation_errors(' ', ' '));
            redirect('login');
        }

        $email = $this->input->post('email', TRUE);
        $password = $this->input->post('password');

        $user = $this->User_model->authenticate($email, $password);

        if (!$user) {
            if ($this->input->is_ajax_request()) {
                $this->json_response([
                    'status' => false,
                    'message' => 'Email atau password yang Anda masukkan salah.'
                ], 401);
            }
            $this->session->set_flashdata('error', 'Email atau password salah.');
            redirect('login');
        }

        // Set session
        $this->session->set_userdata([
            'user_id' => $user['id'],
            'user_name' => $user['name'],
            'user_email' => $user['email'],
            'user_role' => $user['role'],
            'logged_in' => TRUE,
            'last_activity_time' => time()
        ]);

        // Increment login counter
        $this->User_model->increment_login($user['id']);

        if ($this->input->is_ajax_request()) {
            $this->json_response([
                'status' => true,
                'message' => 'Login berhasil! Mengalihkan ke dashboard...',
                'redirect' => site_url('dashboard')
            ]);
        }

        redirect('dashboard');
    }

    /**
     * Logout and destroy session
     */
    public function logout() {
        $this->session->sess_destroy();
        redirect('login');
    }
}
