<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok_model extends MY_Model {

    protected $table = 'stok_transaksi';

    /**
     * Get recent activities with relations
     */
    public function get_recent_activities($limit = 10)
    {
        $this->db->select('st.*, p.nama_produk, p.sku, u.name as user_name, u.avatar as user_avatar, b.nomor_batch, b.lokasi_rak');
        $this->db->from($this->table . ' st');
        $this->db->join('produk p', 'p.id = st.produk_id', 'left');
        $this->db->join('users u', 'u.id = st.user_id', 'left');
        $this->db->join('batch b', 'b.id = st.batch_id', 'left');
        $this->db->order_by('st.tanggal', 'DESC');
        $this->db->order_by('st.id', 'DESC');
        $this->db->limit($limit);

        return $this->db->get()->result_array();
    }

    /**
     * Get stock movement aggregated by day or month
     * @param string $days '7d'|'7' | '30d'|'30' | '6m' | '12m'
     */
    public function get_movement_data($days = '7d')
    {
        $isMonthly = in_array($days, ['6m', '12m', '6', '12']);

        if ($isMonthly) {
            $numMonths = ($days === '12m' || $days === '12') ? 12 : 6;
            return $this->get_monthly_movement($numMonths);
        } else {
            $numDays = ($days === '30d' || $days === '30') ? 30 : 7;
            return $this->get_daily_movement($numDays);
        }
    }

    /**
     * Daily movement aggregation
     */
    protected function get_daily_movement($numDays = 7)
    {
        $labels = [];
        $dateKeys = [];
        $masukMap = [];
        $keluarMap = [];

        for ($i = $numDays - 1; $i >= 0; $i--) {
            $d = date('Y-m-d', strtotime("-{$i} days"));
            $dateKeys[] = $d;
            $labels[] = date('d M', strtotime($d));
            $masukMap[$d] = 0;
            $keluarMap[$d] = 0;
        }

        $startDate = $dateKeys[0] . ' 00:00:00';
        $endDate = end($dateKeys) . ' 23:59:59';

        $this->db->select('DATE(tanggal) as tgl, tipe, SUM(jumlah) as total');
        $this->db->from($this->table);
        $this->db->where('tanggal >=', $startDate);
        $this->db->where('tanggal <=', $endDate);
        $this->db->group_by('DATE(tanggal), tipe');
        $results = $this->db->get()->result_array();

        foreach ($results as $row) {
            $tgl = $row['tgl'];
            if (isset($masukMap[$tgl]) && $row['tipe'] === 'masuk') {
                $masukMap[$tgl] = (int) $row['total'];
            }
            if (isset($keluarMap[$tgl]) && $row['tipe'] === 'keluar') {
                $keluarMap[$tgl] = (int) $row['total'];
            }
        }

        $masukData = array_values($masukMap);
        $keluarData = array_values($keluarMap);

        $todayStr = date('Y-m-d');
        $todayIn = $masukMap[$todayStr] ?? 0;
        $todayOut = $keluarMap[$todayStr] ?? 0;

        return [
            'days' => $labels,
            'masuk' => $masukData,
            'keluar' => $keluarData,
            'today_in' => $todayIn,
            'today_out' => $todayOut,
            'total_in' => array_sum($masukData),
            'total_out' => array_sum($keluarData),
            'period' => $numDays . 'd'
        ];
    }

    /**
     * Monthly movement aggregation (6m / 12m)
     */
    protected function get_monthly_movement($numMonths = 6)
    {
        $labels = [];
        $monthKeys = [];
        $masukMap = [];
        $keluarMap = [];

        for ($i = $numMonths - 1; $i >= 0; $i--) {
            $mKey = date('Y-m', strtotime("-{$i} months"));
            $monthKeys[] = $mKey;
            $labels[] = date('M Y', strtotime($mKey . '-01'));
            $masukMap[$mKey] = 0;
            $keluarMap[$mKey] = 0;
        }

        $startMonth = $monthKeys[0] . '-01 00:00:00';

        $this->db->select("DATE_FORMAT(tanggal, '%Y-%m') as ym, tipe, SUM(jumlah) as total");
        $this->db->from($this->table);
        $this->db->where('tanggal >=', $startMonth);
        $this->db->group_by("DATE_FORMAT(tanggal, '%Y-%m'), tipe");
        $results = $this->db->get()->result_array();

        foreach ($results as $row) {
            $ym = $row['ym'];
            if (isset($masukMap[$ym]) && $row['tipe'] === 'masuk') {
                $masukMap[$ym] = (int) $row['total'];
            }
            if (isset($keluarMap[$ym]) && $row['tipe'] === 'keluar') {
                $keluarMap[$ym] = (int) $row['total'];
            }
        }

        $masukData = array_values($masukMap);
        $keluarData = array_values($keluarMap);

        $thisMonthStr = date('Y-m');
        $todayIn = $masukMap[$thisMonthStr] ?? 0;
        $todayOut = $keluarMap[$thisMonthStr] ?? 0;

        return [
            'days' => $labels,
            'masuk' => $masukData,
            'keluar' => $keluarData,
            'today_in' => $todayIn,
            'today_out' => $todayOut,
            'total_in' => array_sum($masukData),
            'total_out' => array_sum($keluarData),
            'period' => $numMonths . 'm'
        ];
    }

    /**
     * Get transaction history with joined metadata
     */
    public function get_history($filters = [], $limit = null, $offset = null) {
        $this->db->select('stok_transaksi.*, produk.nama_produk, produk.sku, batch.nomor_batch, users.name as user_name');
        $this->db->from($this->table);
        $this->db->join('produk', 'produk.id = stok_transaksi.produk_id', 'left');
        $this->db->join('batch', 'batch.id = stok_transaksi.batch_id', 'left');
        $this->db->join('users', 'users.id = stok_transaksi.user_id', 'left');

        if (!empty($filters['produk_id'])) {
            $this->db->where('stok_transaksi.produk_id', $filters['produk_id']);
        }
        if (!empty($filters['batch_id'])) {
            $this->db->where('stok_transaksi.batch_id', $filters['batch_id']);
        }
        if (!empty($filters['tipe'])) {
            $this->db->where('stok_transaksi.tipe', $filters['tipe']);
        }
        if (!empty($filters['start_date'])) {
            $this->db->where('DATE(stok_transaksi.tanggal) >=', $filters['start_date']);
        }
        if (!empty($filters['end_date'])) {
            $this->db->where('DATE(stok_transaksi.tanggal) <=', $filters['end_date']);
        }

        $this->db->order_by('stok_transaksi.tanggal', 'DESC');
        $this->db->order_by('stok_transaksi.id', 'DESC');
        if ($limit !== null) {
            $this->db->limit($limit, $offset);
        }

        return $this->db->get()->result_array();
    }

    /**
     * Get single transaction details for receipt/delivery slip
     */
    public function get_transaction_detail($id) {
        $this->db->select('stok_transaksi.*, produk.nama_produk, produk.sku, batch.nomor_batch, batch.lokasi_rak, users.name as user_name');
        $this->db->from($this->table);
        $this->db->join('produk', 'produk.id = stok_transaksi.produk_id', 'left');
        $this->db->join('batch', 'batch.id = stok_transaksi.batch_id', 'left');
        $this->db->join('users', 'users.id = stok_transaksi.user_id', 'left');
        $this->db->where('stok_transaksi.id', $id);
        return $this->db->get()->row_array();
    }

    /**
     * Record stock in (masuk) atomically
     */
    public function record_masuk($data) {
        $this->db->trans_start();

        $txData = [
            'produk_id' => $data['produk_id'],
            'batch_id' => !empty($data['batch_id']) ? $data['batch_id'] : null,
            'user_id' => $data['user_id'],
            'tipe' => 'masuk',
            'jumlah' => (int)$data['jumlah'],
            'catatan' => $data['catatan'] ?? null,
            'scan_mode' => $data['scan_mode'] ?? 'manual',
            'tanggal' => $data['tanggal'] ?? date('Y-m-d H:i:s')
        ];
        $this->db->insert($this->table, $txData);
        $txId = $this->db->insert_id();

        $this->db->where('id', $data['produk_id']);
        $this->db->set('stok', 'stok + ' . (int)$data['jumlah'], false);
        $this->db->update('produk');

        if (!empty($data['batch_id'])) {
            $this->db->where('id', $data['batch_id']);
            $this->db->set('stok_saat_ini', 'stok_saat_ini + ' . (int)$data['jumlah'], false);
            $this->db->update('batch');
        }

        $this->load->model('Produk_model');
        $this->Produk_model->sync_sisa_stok($data['produk_id']);

        $this->db->trans_complete();
        return $this->db->trans_status() ? $txId : false;
    }

    /**
     * Record stock out (keluar) with anti-minus protection
     */
    public function record_keluar($data) {
        $this->db->trans_start();

        $produk = $this->db->get_where('produk', ['id' => $data['produk_id']])->row_array();
        if (!$produk || $produk['stok'] < (int)$data['jumlah']) {
            $this->db->trans_rollback();
            return ['status' => false, 'message' => 'Stok produk tidak mencukupi.'];
        }

        if (!empty($data['batch_id'])) {
            $batch = $this->db->get_where('batch', ['id' => $data['batch_id']])->row_array();
            if (!$batch || $batch['stok_saat_ini'] < (int)$data['jumlah']) {
                $this->db->trans_rollback();
                return ['status' => false, 'message' => 'Stok pada batch tidak mencukupi.'];
            }

            $this->db->where('id', $data['batch_id']);
            $this->db->set('stok_saat_ini', 'stok_saat_ini - ' . (int)$data['jumlah'], false);
            $this->db->update('batch');
        }

        $txData = [
            'produk_id' => $data['produk_id'],
            'batch_id' => !empty($data['batch_id']) ? $data['batch_id'] : null,
            'user_id' => $data['user_id'],
            'tipe' => 'keluar',
            'jumlah' => (int)$data['jumlah'],
            'catatan' => $data['catatan'] ?? null,
            'scan_mode' => $data['scan_mode'] ?? 'manual',
            'tanggal' => $data['tanggal'] ?? date('Y-m-d H:i:s')
        ];
        $this->db->insert($this->table, $txData);
        $txId = $this->db->insert_id();

        $this->db->where('id', $data['produk_id']);
        $this->db->set('stok', 'stok - ' . (int)$data['jumlah'], false);
        $this->db->update('produk');

        $this->load->model('Produk_model');
        $this->Produk_model->sync_sisa_stok($data['produk_id']);

        $this->db->trans_complete();

        if ($this->db->trans_status()) {
            return ['status' => true, 'id' => $txId];
        }

        return ['status' => false, 'message' => 'Terjadi kesalahan sistem saat memproses mutasi keluar.'];
    }
}
