<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class WorkProgram extends Model
{
    protected string $table = 'work_programs';

    public function getAllDivisi(): array
    {
        $stmt = $this->db->query("SELECT DISTINCT divisi FROM work_programs ORDER BY divisi ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function getByDivisi(string $divisi): array
    {
        $stmt = $this->db->prepare("SELECT * FROM work_programs WHERE divisi = :divisi ORDER BY id ASC");
        $stmt->execute(['divisi' => $divisi]);
        return $stmt->fetchAll();
    }

    public function getStats(): array
    {
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END) as selesai,
                SUM(CASE WHEN status = 'berlangsung' THEN 1 ELSE 0 END) as berlangsung,
                SUM(CASE WHEN status = 'rencana' THEN 1 ELSE 0 END) as rencana
            FROM work_programs
        ");
        return $stmt->fetch() ?: ['total' => 0, 'selesai' => 0, 'berlangsung' => 0, 'rencana' => 0];
    }
}
