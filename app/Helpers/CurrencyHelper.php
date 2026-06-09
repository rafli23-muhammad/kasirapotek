<?php

if (!function_exists('rupiah')) {
    function rupiah($angka, $prefix = 'Rp') {
        return $prefix . number_format($angka, 0, ',', '.');
    }
}
