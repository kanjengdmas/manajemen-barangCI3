<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Pure CodeIgniter Document Print & PDF Library
 * Renders print-optimized documents with letterheads and signatures without composer dependencies.
 */
class Export_pdf {

    protected $CI;

    public function __construct() {
        $this->CI =& get_instance();
    }

    /**
     * Render document view ready for printing / saving as PDF
     *
     * @param string $filename
     * @param string $htmlContent
     * @param string $paperSize
     * @param string $orientation
     */
    public function download($filename, $htmlContent, $paperSize = 'a4', $orientation = 'portrait') {
        // Output clean HTML document with auto-print script
        $autoPrintScript = '<script>window.addEventListener("load", function() { window.print(); });</script>';

        if (stripos($htmlContent, '</body>') !== false) {
            echo str_ireplace('</body>', $autoPrintScript . '</body>', $htmlContent);
        } else {
            echo $htmlContent . $autoPrintScript;
        }
        exit;
    }

    public function inline($filename, $htmlContent, $paperSize = 'a4', $orientation = 'portrait') {
        $this->download($filename, $htmlContent, $paperSize, $orientation);
    }
}
