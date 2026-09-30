<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Activity extends Model
{
    protected string $table = 'activities';

    public function getRecent(int $limit = 6): array
    {
        $stmt = $this->db->prepare("
            SELECT a.*, u.nama_lengkap as author_name,
                   (SELECT COUNT(*) FROM activity_media WHERE activity_id = a.id) as media_count
            FROM activities a
            LEFT JOIN users u ON a.author_id = u.id
            ORDER BY a.tanggal_kegiatan DESC, a.id DESC
            LIMIT :lim
        ");
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getAllWithMediaCount(): array
    {
        $stmt = $this->db->query("
            SELECT a.*, u.nama_lengkap as author_name,
                   (SELECT COUNT(*) FROM activity_media WHERE activity_id = a.id) as media_count
            FROM activities a
            LEFT JOIN users u ON a.author_id = u.id
            ORDER BY a.tanggal_kegiatan DESC, a.id DESC
        ");
        return $stmt->fetchAll();
    }

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

    public function incrementViews(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE activities SET view_count = view_count + 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
