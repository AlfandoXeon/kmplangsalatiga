<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class Article extends Model
{
    protected string $table = 'articles';

    public function getPublished(int $limit = 10): array
    {
        $stmt = $this->db->prepare("
            SELECT a.*, u.nama_lengkap as author_name,
                   (SELECT COUNT(*) FROM comments WHERE post_type = 'article' AND post_id = a.id AND status = 'approved') as comment_count
            FROM articles a
            LEFT JOIN users u ON a.author_id = u.id
            WHERE a.status = 'published'
            ORDER BY a.created_at DESC
            LIMIT :lim
        ");
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function findBySlug(string $slug): ?array
    {
        $stmt = $this->db->prepare("
            SELECT a.*, u.nama_lengkap as author_name
            FROM articles a
            LEFT JOIN users u ON a.author_id = u.id
            WHERE a.slug = :slug AND a.status = 'published'
            LIMIT 1
        ");
        $stmt->execute(['slug' => $slug]);
        $res = $stmt->fetch();
        return $res ?: null;
    }

    public function incrementViews(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE articles SET view_count = view_count + 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
}
