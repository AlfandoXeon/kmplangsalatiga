<?php

namespace App\Helpers;

use App\Config\App;

class Encryption
{
    private static string $cipher = 'AES-256-CBC';

    private static function getKey(): string
    {
        $rawKey = App::get('APP_KEY', 'default_secret_key_kmplang_2026');
        if (str_starts_with($rawKey, 'base64:')) {
            $rawKey = substr($rawKey, 7);
        }
        return hash('sha256', $rawKey, true);
    }

    public static function encrypt(string $plainText): string
    {
        if ($plainText === '') {
            return '';
        }

        $key = self::getKey();
        $ivLength = openssl_cipher_iv_length(self::$cipher);
        $iv = openssl_random_pseudo_bytes($ivLength);

        $cipherText = openssl_encrypt($plainText, self::$cipher, $key, OPENSSL_RAW_DATA, $iv);
        $hmac = hash_hmac('sha256', $iv . $cipherText, $key, true);

        return base64_encode($iv . $hmac . $cipherText);
    }

    public static function decrypt(string $cipherData): string
    {
        if ($cipherData === '') {
            return '';
        }

        $decoded = base64_decode($cipherData, true);
        if ($decoded === false) {
            return '';
        }

        $key = self::getKey();
        $ivLength = openssl_cipher_iv_length(self::$cipher);
        $hmacLength = 32;

        if (strlen($decoded) < $ivLength + $hmacLength) {
            return '';
        }

        $iv = substr($decoded, 0, $ivLength);
        $hmac = substr($decoded, $ivLength, $hmacLength);
        $cipherText = substr($decoded, $ivLength + $hmacLength);

        $calculatedHmac = hash_hmac('sha256', $iv . $cipherText, $key, true);
        if (!hash_equals($hmac, $calculatedHmac)) {
            return '';
        }

        $decrypted = openssl_decrypt($cipherText, self::$cipher, $key, OPENSSL_RAW_DATA, $iv);
        return $decrypted !== false ? $decrypted : '';
    }
}
