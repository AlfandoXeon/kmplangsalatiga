<?php

namespace App\Models;

use App\Core\Model;
use PDO;

/**
 * ============================================================================
 * Model ActivityMedia - Pengelola Berkas Foto & Video di Dalam Album Kegiatan
 * ============================================================================
 */
class ActivityMedia extends Model
{
    protected string $table = 'activity_media';

    /**
     * Mengambil seluruh berkas media yang tergabung dalam satu album kegiatan.
     * Diurutkan berdasarkan tipe berkas (foto terlebih dahulu, lalu video).
     */
    public function getByActivityId(int $activityId): array
    {
        $stmt = $this->db->prepare("
            SELECT * FROM activity_media 
            WHERE activity_id = :id 
            ORDER BY file_type ASC, id ASC
        ");
        $stmt->execute(['id' => $activityId]);
        return $stmt->fetchAll();
    }

    /**
     * Mengambil koleksi foto terbaru untuk ditampilkan pada Galeri Beranda.
     */
    public function getRecentPhotos(int $limit = 6): array
    {
        $stmt = $this->db->prepare("
            SELECT m.*, a.judul as activity_title, a.slug as activity_slug, a.tanggal_kegiatan
            FROM activity_media m
            JOIN activities a ON m.activity_id = a.id
            WHERE m.file_type = 'image'
            ORDER BY m.id DESC
            LIMIT :lim
        ");
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
