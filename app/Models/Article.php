<?php

namespace App\Models;

use App\Core\Model;
use PDO;

/**
 * ============================================================================
 * Model Article - Pengelola Artikel Budaya, Sejarah Lampung & Berita K'mplang
 * ============================================================================
 */
class Article extends Model
{
    protected string $table = 'articles';

    /**
     * Mengambil daftar artikel berstatus 'published' dengan filter kategori opsional.
     */
    public function getPublished(int $limit = 20, ?string $kategori = null): array
    {
        $sql = "
            SELECT a.*, u.nama_lengkap as author_name,
                   (SELECT COUNT(*) FROM comments WHERE post_type = 'article' AND post_id = a.id AND status = 'approved') as comment_count
            FROM articles a
            LEFT JOIN users u ON a.author_id = u.id
            WHERE a.status = 'published'
        ";

        if (!empty($kategori)) {
            $sql .= " AND a.kategori = :kat";
        }

        $sql .= " ORDER BY a.created_at DESC LIMIT :lim";

        $stmt = $this->db->prepare($sql);
        if (!empty($kategori)) {
            $stmt->bindValue(':kat', $kategori, PDO::PARAM_STR);
        }
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }

    /**
     * Mengambil daftar kategori unik dari artikel yang sudah dipublikasikan.
     */
    public function getCategories(): array
    {
        $stmt = $this->db->query("
            SELECT DISTINCT kategori
            FROM articles
            WHERE status = 'published'
            ORDER BY kategori ASC
        ");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Mencari artikel yang dipublikasikan berdasarkan slug URL.
     */
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

    /**
     * Menambah jumlah pembaca (view_count) artikel.
     */
    public function incrementViews(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE articles SET view_count = view_count + 1 WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    /**
     * Menghitung estimasi waktu baca artikel dalam satuan menit (asumsi rata-rata 180 kata/menit).
     */
    public static function estimateReadingTime(string $content): int
    {
        $plainText = trim(strip_tags($content));
        if ($plainText === '') {
            return 1;
        }
        $wordCount = str_word_count($plainText);
        return max(1, (int) ceil($wordCount / 180));
    }
}
