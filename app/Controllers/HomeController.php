<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\SiteSetting;
use App\Models\WorkProgram;
use App\Models\Activity;
use App\Models\ActivityMedia;
use App\Models\Article;
use App\Models\Announcement;
use App\Models\User;

/**
 * ============================================================================
 * Class HomeController - Pengendali Halaman Utama (Landing Page Poster)
 * ============================================================================
 * Mengagregasi data dari berbagai modul (CMS Settings, Pengumuman/Agenda,
 * Program Kerja Unggulan, Kegiatan Terbaru, Galeri Foto, Artikel Budaya, dan
 * Statistik Organisasi) untuk disajikan pada halaman beranda.
 * ============================================================================
 */
class HomeController extends Controller
{
    public function index(): void
    {
        $settingModel = new SiteSetting();
        $prokerModel = new WorkProgram();
        $activityModel = new Activity();
        $mediaModel = new ActivityMedia();
        $articleModel = new Article();
        $announcementModel = new Announcement();
        $userModel = new User();

        // Ambil pengaturan teks dinamis dari CMS
        $settings = $settingModel->getAllKeyValue();

        // Ambil daftar proker, kegiatan, foto galeri, artikel, dan pengumuman aktif
        $prokerList = $prokerModel->all('id ASC');
        $prokerStats = $prokerModel->getStats();
        $recentActivities = $activityModel->getRecent(6);
        $galleryPhotos = $mediaModel->getRecentPhotos(6);
        $recentArticles = $articleModel->getPublished(3);
        $announcements = $announcementModel->getActive(3);

        // Hitung metrik statistik utama organisasi
        $stats = [
            'total_anggota'  => $userModel->count(),
            'total_proker'   => (int) ($prokerStats['total'] ?? count($prokerList)),
            'rata_progres'   => (int) ($prokerStats['rata_progres'] ?? 0),
            'total_kegiatan' => $activityModel->count()
        ];

        $this->view('home/index', [
            'title'            => "K'mplang Salatiga — Keluarga Mahasiswa Perantauan Lampung",
            'settings'         => $settings,
            'announcements'    => $announcements,
            'prokerList'       => $prokerList,
            'featuredProkers'  => array_slice($prokerList, 0, 6),
            'recentActivities' => $recentActivities,
            'galleryPhotos'    => $galleryPhotos,
            'recentArticles'   => $recentArticles,
            'stats'            => $stats
        ], 'main');
    }
}
