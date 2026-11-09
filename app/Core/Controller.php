<?php

namespace App\Core;

use App\Config\App;

/**
 * ============================================================================
 * Class Controller - Base Controller Aplikasi MVC
 * ============================================================================
 * Menyediakan metode utilitas bagi seluruh Controller turunan:
 * - Rendering View dan Layout (`view()`).
 * - Respon JSON terstandarisasi (`json()`).
 * - Redirect HTTP aman (`redirect()`).
 * - Pengelolaan Flash Message sesi (`setFlash()`).
 * - Pengambilan profil pengguna yang sedang login (`user()`, `userId()`).
 * - Mesin validasi input deklaratif (`validate()`).
 * ============================================================================
 */
class Controller
{
    /**
     * Me-render berkas View di dalam Layout tertentu.
     */
    protected function view(string $viewPath, array $data = [], string $layout = 'main'): void
    {
        $view = new View();
        $view->render($viewPath, $data, $layout);
    }

    /**
     * Mengirimkan respon berformat JSON dan menghentikan eksekusi skrip.
     */
    protected function json(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    /**
     * Mengalihkan pengguna ke path URL internal aplikasi dengan perlindungan
     * terhadap Open Redirect dan HTTP Response Splitting (CRLF Injection).
     */
    protected function redirect(string $path, ?string $flashType = null, ?string $flashMessage = null): void
    {
        if ($flashType !== null && $flashMessage !== null) {
            $this->setFlash($flashType, $flashMessage);
        }

        // Bersihkan karakter CRLF untuk mencegah HTTP Header Injection
        $path = str_replace(["\r", "\n", "\0"], '', $path);

        // Cegah Open Redirect: pastikan path bersifat internal kecuali sudah diawali baseUrl
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            $base = App::baseUrl();
            if (!str_starts_with($path, $base)) {
                $path = '/';
            } else {
                header("Location: $path");
                exit;
            }
        }

        $url = App::baseUrl($path);
        header("Location: $url");
        exit;
    }

    /**
     * Menyimpan pesan notifikasi satu-kali-tampil (flash message) ke dalam sesi.
     *
     * @param string $type 'success' atau 'error'
     */
    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash_' . $type] = $message;
    }

    /**
     * Mengambil data sesi pengguna yang sedang login.
     */
    protected function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    /**
     * Mengambil ID pengguna yang sedang login (null jika tamu).
     */
    protected function userId(): ?int
    {
        return isset($_SESSION['user']['id']) ? (int) $_SESSION['user']['id'] : null;
    }

    /**
     * Memvalidasi array data input berdasarkan aturan deklaratif.
     * Aturan yang didukung:
     * - `required`       : Wajib diisi (tidak boleh kosong).
     * - `email`          : Format alamat email standar.
     * - `numeric`        : Harus berupa angka.
     * - `min:N`          : Panjang karakter minimal N.
     * - `max:N`          : Panjang karakter maksimal N.
     * - `in:a,b,c`       : Nilai harus salah satu dari daftar opsi.
     *
     * @param array<string, mixed> $data
     * @param array<string, string|array<string>> $rules
     * @return array<string, string> Daftar pesan kesalahan per field
     */
    protected function validate(array $data, array $rules): array
    {
        $errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            $label = ucfirst(str_replace('_', ' ', $field));
            $ruleList = is_array($fieldRules) ? $fieldRules : explode('|', $fieldRules);

            foreach ($ruleList as $rule) {
                $strVal = is_scalar($value) ? trim((string) $value) : '';

                if ($rule === 'required' && $strVal === '') {
                    $errors[$field] = "{$label} wajib diisi.";
                    break;
                }

                // Lewati aturan lanjutan jika field kosong dan tidak required
                if ($strVal === '') {
                    continue;
                }

                if ($rule === 'email' && !filter_var($strVal, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = "Format {$label} tidak valid.";
                    break;
                }

                if ($rule === 'numeric' && !is_numeric($strVal)) {
                    $errors[$field] = "{$label} harus berupa angka.";
                    break;
                }

                if (str_starts_with($rule, 'min:')) {
                    $min = (int) substr($rule, 4);
                    if (mb_strlen($strVal) < $min) {
                        $errors[$field] = "{$label} minimal {$min} karakter.";
                        break;
                    }
                }

                if (str_starts_with($rule, 'max:')) {
                    $max = (int) substr($rule, 4);
                    if (mb_strlen($strVal) > $max) {
                        $errors[$field] = "{$label} maksimal {$max} karakter.";
                        break;
                    }
                }

                if (str_starts_with($rule, 'in:')) {
                    $allowed = explode(',', substr($rule, 3));
                    if (!in_array($strVal, $allowed, true)) {
                        $errors[$field] = "Pilihan {$label} tidak valid.";
                        break;
                    }
                }

                if ($rule === 'phone' && !preg_match('/^[0-9+\-\s()]{8,20}$/', $strVal)) {
                    $errors[$field] = "Format nomor {$label} tidak valid (hanya angka, +, -, atau spasi, 8-20 digit).";
                    break;
                }

                if ($rule === 'nim' && !preg_match('/^[a-zA-Z0-9.\-\/]{4,30}$/', $strVal)) {
                    $errors[$field] = "Format {$label} tidak valid (4-30 karakter alfanumerik).";
                    break;
                }

                if ($rule === 'year') {
                    $yr = (int) $strVal;
                    if (!preg_match('/^\d{4}$/', $strVal) || $yr < 1990 || $yr > 2035) {
                        $errors[$field] = "{$label} harus berupa tahun 4 digit yang valid (1990–2035).";
                        break;
                    }
                }

                if ($rule === 'date') {
                    $parts = explode('-', $strVal);
                    if (count($parts) !== 3 || !checkdate((int) $parts[1], (int) $parts[2], (int) $parts[0])) {
                        $errors[$field] = "Format tanggal {$label} tidak valid (YYYY-MM-DD).";
                        break;
                    }
                }

                if ($rule === 'url' && !filter_var($strVal, FILTER_VALIDATE_URL)) {
                    $errors[$field] = "Format tautan {$label} tidak valid.";
                    break;
                }

                if ($rule === 'no_script') {
                    if (preg_match('/(<script|<iframe|<object|<embed|javascript:|vbscript:|onload\s*=|onerror\s*=|onclick\s*=|union\s+select)/i', $strVal)) {
                        $errors[$field] = "Input {$label} mengandung ekspresi atau kode yang tidak diizinkan.";
                        break;
                    }
                }
            }
        }

        return $errors;
    }
}
