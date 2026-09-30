<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class ActivityMedia extends Model
{
    protected string $table = 'activity_media';

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

    public function getRecentPhotos(int $limit = 6): array
    {
        $stmt = $this->db->prepare("
            SELECT m.*, a.judul as activity_title, a.slug as activity_slug
            FROM activity_media m
            JOIN activities a ON m.activity_id = a.id
            WHERE m.file_type = 'image'
            ORDER BY m.id ASC
            LIMIT :lim
        ");
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
