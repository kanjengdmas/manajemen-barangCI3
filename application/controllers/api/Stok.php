<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Stok extends MY_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Stok_model', 'Produk_model', 'Batch_model']);
        $this->load->library(['export_excel', 'export_pdf']);
    }

    /**
     * GET /api/stok/history
     */
    public function history() {
        $filters = [
            'produk_id' => $this->input->get('produk_id'),
            'batch_id' => $this->input->get('batch_id'),
            'tipe' => $this->input->get('tipe'),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date')
        ];

        $limit = $this->input->get('limit') ? (int)$this->input->get('limit') : null;
        $offset = $this->input->get('offset') ? (int)$this->input->get('offset') : null;

        $history = $this->Stok_model->get_history($filters, $limit, $offset);

        // Format dates
        foreach ($history as &$row) {
            $row['tanggal_formatted'] = format_date_by_preference($row['tanggal'], null, true);
        }

        $this->json_response([
            'status' => true,
            'data' => $history
        ]);
    }

    /**
     * GET /api/stok/summary
     */
    public function summary() {
        $masuk = $this->db->select_sum('jumlah')->where('tipe', 'masuk')->get('stok_transaksi')->row_array()['jumlah'] ?? 0;
        $keluar = $this->db->select_sum('jumlah')->where('tipe', 'keluar')->get('stok_transaksi')->row_array()['jumlah'] ?? 0;

        $this->json_response([
            'status' => true,
            'data' => [
                'total_masuk' => (int)$masuk,
                'total_keluar' => (int)$keluar,
                'net_flow' => (int)$masuk - (int)$keluar
            ]
        ]);
    }

    /**
     * GET /api/stok/export/excel
     */
    public function export_excel() {
        $filters = [
            'produk_id' => $this->input->get('produk_id'),
            'tipe' => $this->input->get('tipe'),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date')
        ];

        $history = $this->Stok_model->get_history($filters);

        $headers = ['No', 'Tanggal & Waktu', 'Nama Produk', 'SKU', 'Nomor Lot/Batch', 'Tipe Mutasi', 'Jumlah', 'Petugas/Operator', 'Catatan'];
        $rows = [];
        $no = 1;
        foreach ($history as $h) {
            $rows[] = [
                $no++,
                format_date_by_preference($h['tanggal'], null, true),
                $h['nama_produk'],
                $h['sku'],
                $h['nomor_batch'] ?? '-',
                strtoupper($h['tipe']),
                $h['jumlah'],
                $h['user_name'] ?? '-',
                $h['catatan'] ?? '-'
            ];
        }

        $filename = 'Laporan-Mutasi-Stok-' . date('Y-m-d') . '.xlsx';
        $this->export_excel->download($filename, 'Laporan Mutasi Stok Gudang', $headers, $rows);
    }

    /**
     * GET /api/stok/export/pdf
     */
    public function export_pdf() {
        $filters = [
            'produk_id' => $this->input->get('produk_id'),
            'tipe' => $this->input->get('tipe'),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date')
        ];

        $data['transactions'] = $this->Stok_model->get_history($filters);
        $data['company'] = get_company_profile();
        $data['filters'] = $filters;
        $data['generated_at'] = format_date_by_preference(date('Y-m-d H:i:s'), null, true);

        $html = $this->load->view('laporan/pdf_template', $data, true);
        $filename = 'Laporan-Mutasi-Stok-' . date('Y-m-d') . '.pdf';

        $this->export_pdf->download($filename, $html, 'a4', 'landscape');
    }
}
