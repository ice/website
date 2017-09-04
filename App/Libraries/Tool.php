<?php

namespace App\Libraries;

/**
 * Tool Library
 *
 * @package     Ice/Website
 * @category    Library
 */
class Tool
{
    
    /**
     * Get the human file size
     */
    public static function fileSize($file, $setup = null)
    {
        $FZ = ($file && @is_file($file)) ? filesize($file) : null;
        $FS = ["B","kB","MB","GB","TB","PB","EB","ZB","YB"];

        if (!$setup && $setup !== 0) {
            return number_format($FZ/pow(1024, $I = floor(log($FZ, 1024))), ($I >= 1) ? 2 : 0) . ' ' . $FS[$I];
        } elseif ($setup == 'INT') {
            return number_format($FZ);
        } else {
            return number_format($FZ/pow(1024, $setup), ($setup >= 1) ? 2 : 0). ' ' . $FS[$setup];
        }
    }
}
