<?php

if (!function_exists('mask_nik')) {
    function mask_nik(?string $nik): string
    {
        if (!$nik || strlen($nik) < 6) {
            return '-';
        }

        $awal = substr($nik, 0, 3);      // 3 digit awal
        $akhir = substr($nik, -2);       // 2 digit akhir
        $bintang = str_repeat('*', strlen($nik) - 5);

        return $awal . $bintang . $akhir;
    }

    function mask_nama(?string $nama): string
    {
        if (!$nama) return '-';

        $parts = explode(' ', $nama);
        return $parts[0] . ' ' . substr(end($parts), 0, 1) . '.';
    }
}
