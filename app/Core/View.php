<?php

namespace App\Core;

use App\Config\App;

class View
{
    public function render(string $viewPath, array $data = [], string $layout = 'main'): void
    {
        // Extract data to local variables for views
        extract($data);

        // Current user session helper
        $currentUser = $_SESSION['user'] ?? null;
        $isLoggedIn = !empty($currentUser);
        $isAdmin = $isLoggedIn && ($currentUser['role'] === 'admin');
        $isPengurus = $isLoggedIn && in_array($currentUser['role'], ['admin', 'pengurus'], true);

        // Flash message helper
        $flashSuccess = $_SESSION['flash_success'] ?? null;
        $flashError = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $viewFile = __DIR__ . '/../Views/' . $viewPath . '.php';

        if (!file_exists($viewFile)) {
            echo "View file [$viewFile] tidak ditemukan.";
            return;
        }

        // Buffer view content
        ob_start();
        require $viewFile;
        $content = ob_get_clean();

        // If no layout requested, render raw content
        if ($layout === 'raw') {
            echo $content;
            return;
        }

        // Load layout
        $layoutFile = __DIR__ . '/../Views/layouts/' . $layout . '.php';
        if (file_exists($layoutFile)) {
            require $layoutFile;
        } else {
            echo $content;
        }
    }
}
