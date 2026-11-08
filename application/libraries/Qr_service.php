<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pure CodeIgniter QR Code Service
 * Generates PNG files using PHP GD with zero vendor / composer dependencies.
 */
class Qr_service {

    protected $uploadPath;

    public function __construct() {
        $this->uploadPath = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'qr' . DIRECTORY_SEPARATOR;
        if (!is_dir($this->uploadPath)) {
            @mkdir($this->uploadPath, 0775, true);
        }
    }

    /**
     * Generate QR code and save to uploads/qr/{filename}
     *
     * @param string $data
     * @param string|null $filename
     * @param int $pixelSize
     * @return string Relative path
     */
    public function generate($data, $filename = null, $pixelSize = 6) {
        if (empty($filename)) {
            $filename = 'qr_' . md5($data . '_' . time()) . '.png';
        }

        if (substr($filename, -4) !== '.png') {
            $filename .= '.png';
        }

        $fullPath = $this->uploadPath . $filename;
        $this->render_png($data, $fullPath, $pixelSize);

        return 'uploads/qr/' . $filename;
    }

    /**
     * Stream QR code directly to browser
     */
    public function stream($data, $pixelSize = 6) {
        $matrix = $this->text_to_matrix($data);
        $count = count($matrix);
        $margin = 2;
        $imgSize = ($count + ($margin * 2)) * $pixelSize;

        $im = imagecreatetruecolor($imgSize, $imgSize);
        $bg = imagecolorallocate($im, 255, 255, 255);
        $fg = imagecolorallocate($im, 17, 24, 39);

        imagefilledrectangle($im, 0, 0, $imgSize, $imgSize, $bg);

        for ($r = 0; $r < $count; $r++) {
            for ($c = 0; $c < $count; $c++) {
                if ($matrix[$r][$c]) {
                    $x1 = ($c + $margin) * $pixelSize;
                    $y1 = ($r + $margin) * $pixelSize;
                    $x2 = $x1 + $pixelSize - 1;
                    $y2 = $y1 + $pixelSize - 1;
                    imagefilledrectangle($im, $x1, $y1, $x2, $y2, $fg);
                }
            }
        }

        header('Content-Type: image/png');
        imagepng($im);
        imagedestroy($im);
        exit;
    }

    protected function render_png($text, $outfile, $pixelSize = 6) {
        $matrix = $this->text_to_matrix($text);
        $count = count($matrix);
        $margin = 2;
        $imgSize = ($count + ($margin * 2)) * $pixelSize;

        if (function_exists('imagecreatetruecolor')) {
            $im = imagecreatetruecolor($imgSize, $imgSize);
            $bg = imagecolorallocate($im, 255, 255, 255);
            $fg = imagecolorallocate($im, 17, 24, 39);

            imagefilledrectangle($im, 0, 0, $imgSize, $imgSize, $bg);

            for ($r = 0; $r < $count; $r++) {
                for ($c = 0; $c < $count; $c++) {
                    if ($matrix[$r][$c]) {
                        $x1 = ($c + $margin) * $pixelSize;
                        $y1 = ($r + $margin) * $pixelSize;
                        $x2 = $x1 + $pixelSize - 1;
                        $y2 = $y1 + $pixelSize - 1;
                        imagefilledrectangle($im, $x1, $y1, $x2, $y2, $fg);
                    }
                }
            }

            imagepng($im, $outfile);
            imagedestroy($im);
            return true;
        }

        return false;
    }

    protected function text_to_matrix($text) {
        $version = 3;
        if (strlen($text) > 32) $version = 4;
        if (strlen($text) > 48) $version = 5;
        if (strlen($text) > 70) $version = 6;

        $size = 17 + (4 * $version);
        $matrix = array_fill(0, $size, array_fill(0, $size, 0));
        $reserved = array_fill(0, $size, array_fill(0, $size, false));

        // 1. Finder patterns
        $this->add_finder_pattern($matrix, $reserved, 0, 0);
        $this->add_finder_pattern($matrix, $reserved, $size - 7, 0);
        $this->add_finder_pattern($matrix, $reserved, 0, $size - 7);

        // 2. Alignment pattern
        if ($version >= 2) {
            $align_pos = $size - 7;
            $this->add_alignment_pattern($matrix, $reserved, $align_pos - 2, $align_pos - 2);
        }

        // 3. Timing patterns
        for ($i = 8; $i < $size - 8; $i++) {
            $val = ($i % 2 === 0) ? 1 : 0;
            if (!$reserved[6][$i]) {
                $matrix[6][$i] = $val;
                $reserved[6][$i] = true;
            }
            if (!$reserved[$i][6]) {
                $matrix[$i][6] = $val;
                $reserved[$i][6] = true;
            }
        }

        // 4. Encode data bits
        $bits = $this->encode_data_to_bits($text, $version);
        $bit_idx = 0;
        $bit_len = strlen($bits);

        $dir = -1;
        $row = $size - 1;
        $col = $size - 1;

        while ($col > 0) {
            if ($col === 6) $col--;

            for ($i = 0; $i < $size; $i++) {
                $curr_row = $row;
                for ($c = 0; $c < 2; $c++) {
                    $curr_col = $col - $c;
                    if (!$reserved[$curr_row][$curr_col]) {
                        $bit = ($bit_idx < $bit_len) ? (int)$bits[$bit_idx++] : 0;
                        $mask = (($curr_row + $curr_col) % 2 === 0);
                        $matrix[$curr_row][$curr_col] = $mask ? ($bit ^ 1) : $bit;
                    }
                }
                $row += $dir;
            }
            $dir = -$dir;
            $row += $dir;
            $col -= 2;
        }

        return $matrix;
    }

    protected function add_finder_pattern(&$matrix, &$reserved, $r, $c) {
        for ($i = -1; $i <= 7; $i++) {
            for ($j = -1; $j <= 7; $j++) {
                $row = $r + $i;
                $col = $c + $j;
                if ($row >= 0 && $row < count($matrix) && $col >= 0 && $col < count($matrix)) {
                    $reserved[$row][$col] = true;
                    if ($i >= 0 && $i <= 6 && $j >= 0 && $j <= 6) {
                        if ($i === 0 || $i === 6 || $j === 0 || $j === 6 || ($i >= 2 && $i <= 4 && $j >= 2 && $j <= 4)) {
                            $matrix[$row][$col] = 1;
                        } else {
                            $matrix[$row][$col] = 0;
                        }
                    } else {
                        $matrix[$row][$col] = 0;
                    }
                }
            }
        }
    }

    protected function add_alignment_pattern(&$matrix, &$reserved, $r, $c) {
        for ($i = -2; $i <= 2; $i++) {
            for ($j = -2; $j <= 2; $j++) {
                $row = $r + $i;
                $col = $c + $j;
                if ($row >= 0 && $row < count($matrix) && $col >= 0 && $col < count($matrix)) {
                    $reserved[$row][$col] = true;
                    if (abs($i) === 2 || abs($j) === 2 || ($i === 0 && $j === 0)) {
                        $matrix[$row][$col] = 1;
                    } else {
                        $matrix[$row][$col] = 0;
                    }
                }
            }
        }
    }

    protected function encode_data_to_bits($text, $version) {
        $bits = "0100";
        $bits .= str_pad(decbin(strlen($text)), 8, '0', STR_PAD_LEFT);

        for ($i = 0; $i < strlen($text); $i++) {
            $bits .= str_pad(decbin(ord($text[$i])), 8, '0', STR_PAD_LEFT);
        }

        $bits .= "0000";
        while (strlen($bits) % 8 !== 0) {
            $bits .= "0";
        }

        $pad = array("11101100", "00010001");
        $p = 0;
        $max_capacity_bytes = (17 + 4 * $version) * 6;
        while (strlen($bits) < $max_capacity_bytes * 8) {
            $bits .= $pad[$p % 2];
            $p++;
        }

        return $bits;
    }
}
