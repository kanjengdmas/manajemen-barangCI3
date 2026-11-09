<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Profile extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_model');
    }

    /**
     * GET /api/user
     */
    public function me() {
        $avatarUrl = !empty($this->current_user['avatar']) ? base_url('uploads/avatar/' . $this->current_user['avatar']) : null;
        $this->json_response([
            'status' => true,
            'data' => [
                'id' => $this->current_user['id'],
                'name' => $this->current_user['name'],
                'email' => $this->current_user['email'],
                'role' => $this->current_user['role'],
                'avatar' => $this->current_user['avatar'],
                'avatar_url' => $avatarUrl
            ]
        ]);
    }

    /**
     * GET /api/profile
     * Dispatches to update() if POST method is used
     */
    public function index() {
        if ($this->input->method() === 'post') {
            return $this->update();
        }

        $user = $this->current_user;
        unset($user['password']);
        $user['joined_date_formatted'] = format_date_by_preference($user['created_at']);
        $user['avatar_url'] = !empty($user['avatar']) ? base_url('uploads/avatar/' . $user['avatar']) : null;

        $this->json_response([
            'status' => true,
            'data' => $user
        ]);
    }

    /**
     * POST /api/profile
     * POST /api/profile/update
     */
    public function update() {
        $userId = $this->current_user['id'];
        $name = trim($this->input->post('name', TRUE));
        $email = trim($this->input->post('email', TRUE));

        if (empty($name) || empty($email)) {
            $this->json_response(['status' => false, 'message' => 'Nama dan email wajib diisi.'], 422);
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->json_response(['status' => false, 'message' => 'Format alamat email tidak valid.'], 422);
        }

        // Check unique email
        $existing = $this->db->get_where('users', ['email' => $email, 'id !=' => $userId])->row_array();
        if ($existing) {
            $this->json_response(['status' => false, 'message' => 'Email telah digunakan oleh pengguna lain.'], 422);
        }

        $updateData = [
            'name' => $name,
            'email' => $email,
            'updated_at' => date('Y-m-d H:i:s')
        ];

        // Handle Avatar Removal
        if ($this->input->post('delete_avatar') == '1') {
            if (!empty($this->current_user['avatar']) && file_exists(FCPATH . 'uploads/avatar/' . $this->current_user['avatar'])) {
                @unlink(FCPATH . 'uploads/avatar/' . $this->current_user['avatar']);
            }
            $updateData['avatar'] = null;
        }

        // Handle Avatar Upload
        if (!empty($_FILES['avatar']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/avatar/';
            $config['allowed_types'] = 'jpg|jpeg|png|webp';
            $config['max_size'] = 2048; // 2MB
            $config['file_name'] = 'avatar_' . $userId . '_' . time();

            $this->load->library('upload', $config);
            if ($this->upload->do_upload('avatar')) {
                // Remove previous avatar file if exists
                if (!empty($this->current_user['avatar']) && file_exists(FCPATH . 'uploads/avatar/' . $this->current_user['avatar'])) {
                    @unlink(FCPATH . 'uploads/avatar/' . $this->current_user['avatar']);
                }
                $fileData = $this->upload->data();
                $updateData['avatar'] = $fileData['file_name'];
            } else {
                $uploadError = strip_tags($this->upload->display_errors());
                $this->json_response(['status' => false, 'message' => 'Gagal mengunggah foto avatar: ' . $uploadError], 422);
            }
        }

        $this->User_model->update($userId, $updateData);

        // Update active session values
        $this->session->set_userdata([
            'user_name' => $name,
            'user_email' => $email
        ]);

        $updatedUser = $this->User_model->get_by_id($userId);
        unset($updatedUser['password']);
        $updatedUser['avatar_url'] = !empty($updatedUser['avatar']) ? base_url('uploads/avatar/' . $updatedUser['avatar']) : null;

        $this->json_response([
            'status' => true,
            'message' => 'Profil pengguna berhasil diperbarui.',
            'data' => $updatedUser
        ]);
    }

    /**
     * POST /api/profile/password
     */
    public function password() {
        $userId = $this->current_user['id'];
        $currentPass = $this->input->post('current_password');
        $newPass = $this->input->post('new_password');
        $confirmPass = $this->input->post('new_password_confirmation') ?? $this->input->post('confirm_password');

        if (empty($currentPass) || empty($newPass)) {
            $this->json_response(['status' => false, 'message' => 'Semua kolom kata sandi wajib diisi.'], 422);
        }

        if (!password_verify($currentPass, $this->current_user['password'])) {
            $this->json_response(['status' => false, 'message' => 'Kata sandi saat ini tidak sesuai.'], 422);
        }

        if (strlen($newPass) < 8) {
            $this->json_response(['status' => false, 'message' => 'Kata sandi baru minimal harus 8 karakter.'], 422);
        }

        if ($newPass !== $confirmPass) {
            $this->json_response(['status' => false, 'message' => 'Konfirmasi kata sandi baru tidak cocok.'], 422);
        }

        $this->User_model->update_password($userId, password_hash($newPass, PASSWORD_BCRYPT));

        $this->json_response([
            'status' => true,
            'message' => 'Kata sandi akun Anda berhasil diperbarui.'
        ]);
    }

    /**
     * GET /api/profile/preferences
     * POST /api/profile/preferences
     */
    public function preferences() {
        if ($this->input->method() === 'post') {
            return $this->save_preferences();
        }

        $prefs = $this->preference->get_user_preferences($this->current_user['id']);
        $this->json_response([
            'status' => true,
            'data' => $prefs
        ]);
    }

    /**
     * POST /api/profile/save-preferences
     */
    public function save_preferences() {
        $json = json_decode($this->input->raw_input_stream, true);
        $prefs = is_array($json) ? $json : $this->input->post();

        if (empty($prefs)) {
            $this->json_response(['status' => false, 'message' => 'Data preferensi tidak valid.'], 400);
        }

        $this->preference->save_user_preferences($this->current_user['id'], $prefs);

        $this->json_response([
            'status' => true,
            'message' => 'Preferensi pengguna berhasil disimpan.',
            'data' => $this->preference->get_user_preferences($this->current_user['id'])
        ]);
    }
}
