<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Base Application Controller (Protected)
 * Implements session guard, timeout check, AJAX 401 handling, and layout rendering.
 */
class MY_Controller extends CI_Controller {

    /**
     * @var array|null Logged in user data
     */
    protected $current_user = null;

    public function __construct() {
        parent::__construct();

        // 1. Check if user is logged in
        $userId = $this->session->userdata('user_id');
        if (!$userId) {
            // Auto authenticate admin in development for instant preview
            if (ENVIRONMENT === 'development') {
                $defaultAdmin = $this->db->get_where('users', ['email' => 'admin@admin.com'])->row();
                if ($defaultAdmin) {
                    $this->session->set_userdata([
                        'user_id' => $defaultAdmin->id,
                        'user_name' => $defaultAdmin->name,
                        'user_email' => $defaultAdmin->email,
                        'user_role' => $defaultAdmin->role,
                        'last_activity_time' => time()
                    ]);
                    $userId = $defaultAdmin->id;
                }
            }
            if (!$userId) {
                $this->handle_unauthorized('Silakan login terlebih dahulu.');
                return;
            }
        }

        // 2. Validate session timeout if configured
        $this->check_session_timeout();

        // 3. Load user details
        $this->load->model('User_model');
        $this->current_user = $this->User_model->get_by_id($userId);

        if (!$this->current_user) {
            $this->session->sess_destroy();
            $this->handle_unauthorized('Akun tidak ditemukan atau telah dinonaktifkan.');
            return;
        }

        // Update last activity timestamp in session
        $this->session->set_userdata('last_activity_time', time());
    }

    /**
     * Handle unauthenticated or expired requests
     */
    protected function handle_unauthorized($message = 'Sesi telah berakhir.') {
        if ($this->input->is_ajax_request() || $this->is_json_request()) {
            $this->output
                ->set_status_header(401)
                ->set_content_type('application/json')
                ->set_output(json_encode([
                    'status' => false,
                    'message' => $message,
                    'redirect' => site_url('login')
                ]))
                ->_display();
            exit;
        }

        $this->session->set_flashdata('error', $message);
        redirect('login');
    }

    /**
     * Check session timeout against system preferences
     */
    protected function check_session_timeout() {
        $lastActivity = $this->session->userdata('last_activity_time');
        if (!$lastActivity) {
            $this->session->set_userdata('last_activity_time', time());
            return;
        }

        // Default timeout: 120 minutes, unless specified in settings
        $timeoutMinutes = 120;
        if (isset($this->preference)) {
            $timeoutMinutes = (int) $this->preference->get('session_timeout', 120);
        }

        $timeoutSeconds = $timeoutMinutes * 60;
        if ((time() - $lastActivity) > $timeoutSeconds) {
            $this->session->sess_destroy();
            $this->handle_unauthorized('Sesi login telah habis karena tidak ada aktivitas.');
        }
    }

    /**
     * Determine if incoming request expects JSON
     */
    protected function is_json_request() {
        $accept = $this->input->get_request_header('Accept', TRUE);
        $contentType = $this->input->get_request_header('Content-Type', TRUE);
        return (stripos($accept, 'application/json') !== false || stripos($contentType, 'application/json') !== false);
    }

    /**
     * Output standardized JSON response with CSRF token for seamless AJAX chaining
     */
    protected function json_response($data = [], $statusCode = 200) {
        // Include updated CSRF token
        if (is_array($data)) {
            $data['csrf_name'] = $this->security->get_csrf_token_name();
            $data['csrf_hash'] = $this->security->get_csrf_hash();
        }

        $this->output
            ->set_status_header($statusCode)
            ->set_content_type('application/json')
            ->set_output(json_encode($data))
            ->_display();
        exit;
    }

    /**
     * Render view wrapped inside the main layout
     */
    protected function render($view, $data = [], $return = false) {
        $data['user'] = $this->current_user;
        $data['content_view'] = $view;
        $data['csrf_token_name'] = $this->security->get_csrf_token_name();
        $data['csrf_hash'] = $this->security->get_csrf_hash();

        return $this->load->view('layouts/main', $data, $return);
    }
}

/**
 * Public Controller
 * Accessible without login (e.g. Login, Register, Forgot Password).
 */
class Public_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();

        // If user already logged in, redirect away from public auth pages
        $userId = $this->session->userdata('user_id');
        $currentUri = $this->uri->uri_string();
        if ($userId && ($currentUri === 'login' || $currentUri === '' || $currentUri === 'auth/login')) {
            redirect('dashboard');
        }
    }

    /**
     * Standard JSON response helper for public endpoints
     */
    protected function json_response($data = [], $statusCode = 200) {
        if (is_array($data)) {
            $data['csrf_name'] = $this->security->get_csrf_token_name();
            $data['csrf_hash'] = $this->security->get_csrf_hash();
        }

        $this->output
            ->set_status_header($statusCode)
            ->set_content_type('application/json')
            ->set_output(json_encode($data))
            ->_display();
        exit;
    }
}
