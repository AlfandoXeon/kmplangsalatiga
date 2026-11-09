<?php

namespace App\Models;

use App\Core\Model;
use PDO;

/**
 * ============================================================================
 * Model Activity - Pengelola Data Kegiatan & Album Dokumentasi
 * ============================================================================
 */
class Activity extends Model
{
    protected string $table = 'activities';

    /**
     * Mengambil daftar kegiatan terbaru beserta nama penulis dan jumlah berkas media di dalamnya.
     */
    public function getRecent(int $limit = 6): array
    {
        $stmt = $this->db->prepare("
            SELECT a.*, u.nama_lengkap as author_name,
                   (SELECT COUNT(*) FROM activity_media WHERE activity_id = a.id) as media_count
            FROM activities a
            LEFT JOIN users u ON a.author_id = u.id
            ORDER BY a.is_featured DESC, a.tanggal_kegiatan DESC, a.id DESC
            LIMIT :lim
        ");
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Mengambil seluruh kegiatan beserta jumlah media dan jumlah komentar.
     */
    public function getAllWithMediaCount(?string $kategori = null): array
    {
        if (!empty($kategori)) {
            $stmt = $this->db->prepare("
                SELECT a.*, u.nama_lengkap as author_name,
                       (SELECT COUNT(*) FROM activity_media WHERE activity_id = a.id) as media_count,
                       (SELECT COUNT(*) FROM comments WHERE post_type = 'activity' AND post_id = a.id AND status = 'approved') as comment_count
                FROM activities a
                LEFT JOIN users u ON a.author_id = u.id
                WHERE a.kategori_kegiatan = :kat
                ORDER BY a.tanggal_kegiatan DESC, a.id DESC
            ");
            $stmt->execute(['kat' => $kategori]);
            return $stmt->fetchAll();
        }

        $stmt = $this->db->query("
            SELECT a.*, u.nama_lengkap as author_name,
                   (SELECT COUNT(*) FROM activity_media WHERE activity_id = a.id) as media_count,
                   (SELECT COUNT(*) FROM comments WHERE post_type = 'activity' AND post_id = a.id AND status = 'approved') as comment_count
            FROM activities a
            LEFT JOIN users u ON a.author_id = u.id
            ORDER BY a.tanggal_kegiatan DESC, a.id DESC
        ");
        return $stmt->fetchAll();
    }

    /**
     * Mencari satu kegiatan berdasarkan slug URL.
     */
    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("
            SELECT a.*, u.nama_lengkap as author_name
            FROM activities a
            LEFT JOIN users u ON a.author_id = u.id
            WHERE a.slug = :slug
            LIMIT 1
        ");
        $stmt->execute(['slug' => $slug]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    /**
     * Menambah penghitung jumlah tayangan (view count) secara atomik.
     */
    public function incrementViews(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE activities SET view_count = view_count + 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
