<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Preference Library
 * Manages system settings, user preferences, and company letterhead using the `pengaturan` table.
 */
class Preference {

    protected $CI;
    protected $cache = [];

    public function __construct() {
        $this->CI =& get_instance();
        $this->CI->load->database();
    }

    /**
     * Get a setting by key
     *
     * @param string $key
     * @param mixed $default
     * @param int|null $userId
     * @return mixed
     */
    public function get($key, $default = null, $userId = null) {
        $cacheKey = ($userId ? 'u_' . $userId : 'sys') . '_' . $key;
        if (isset($this->cache[$cacheKey])) {
            return $this->cache[$cacheKey];
        }

        // Query database table `pengaturan`
        if (!$this->CI->db->table_exists('pengaturan')) {
            return $default;
        }

        $this->CI->db->where('kunci', $key);
        if ($userId !== null) {
            $this->CI->db->where('user_id', $userId);
        } else {
            $this->CI->db->where('user_id IS NULL', null, false);
        }

        $row = $this->CI->db->get('pengaturan')->row_array();
        if ($row) {
            $val = json_decode($row['nilai'], true);
            $parsed = (json_last_error() === JSON_ERROR_NONE) ? $val : $row['nilai'];
            $this->cache[$cacheKey] = $parsed;
            return $parsed;
        }

        return $default;
    }

    /**
     * Set/save a setting by key
     *
     * @param string $key
     * @param mixed $value
     * @param int|null $userId
     * @return bool
     */
    public function set($key, $value, $userId = null) {
        $cacheKey = ($userId ? 'u_' . $userId : 'sys') . '_' . $key;
        $this->cache[$cacheKey] = $value;

        if (!$this->CI->db->table_exists('pengaturan')) {
            return false;
        }

        $encoded = is_array($value) || is_object($value) ? json_encode($value) : $value;

        $this->CI->db->where('kunci', $key);
        if ($userId !== null) {
            $this->CI->db->where('user_id', $userId);
        } else {
            $this->CI->db->where('user_id IS NULL', null, false);
        }

        $exists = $this->CI->db->get('pengaturan')->row_array();

        if ($exists) {
            $this->CI->db->where('id', $exists['id']);
            return $this->CI->db->update('pengaturan', [
                'nilai' => $encoded,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        } else {
            return $this->CI->db->insert('pengaturan', [
                'user_id' => $userId,
                'kunci' => $key,
                'nilai' => $encoded,
                'updated_at' => date('Y-m-d H:i:s')
            ]);
        }
    }

    /**
     * Get Company Profile for letterheads and documents
     */
    public function get_company() {
        $default = [
            'nama_perusahaan' => 'PT. Gudang Solusi Indonesia',
            'tagline' => 'Sistem Pergudangan & Manajemen Inventaris Terpadu',
            'alamat' => 'Jl. Kawasan Industri No. 45, Jakarta Pusat',
            'telepon' => '021-5550192',
            'email' => 'admin@gudangsolusi.id',
            'pic_name' => 'Dimas Rizqi',
            'pic_jabatan' => 'Kepala Gudang & Operasional',
            'footer_note' => 'Dokumen ini sah dan diterbitkan secara elektronik oleh Sistem Manajemen Barang.'
        ];

        return $this->get('company_profile', $default);
    }

    /**
     * Save Company Profile
     */
    public function save_company(array $data) {
        return $this->set('company_profile', $data);
    }

    /**
     * Get all preferences for a user
     */
    public function get_user_preferences($userId) {
        $default = [
            'confirm_mode' => 'safe', // safe | fast
            'date_format' => 'DD/MM/YYYY', // DD/MM/YYYY | YYYY-MM-DD | DD MMMM YYYY
            'daily_digest' => false,
            'sound_effects' => true,
            'theme' => 'light',
            'language' => 'id'
        ];

        $userPrefs = $this->get('user_preferences', $default, $userId);
        return array_merge($default, is_array($userPrefs) ? $userPrefs : []);
    }

    /**
     * Save user preferences
     */
    public function save_user_preferences($userId, array $prefs) {
        $current = $this->get_user_preferences($userId);
        $merged = array_merge($current, $prefs);
        return $this->set('user_preferences', $merged, $userId);
    }
}
