<?php

namespace App\Models;

use App\Core\Model;

/**
 * ============================================================================
 * Model Comment - Pengelola Diskusi & Tanggapan Anggota pada Kegiatan/Artikel
 * ============================================================================
 */
class Comment extends Model
{
    protected string $table = 'comments';

    /**
     * Mengambil daftar komentar berstatus 'approved' beserta identitas pengirimnya.
     */
    public function getComments(string $postType, int $postId): array
    {
        $stmt = $this->db->prepare("
            SELECT c.*, u.nama_lengkap, u.role, u.jabatan_organisasi, u.fakultas, u.angkatan
            FROM comments c
            JOIN users u ON c.user_id = u.id
            WHERE c.post_type = :pt AND c.post_id = :pid AND c.status = 'approved'
            ORDER BY c.created_at ASC
        ");
        $stmt->execute(['pt' => $postType, 'pid' => $postId]);
        return $stmt->fetchAll();
    }
}
