<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pure CodeIgniter Excel Export Library
 * Native .xls generation without vendor/composer dependencies.
 */
class Export_excel {

    public function download($filename, $title, array $headers, array $rows) {
        if (substr($filename, -4) !== '.xls') {
            $filename = preg_replace('/\.[^.]+$/', '', $filename) . '.xls';
        }

        header("Content-Type: application/vnd.ms-excel; charset=utf-8");
        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Pragma: no-cache");
        header("Expires: 0");

        echo '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">';
        echo '<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />';
        echo '<style>
            table { border-collapse: collapse; width: 100%; font-family: Arial, sans-serif; font-size: 10pt; }
            th { background-color: #dc2626; color: #ffffff; font-weight: bold; border: 1px solid #991b1b; padding: 8px 10px; text-align: left; }
            td { border: 1px solid #cbd5e1; padding: 6px 10px; vertical-align: top; }
            .title { font-size: 14pt; font-weight: bold; color: #b91c1c; }
            .date { font-size: 9pt; color: #64748b; font-style: italic; }
            .text-center { text-align: center; }
            .text-right { text-align: right; }
        </style></head><body>';

        echo '<table>';
        echo '<tr><td colspan="' . count($headers) . '" class="title">' . html_escape($title) . '</td></tr>';
        echo '<tr><td colspan="' . count($headers) . '" class="date">Diekspor pada: ' . date('d/m/Y H:i') . ' WIB</td></tr>';
        echo '<tr><td colspan="' . count($headers) . '"></td></tr>';

        echo '<tr>';
        foreach ($headers as $h) {
            echo '<th>' . html_escape($h) . '</th>';
        }
        echo '</tr>';

        foreach ($rows as $row) {
            echo '<tr>';
            foreach ($row as $val) {
                echo '<td>' . html_escape($val) . '</td>';
            }
            echo '</tr>';
        }
        echo '</table></body></html>';
        exit;
    }
}
