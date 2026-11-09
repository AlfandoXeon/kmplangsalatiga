<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Config\Database;
use App\Models\User;
use App\Models\WorkProgram;
use App\Models\Activity;
use App\Models\Article;
use App\Models\Announcement;
use App\Models\AuditLog;
use App\Helpers\Sanitizer;

/**
 * ============================================================================
 * Class DashboardController - Pusat Kendali Utama Panel Admin & Pengurus
 * ============================================================================
 * Menyajikan ringkasan statistik organisasi, pendaftar terbaru, log audit
 * keamanan, serta manajemen Papan Pengumuman & Agenda Poster (`announcements`).
 * ============================================================================
 */
class DashboardController extends Controller
{
    public function index(): void
    {
        $userModel = new User();
        $prokerModel = new WorkProgram();
        $activityModel = new Activity();
        $articleModel = new Article();
        $announcementModel = new Announcement();
        $auditModel = new AuditLog();

        $db = Database::getConnection();

        $totalMembers = $userModel->count();
        $pendingMembers = (int) $db->query("SELECT COUNT(*) FROM users WHERE status = 'pending'")->fetchColumn();
        $prokerStats = $prokerModel->getStats();
        $totalProker = (int) ($prokerStats['total'] ?? 0);
        $rataProgres = (int) ($prokerStats['rata_progres'] ?? 0);
        $totalActivities = $activityModel->count();
        $totalArticles = $articleModel->count();

        $recentMembers = $db->query("SELECT * FROM users ORDER BY id DESC LIMIT 5")->fetchAll();
        $recentLogs = $auditModel->getRecentLogs(10);
        $announcements = $announcementModel->getAllWithAuthor();

        $this->view('admin/dashboard', [
            'title'           => 'Dashboard Kendali — K\'mplang Salatiga',
            'totalMembers'    => $totalMembers,
            'pendingMembers'  => $pendingMembers,
            'totalProker'     => $totalProker,
            'rataProgres'     => $rataProgres,
            'totalActivities' => $totalActivities,
            'totalArticles'   => $totalArticles,
            'recentMembers'   => $recentMembers,
            'recentLogs'      => $recentLogs,
            'announcements'   => $announcements
        ], 'admin');
    }

    /**
     * Menambahkan pengumuman atau agenda poster baru dari Dashboard Admin.
     */
    public function storeAnnouncement(): void
    {
        $data = Sanitizer::cleanInput($_POST);
        $judul = trim($data['judul'] ?? '');
        $isi = trim($data['isi'] ?? '');
        $tipe = in_array($data['tipe'] ?? '', ['agenda', 'pengumuman', 'penting'], true)
            ? $data['tipe']
            : 'pengumuman';

        if ($judul === '' || $isi === '') {
            $this->setFlash('error', 'Judul dan isi pengumuman wajib diisi.');
            $this->redirect('/admin/dashboard');
        }

        $announcementModel = new Announcement();
        $id = $announcementModel->create([
            'judul'         => $judul,
            'isi'           => $isi,
            'tipe'          => $tipe,
            'tanggal_acara' => !empty($data['tanggal_acara']) ? $data['tanggal_acara'] : null,
            'lokasi'        => !empty($data['lokasi']) ? $data['lokasi'] : null,
            'tautan'        => !empty($data['tautan']) ? $data['tautan'] : null,
            'is_active'     => 1,
            'author_id'     => $this->userId()
        ]);

        AuditLog::record($this->userId(), 'CREATE_ANNOUNCEMENT', 'announcements', $id, "Created announcement: {$judul}");
        $this->setFlash('success', 'Poster pengumuman/agenda berhasil diterbitkan.');
        $this->redirect('/admin/dashboard');
    }

    /**
     * Mengubah status aktif/nonaktif pada pengumuman.
     */
    public function toggleAnnouncement(string $id): void
    {
        $annId = (int) $id;
        $announcementModel = new Announcement();
        $ann = $announcementModel->find($annId);

        if ($ann) {
            $newState = ((int) $ann['is_active'] === 1) ? 0 : 1;
            $announcementModel->update($annId, ['is_active' => $newState]);
            AuditLog::record($this->userId(), 'TOGGLE_ANNOUNCEMENT', 'announcements', $annId, "Set active={$newState}");
            $this->setFlash('success', 'Status tampilan pengumuman diperbarui.');
        }

        $this->redirect('/admin/dashboard');
    }

    /**
     * Menghapus pengumuman dari papan informasi.
     */
    public function deleteAnnouncement(string $id): void
    {
        $annId = (int) $id;
        $announcementModel = new Announcement();
        $announcementModel->delete($annId);
        AuditLog::record($this->userId(), 'DELETE_ANNOUNCEMENT', 'announcements', $annId, "Deleted announcement #{$annId}");

        $this->setFlash('success', 'Pengumuman berhasil dihapus.');
        $this->redirect('/admin/dashboard');
    }
}
