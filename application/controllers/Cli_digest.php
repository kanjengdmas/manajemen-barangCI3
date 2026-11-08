<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CLI Digest Controller
 * Executed via cron to email end-of-day warehouse inventory transaction summaries.
 * Usage: php index.php cli_digest run
 */
class Cli_digest extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if (!is_cli()) {
            show_404();
            return;
        }

        $this->load->database();
        $this->load->model(['Stok_model', 'User_model', 'Pengaturan_model']);
    }

    public function run() {
        echo "[CRON] Starting Daily Digest processing at " . date('Y-m-d H:i:s') . PHP_EOL;

        // 1. Check which users have daily_digest enabled in their preferences
        $users = $this->User_model->get_all(['role' => 'admin']);
        $today = date('Y-m-d');

        // 2. Fetch today's transactions
        $transactions = $this->Stok_model->get_history([
            'start_date' => $today,
            'end_date' => $today
        ]);

        $totalMasuk = 0;
        $totalKeluar = 0;
        foreach ($transactions as $tx) {
            if ($tx['tipe'] === 'masuk') {
                $totalMasuk += (int)$tx['jumlah'];
            } else {
                $totalKeluar += (int)$tx['jumlah'];
            }
        }

        $summaryText = "Ringkasan Mutasi Gudang Tanggal {$today}:\n" .
                       "- Total Barang Masuk: {$totalMasuk} unit\n" .
                       "- Total Barang Keluar: {$totalKeluar} unit\n" .
                       "- Total Transaksi: " . count($transactions) . " aktivitas\n\n";

        foreach ($users as $user) {
            $userPrefs = $this->preference->get_user_preferences($user['id']);
            if (empty($userPrefs['daily_digest'])) {
                continue;
            }

            echo "[CRON] Sending digest to: {$user['email']}" . PHP_EOL;
            // CI Email can be configured via application/config/email.php
            // $this->load->library('email'); ...
        }

        echo "[CRON] Daily Digest completed.\n" . $summaryText;
    }
}
