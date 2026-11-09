<?php

namespace App\Core;

/**
 * ============================================================================
 * Class View - Template Engine Berbasis Output Buffering PHP Murni
 * ============================================================================
 * Cara Kerja:
 * 1. Mengekstrak variabel `$data` dari Controller agar tersedia di dalam View.
 * 2. Menyuntikkan variabel konteks global (`$currentUser`, `$isLoggedIn`,
 *    `$isAdmin`, `$isPengurus`, `$flashSuccess`, `$flashError`).
 * 3. Menangkap output HTML dari berkas View ke dalam variabel `$content` menggunakan
 *    `ob_start()` dan `ob_get_clean()`, lalu membungkusnya di dalam Layout utama.
 * ============================================================================
 */
class View
{
    /**
     * Me-render berkas View dan menyisipkannya ke dalam Layout.
     */
    public function render(string $viewPath, array $data = [], string $layout = 'main'): void
    {
        // Ekstrak array asosiatif menjadi variabel lokal untuk template
        extract($data, EXTR_SKIP);

        // Variabel helper status autentikasi & peran pengguna
        $currentUser = $_SESSION['user'] ?? null;
        $isLoggedIn = !empty($currentUser);
        $isAdmin = $isLoggedIn && (($currentUser['role'] ?? '') === 'admin');
        $isPengurus = $isLoggedIn && in_array($currentUser['role'] ?? '', ['admin', 'pengurus'], true);

        // Ambil dan bersihkan pesan Flash dari sesi (sekali pakai)
        $flashSuccess = $_SESSION['flash_success'] ?? null;
        $flashError = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $viewFile = __DIR__ . '/../Views/' . $viewPath . '.php';

        if (!file_exists($viewFile)) {
            http_response_code(500);
            echo "Berkas View [{$viewPath}] tidak ditemukan.";
            return;
        }

        // Tangkap output dari berkas View ke dalam variabel $content
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // Jika diminta tanpa pembungkus layout ('raw'), langsung cetak isi
        if ($layout === 'raw') {
            echo $content;
            return;
        }

        // Muat berkas Layout pembungkus (main, admin, atau auth)
        $layoutFile = __DIR__ . '/../Views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }
}
