<?php

namespace App\Helpers;

use App\Config\App;

class FileUpload
{
    private static array $allowedMimes = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'video/mp4'  => 'mp4',
        'video/webm' => 'webm'
    ];

    public static function upload(array $file, string $targetSubDir = 'activities'): array
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            return ['success' => false, 'error' => 'Parameter berkas tidak valid.'];
        }

        switch ($file['error']) {
            case UPLOAD_ERR_OK:
                break;
            case UPLOAD_ERR_NO_FILE:
                return ['success' => false, 'error' => 'Tidak ada berkas yang diunggah.'];
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                return ['success' => false, 'error' => 'Ukuran berkas melebihi batas upload server.'];
            default:
                return ['success' => false, 'error' => 'Terjadi kesalahan sistem saat mengunggah berkas.'];
        }

        $maxSize = (int) App::get('UPLOAD_MAX_SIZE', 52428800); // 50MB
        if ($file['size'] > $maxSize) {
            return ['success' => false, 'error' => 'Ukuran berkas melebihi batas maksimal 50 MB.'];
        }

        // Check real MIME type using finfo
        $finfo = new \finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($file['tmp_name']);

        if (!array_key_exists($mime, self::$allowedMimes)) {
            return ['success' => false, 'error' => "Tipe berkas ($mime) tidak diizinkan. Hanya menerima JPG, PNG, WEBP, MP4, WEBM."];
        }

        $ext = self::$allowedMimes[$mime];
        $isImage = str_starts_with($mime, 'image/');
        $fileType = $isImage ? 'image' : 'video';

        // Cryptographically secure random filename
        $uniqueName = bin2hex(random_bytes(16)) . '.' . $ext;

        $baseStorageDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'uploads';
        $targetDir = $baseStorageDir . DIRECTORY_SEPARATOR . $targetSubDir;
        if (!is_dir($targetDir)) {
            @mkdir($targetDir, 0777, true);
        }

        $destination = $targetDir . DIRECTORY_SEPARATOR . $uniqueName;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            return ['success' => false, 'error' => 'Gagal memindahkan berkas ke folder penyimpanan. Pastikan folder storage/uploads memiliki izin tulis (writable).'];
        }

        return [
            'success'       => true,
            'file_name'     => $uniqueName,
            'file_path'     => $uniqueName,
            'original_name' => basename($file['name']),
            'mime_type'     => $mime,
            'file_type'     => $fileType,
            'file_size'     => (int) $file['size'],
            'full_path'     => $destination
        ];
    }
}
