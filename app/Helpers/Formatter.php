<?php

namespace App\Helpers;

class Formatter
{
    private static array $bulan = [
        1 => 'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
    ];

    public static function tanggalIndo(?string $dateStr, bool $withTime = false): string
    {
        if (empty($dateStr)) {
            return '-';
        }

        $timestamp = strtotime($dateStr);
        if (!$timestamp) {
            return $dateStr;
        }

        $hari = date('d', $timestamp);
        $bulanIndex = (int) date('m', $timestamp);
        $tahun = date('Y', $timestamp);
        $bulanNama = self::$bulan[$bulanIndex] ?? date('F', $timestamp);

        $res = "$hari $bulanNama $tahun";
        if ($withTime) {
            $res .= ' pukul ' . date('H:i', $timestamp) . ' WIB';
        }

        return $res;
    }

    public static function formatBytes(int|float $bytes, int $precision = 2): string
    {
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);

        $bytes /= (1 << (10 * $pow));

        return round($bytes, $precision) . ' ' . $units[$pow];
    }

    public static function rupiah(string|int|float|null $amount): string
    {
        if ($amount === null || $amount === '') {
            return 'Rp 0';
        }

        if (is_string($amount) && str_starts_with(strtoupper($amount), 'RP')) {
            return $amount;
        }

        $num = (float) preg_replace('/[^\d.]/', '', (string)$amount);
        return 'Rp ' . number_format($num, 0, ',', '.');
    }
}
