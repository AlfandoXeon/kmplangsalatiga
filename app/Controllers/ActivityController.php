<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Activity;
use App\Models\ActivityMedia;
use App\Models\Comment;

/**
 * ============================================================================
 * Class ActivityController - Pengendali Dokumentasi Kegiatan & Galeri Berkas
 * ============================================================================
 */
class ActivityController extends Controller
{
    private Activity $activityModel;
    private ActivityMedia $mediaModel;
    private Comment $commentModel;

    public function __construct()
    {
        $this->activityModel = new Activity();
        $this->mediaModel = new ActivityMedia();
        $this->commentModel = new Comment();
    }

    /**
     * Menampilkan daftar seluruh album dokumentasi kegiatan.
     */
    public function index(): void
    {
        $kategoriFilter = trim($_GET['kategori'] ?? '');
        $activities = $this->activityModel->getAllWithMediaCount($kategoriFilter !== '' ? $kategoriFilter : null);

        $this->view('activities/index', [
            'title'          => 'Arsip & Album Dokumentasi — K\'mplang Salatiga',
            'activities'     => $activities,
            'kategoriFilter' => $kategoriFilter
        ], 'main');
    }

    /**
     * Menampilkan detail kegiatan beserta penjelajah berkas ala Google Drive dan komentar.
     */
    public function detail(string $slug): void
    {
        $activity = $this->activityModel->findBySlug($slug);

        if (!$activity) {
            http_response_code(404);
            $this->view('errors/404', ['title' => 'Kegiatan Tidak Ditemukan'], 'main');
            return;
        }

        // Tambah jumlah tayangan
        $this->activityModel->incrementViews((int) $activity['id']);

        // Ambil seluruh berkas foto & video di dalam album ini
        $mediaList = $this->mediaModel->getByActivityId((int) $activity['id']);

        // Ambil daftar komentar yang telah disetujui
        $comments = $this->commentModel->getComments('activity', (int) $activity['id']);

        $this->view('activities/detail', [
            'title'     => $activity['judul'] . ' — K\'mplang Salatiga',
            'activity'  => $activity,
            'mediaList' => $mediaList,
            'comments'  => $comments
        ], 'main');
    }
}
