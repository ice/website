<?php

namespace App\Libraries;

/**
 * Tools.
 *
 * @category Library
 * @package  Website
 * @author   Ice <info@iceframework.org>
 * @license  iceframework.org Ice
 * @link     iceframework.org
 */
class Tool
{

    /**
     * Get the human file size
     *
     * @param string $file  Path to the file
     * @param string $setup Setup
     *
     * @return string
     */
    public static function fileSize($file, $setup = null)
    {
        $FZ = ($file && @is_file($file)) ? filesize($file) : null;
        $FS = ["B","kB","MB","GB","TB","PB","EB","ZB","YB"];

        if (!$setup && $setup !== 0) {
            return number_format($FZ / pow(1024, $I = floor(log($FZ, 1024))), ($I >= 1) ? 2 : 0) . ' ' . $FS[$I];
        } elseif ($setup == 'INT') {
            return number_format($FZ);
        } else {
            return number_format($FZ / pow(1024, $setup), ($setup >= 1) ? 2 : 0) . ' ' . $FS[$setup];
        }
    }
}
