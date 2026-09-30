<?php

namespace App\Core;

use App\Config\App;

class Controller
{
    protected function view(string $viewPath, array $data = [], string $layout = 'main'): void
    {
        $view = new View();
        $view->render($viewPath, $data, $layout);
    }

    protected function json(mixed $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
        exit;
    }

    protected function redirect(string $path): void
    {
        $url = App::baseUrl($path);
        header("Location: $url");
        exit;
    }

    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash_' . $type] = $message;
    }

    protected function user(): ?array
    {
        return $_SESSION['user'] ?? null;
    }

    protected function userId(): ?int
    {
        return isset($_SESSION['user']['id']) ? (int) $_SESSION['user']['id'] : null;
    }

    protected function validate(array $data, array $rules): array
    {
        $errors = [];
        foreach ($rules as $field => $fieldRules) {
            $value = $data[$field] ?? null;
            $ruleList = is_array($fieldRules) ? $fieldRules : explode('|', $fieldRules);

            foreach ($ruleList as $rule) {
                if ($rule === 'required' && (empty($value) && $value !== '0')) {
                    $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . ' wajib diisi.';
                    break;
                }
                if ($rule === 'email' && !empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $errors[$field] = 'Format email tidak valid.';
                    break;
                }
                if (str_starts_with($rule, 'min:') && !empty($value)) {
                    $min = (int) substr($rule, 4);
                    if (strlen((string) $value) < $min) {
                        $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " minimal $min karakter.";
                        break;
                    }
                }
                if (str_starts_with($rule, 'max:') && !empty($value)) {
                    $max = (int) substr($rule, 4);
                    if (strlen((string) $value) > $max) {
                        $errors[$field] = ucfirst(str_replace('_', ' ', $field)) . " maksimal $max karakter.";
                        break;
                    }
                }
            }
        }

        return $errors;
    }
}
