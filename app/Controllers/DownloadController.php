<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Activity;
use App\Models\ActivityMedia;
use ZipArchive;

/**
 * Class DownloadController
 * -------------------------------------------------------------------------
 * Menangani pengunduhan berkas dokumentasi (satuan maupun bundel ZIP album).
 * Dilengkapi proteksi Path Traversal dengan memverifikasi basename dan
 * keberadaan fisik file di direktori resmi (`storage/uploads/activities`
 * atau `public/assets/images`).
 * -------------------------------------------------------------------------
 */
class DownloadController extends Controller
{
    private ActivityMedia $mediaModel;
    private Activity $activityModel;

    public function __construct()
    {
        $this->mediaModel = new ActivityMedia();
        $this->activityModel = new Activity();
    }

    /**
     * Resolusi aman lokasi fisik berkas media kegiatan.
     */
    private function resolveMediaFilePath(string $rawPath): ?string
    {
        $filename = basename($rawPath);
        if ($filename === '' || $filename === '.' || $filename === '..') {
            return null;
        }

        $storagePath = realpath(__DIR__ . '/../../storage/uploads/activities/' . $filename);
        if ($storagePath && is_file($storagePath)) {
            return $storagePath;
        }

        $assetPath = realpath(__DIR__ . '/../../public/assets/images/' . $filename);
        if ($assetPath && is_file($assetPath)) {
            return $assetPath;
        }

        return null;
    }

    /**
     * Mengunduh satu berkas media kegiatan berdasarkan ID.
     */
    public function downloadFile(string $mediaId): void
    {
        $id = (int) $mediaId;
        $media = $this->mediaModel->find($id);

        if (!$media) {
            http_response_code(404);
            die('Berkas tidak ditemukan.');
        }

        $filePath = $this->resolveMediaFilePath($media['file_path']);
        if (!$filePath) {
            http_response_code(404);
            die('Berkas fisik tidak ditemukan di server.');
        }

        $filename = basename($filePath);
        $downloadName = !empty($media['original_name']) ? basename($media['original_name']) : $filename;

        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: ' . ($media['mime_type'] ?: 'application/octet-stream'));
        header('Content-Disposition: attachment; filename="' . str_replace(['"', "\r", "\n"], '', $downloadName) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);
        exit;
    }

    /**
     * Mengunduh seluruh berkas dalam satu kegiatan sebagai arsip ZIP.
     */
    public function downloadAlbum(string $activityId): void
    {
        $actId = (int) $activityId;
        $activity = $this->activityModel->find($actId);

        if (!$activity) {
            http_response_code(404);
            die('Kegiatan tidak ditemukan.');
        }

        $mediaList = $this->mediaModel->getByActivityId($actId);
        if (empty($mediaList)) {
            $this->redirect('/kegiatan/' . $activity['slug'], 'error', 'Tidak ada berkas media di dalam album ini untuk diunduh.');
        }

        if (!class_exists('ZipArchive')) {
            $this->redirect('/kegiatan/' . $activity['slug'], 'error', 'Ekstensi ZipArchive tidak aktif pada server PHP.');
        }

        $zip = new ZipArchive();
        // Gunakan folder storage/temp lokal terlebih dahulu agar aman dari pembatasan open_basedir di Cloud Hosting (InfinityFree)
        $localTempDir = dirname(__DIR__, 2) . DIRECTORY_SEPARATOR . 'storage' . DIRECTORY_SEPARATOR . 'temp';
        if (!is_dir($localTempDir)) {
            @mkdir($localTempDir, 0775, true);
        }
        $tempDir = (is_dir($localTempDir) && is_writable($localTempDir)) ? $localTempDir : sys_get_temp_dir();
        $zipFilename = $tempDir . DIRECTORY_SEPARATOR . 'kmplang_album_' . $actId . '_' . uniqid() . '.zip';

        if ($zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            $this->redirect('/kegiatan/' . $activity['slug'], 'error', 'Gagal membuat arsip ZIP sementara.');
        }

        $addedCount = 0;
        foreach ($mediaList as $index => $item) {
            $fpath = $this->resolveMediaFilePath($item['file_path']);
            if ($fpath) {
                $entryName = !empty($item['original_name']) ? basename($item['original_name']) : basename($fpath);
                // Cegah nama file duplikat di dalam ZIP
                $zip->addFile($fpath, sprintf('%02d_%s', $index + 1, $entryName));
                $addedCount++;
            }
        }

        $zip->close();

        if ($addedCount === 0 || !file_exists($zipFilename)) {
            @unlink($zipFilename);
            $this->redirect('/kegiatan/' . $activity['slug'], 'error', 'Berkas fisik dokumentasi tidak tersedia untuk diarsipkan.');
        }

        $downloadTitle = preg_replace('/[^a-zA-Z0-9_-]/', '_', $activity['judul']) . '_Album.zip';

        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: application/zip');
        header('Content-Disposition: attachment; filename="' . $downloadTitle . '"');
        header('Content-Length: ' . filesize($zipFilename));
        header('Pragma: no-cache');
        header('Expires: 0');

        readfile($zipFilename);
        @unlink($zipFilename);
        exit;
    }
}
