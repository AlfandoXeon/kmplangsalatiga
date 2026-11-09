<?php

namespace App\Models;

use App\Core\Model;
use App\Config\Security;
use PDO;
use Throwable;

/**
 * ============================================================================
 * Model AuditLog - Pencatatan Jejak Audit & Aktivitas Keamanan Sistem
 * ============================================================================
 * Mencatat setiap aksi penting (Login, Register, CRUD Admin, Verifikasi Anggota)
 * lengkap dengan IP Address dan User-Agent peramban.
 * ============================================================================
 */
class AuditLog extends Model
{
    protected string $table = 'audit_logs';

    /**
     * Mencatat satu entri log audit secara fail-safe (tidak menghentikan alur utama jika gagal).
     */
    public static function record(?int $userId, string $action, ?string $table = null, ?int $targetId = null, ?string $details = null): void
    {
        try {
            $log = new self();
            $log->create([
                'user_id'      => $userId,
                'action'       => $action,
                'target_table' => $table,
                'target_id'    => $targetId,
                'ip_address'   => Security::getClientIp(),
                'user_agent'   => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
                'details'      => $details
            ]);
        } catch (Throwable) {
            // Abaikan error pencatatan log agar transaksi utama tetap berjalan
        }
    }

    /**
     * Mengambil daftar riwayat aktivitas terbaru beserta nama pelakunya.
     */
    public function getRecentLogs(int $limit = 20): array
    {
        $stmt = $this->db->prepare("
            SELECT l.*, u.nama_lengkap, u.role
            FROM audit_logs l
            LEFT JOIN users u ON l.user_id = u.id
            ORDER BY l.created_at DESC, l.id DESC
            LIMIT :lim
        ");
        $stmt->bindValue(':lim', $limit, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
