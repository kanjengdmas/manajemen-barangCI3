<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if (!function_exists('format_date_by_preference')) {
    /**
     * Format a date/datetime string based on user/system preference
     *
     * @param string|int|null $datetime
     * @param string|null $formatStyle 'DD/MM/YYYY' | 'YYYY-MM-DD' | 'DD MMMM YYYY'
     * @param bool $includeTime Whether to include H:i:s or H:i
     * @return string
     */
    function format_date_by_preference($datetime, $formatStyle = null, $includeTime = false) {
        if (empty($datetime)) {
            return '-';
        }

        $timestamp = is_numeric($datetime) ? (int)$datetime : strtotime($datetime);
        if (!$timestamp) {
            return $datetime;
        }

        // If formatStyle is not passed, resolve from preference library
        if (empty($formatStyle)) {
            $CI =& get_instance();
            if (isset($CI->preference)) {
                $formatStyle = $CI->preference->get('date_format', 'DD/MM/YYYY');
            } else {
                $formatStyle = 'DD/MM/YYYY';
            }
        }

        $monthsId = [
            1 => 'Januari', 2 => 'Februari', 3 => 'Maret', 4 => 'April',
            5 => 'Mei', 6 => 'Juni', 7 => 'Juli', 8 => 'Agustus',
            9 => 'September', 10 => 'Oktober', 11 => 'November', 12 => 'Desember'
        ];

        switch ($formatStyle) {
            case 'YYYY-MM-DD':
                $result = date('Y-m-d', $timestamp);
                break;
            case 'DD MMMM YYYY':
                $m = (int)date('n', $timestamp);
                $monthName = $monthsId[$m] ?? date('F', $timestamp);
                $result = date('d', $timestamp) . ' ' . $monthName . ' ' . date('Y', $timestamp);
                break;
            case 'DD/MM/YYYY':
            default:
                $result = date('d/m/Y', $timestamp);
                break;
        }

        if ($includeTime) {
            $result .= ' ' . date('H:i', $timestamp);
        }

        return $result;
    }
}

if (!function_exists('get_preference')) {
    /**
     * Helper to retrieve a preference value
     */
    function get_preference($key, $default = null, $userId = null) {
        $CI =& get_instance();
        if (isset($CI->preference)) {
            return $CI->preference->get($key, $default, $userId);
        }
        return $default;
    }
}

if (!function_exists('get_company_profile')) {
    /**
     * Retrieve company identity and letterhead profile
     */
    function get_company_profile() {
        $CI =& get_instance();
        if (isset($CI->preference)) {
            return $CI->preference->get_company();
        }
        return [
            'nama_perusahaan' => 'PT. Gudang Solusi Indonesia',
            'tagline' => 'Sistem Pergudangan & Manajemen Inventaris Terpadu',
            'alamat' => 'Jl. Kawasan Industri No. 45, Jakarta Pusat',
            'telepon' => '021-5550192',
            'email' => 'admin@gudangsolusi.id',
            'pic_name' => 'Dimas Rizqi',
            'pic_jabatan' => 'Kepala Gudang & Operasional',
            'footer_note' => 'Dokumen ini sah dan diterbitkan secara elektronik oleh Sistem Manajemen Barang.'
        ];
    }
}
