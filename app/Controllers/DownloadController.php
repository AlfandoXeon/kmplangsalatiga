<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Activity;
use App\Models\ActivityMedia;
use ZipArchive;

class DownloadController extends Controller
{
    private ActivityMedia $mediaModel;
    private Activity $activityModel;

    public function __construct()
    {
        $this->mediaModel = new ActivityMedia();
        $this->activityModel = new Activity();
    }

    public function downloadFile(string $mediaId): void
    {
        $id = (int) $mediaId;
        $media = $this->mediaModel->find($id);

        if (!$media) {
            http_response_code(404);
            die('Berkas tidak ditemukan.');
        }

        // Check storage path
        $filename = basename($media['file_path']);
        $filePath = realpath(__DIR__ . '/../../storage/uploads/activities/' . $filename);

        // Fallback to public/assets/images if not in storage
        if (!$filePath || !file_exists($filePath)) {
            $filePath = realpath(__DIR__ . '/../../public/assets/images/' . $filename);
        }

        if (!$filePath || !file_exists($filePath)) {
            http_response_code(404);
            die('Berkas fisik tidak ditemukan di server.');
        }

        $downloadName = !empty($media['original_name']) ? basename($media['original_name']) : $filename;

        // Clear output buffer
        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Description: File Transfer');
        header('Content-Type: ' . ($media['mime_type'] ?: 'application/octet-stream'));
        header('Content-Disposition: attachment; filename="' . str_replace('"', '', $downloadName) . '"');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filePath));

        readfile($filePath);
        exit;
    }

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
            die('Tidak ada berkas media di dalam album ini untuk diunduh.');
        }

        if (!class_exists('ZipArchive')) {
            die('Layanan ZIP tidak didukung pada server.');
        }

        $zip = new ZipArchive();
        $tempDir = sys_get_temp_dir();
        $zipFilename = $tempDir . DIRECTORY_SEPARATOR . 'album_' . $actId . '_' . time() . '.zip';

        if ($zip->open($zipFilename, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            die('Gagal membuat arsip ZIP.');
        }

        foreach ($mediaList as $item) {
            $fname = basename($item['file_path']);
            $fpath = realpath(__DIR__ . '/../../storage/uploads/activities/' . $fname);
            if (!$fpath || !file_exists($fpath)) {
                $fpath = realpath(__DIR__ . '/../../public/assets/images/' . $fname);
            }

            if ($fpath && file_exists($fpath)) {
                $entryName = !empty($item['original_name']) ? $item['original_name'] : $fname;
                $zip->addFile($fpath, $entryName);
            }
        }

        $zip->close();

        if (!file_exists($zipFilename)) {
            die('Arsip berkas ZIP tidak dapat diproses.');
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
