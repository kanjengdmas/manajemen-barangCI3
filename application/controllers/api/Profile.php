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
        $this->json_response([
            'status' => true,
            'data' => [
                'id' => $this->current_user['id'],
                'name' => $this->current_user['name'],
                'email' => $this->current_user['email'],
                'role' => $this->current_user['role'],
                'avatar' => $this->current_user['avatar']
            ]
        ]);
    }

    /**
     * GET /api/profile
     */
    public function index() {
        $user = $this->current_user;
        unset($user['password']);
        $user['joined_date_formatted'] = format_date_by_preference($user['created_at']);

        $this->json_response([
            'status' => true,
            'data' => $user
        ]);
    }

    /**
     * POST /api/profile
     */
    public function update() {
        $userId = $this->current_user['id'];
        $name = trim($this->input->post('name', TRUE));
        $email = trim($this->input->post('email', TRUE));

        if (empty($name) || empty($email)) {
            $this->json_response(['status' => false, 'message' => 'Nama dan email wajib diisi.'], 422);
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

        // Handle Avatar Upload
        if (!empty($_FILES['avatar']['name'])) {
            $config['upload_path'] = FCPATH . 'uploads/avatar/';
            $config['allowed_types'] = 'jpg|jpeg|png|webp';
            $config['max_size'] = 2048; // 2MB
            $config['file_name'] = 'avatar_' . $userId . '_' . time();

            $this->load->library('upload', $config);
            if ($this->upload->do_upload('avatar')) {
                $fileData = $this->upload->data();
                $updateData['avatar'] = $fileData['file_name'];
            }
        }

        $this->User_model->update($userId, $updateData);

        $this->json_response([
            'status' => true,
            'message' => 'Profil berhasil diperbarui.',
            'data' => array_merge($this->current_user, $updateData)
        ]);
    }

    /**
     * POST /api/profile/password
     */
    public function password() {
        $userId = $this->current_user['id'];
        $currentPass = $this->input->post('current_password');
        $newPass = $this->input->post('new_password');
        $confirmPass = $this->input->post('new_password_confirmation');

        if (!password_verify($currentPass, $this->current_user['password'])) {
            $this->json_response(['status' => false, 'message' => 'Kata sandi saat ini tidak sesuai.'], 422);
        }

        if (strlen($newPass) < 8) {
            $this->json_response(['status' => false, 'message' => 'Kata sandi baru minimal 8 karakter.'], 422);
        }

        if ($newPass !== $confirmPass) {
            $this->json_response(['status' => false, 'message' => 'Konfirmasi kata sandi baru tidak cocok.'], 422);
        }

        $this->User_model->update_password($userId, password_hash($newPass, PASSWORD_BCRYPT));

        $this->json_response([
            'status' => true,
            'message' => 'Kata sandi berhasil diperbarui.'
        ]);
    }

    /**
     * GET /api/profile/preferences
     */
    public function preferences() {
        $prefs = $this->preference->get_user_preferences($this->current_user['id']);
        $this->json_response([
            'status' => true,
            'data' => $prefs
        ]);
    }

    /**
     * POST /api/profile/preferences
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
            'message' => 'Preferensi berhasil disimpan secara otomatis.',
            'data' => $this->preference->get_user_preferences($this->current_user['id'])
        ]);
    }
}
