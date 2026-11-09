<?php

namespace App\Models;

use App\Core\Model;
use PDO;

/**
 * ============================================================================
 * Model WorkProgram - Transparansi Program Kerja & Statistik Ketercapaian
 * ============================================================================
 */
class WorkProgram extends Model
{
    protected string $table = 'work_programs';

    /**
     * Mengambil daftar seluruh nama divisi unik secara alfabetis.
     */
    public function getAllDivisi(): array
    {
        $stmt = $this->db->query("SELECT DISTINCT divisi FROM work_programs ORDER BY divisi ASC");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Mengambil daftar program kerja berdasarkan nama divisi.
     */
    public function getByDivisi(string $divisi): array
    {
        $stmt = $this->db->prepare("SELECT * FROM work_programs WHERE divisi = :divisi ORDER BY id ASC");
        $stmt->execute(['divisi' => $divisi]);
        return $stmt->fetchAll();
    }

    /**
     * Menghitung ringkasan statistik program kerja (total, selesai, berlangsung, rencana,
     * evaluasi, dan rata-rata persentase progres keseluruhan).
     */
    public function getStats(): array
    {
        $stmt = $this->db->query("
            SELECT 
                COUNT(*) as total,
                COALESCE(SUM(CASE WHEN status = 'selesai' THEN 1 ELSE 0 END), 0) as selesai,
                COALESCE(SUM(CASE WHEN status = 'berlangsung' THEN 1 ELSE 0 END), 0) as berlangsung,
                COALESCE(SUM(CASE WHEN status = 'rencana' THEN 1 ELSE 0 END), 0) as rencana,
                COALESCE(SUM(CASE WHEN status = 'evaluasi' THEN 1 ELSE 0 END), 0) as evaluasi,
                COALESCE(ROUND(AVG(progres_persen), 0), 0) as rata_progres
            FROM work_programs
        ");
        return $stmt->fetch() ?: [
            'total'        => 0,
            'selesai'      => 0,
            'berlangsung'  => 0,
            'rencana'      => 0,
            'evaluasi'     => 0,
            'rata_progres' => 0
        ];
    }
}
