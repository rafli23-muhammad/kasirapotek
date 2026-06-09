<?php

use Illuminate\Support\Str;

if (!function_exists('text_limiter')) {
    /**
     * 
     *
     * @param string
     * @param int 
     * @param string 
     * @return string
     */
    function text_limiter($text, $limit = 50, $end = '...') {
        return Str::limit($text, $limit, $end);
    }
}
