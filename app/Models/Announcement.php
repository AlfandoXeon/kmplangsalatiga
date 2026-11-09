<?php

namespace App\Models;

use App\Core\Model;
use PDO;

/**
 * ============================================================================
 * Model Announcement - Papan Pengumuman & Agenda Poster Organisasi
 * ============================================================================
 * Mengelola informasi pengumuman penting dan agenda terdekat K'mplang Salatiga.
 * ============================================================================
 */
class Announcement extends Model
{
    protected string $table = 'announcements';

    /**
     * Mengambil daftar pengumuman aktif untuk ditampilkan pada halaman publik.
     */
    public function getActive(int $limit = 4): array
    {
        $stmt = $this->db->prepare("
            SELECT a.*, u.nama_lengkap as author_name
            FROM announcements a
            LEFT JOIN users u ON a.author_id = u.id
            WHERE a.is_active = 1
            ORDER BY CASE WHEN a.tipe = 'penting' THEN 0 WHEN a.tipe = 'agenda' THEN 1 ELSE 2 END,
                     a.id DESC
            LIMIT :lim
        ");
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Mengambil seluruh pengumuman beserta nama pembuatnya untuk Panel Admin.
     */
    public function getAllWithAuthor(): array
    {
        $stmt = $this->db->query("
            SELECT a.*, u.nama_lengkap as author_name
            FROM announcements a
            LEFT JOIN users u ON a.author_id = u.id
            ORDER BY a.id DESC
        ");
        return $stmt->fetchAll();
    }
}
