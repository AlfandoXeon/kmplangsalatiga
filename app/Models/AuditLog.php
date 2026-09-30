<?php

namespace App\Models;

use App\Core\Model;

class AuditLog extends Model
{
    protected string $table = 'audit_logs';

    public static function record(?int $userId, string $action, ?string $table = null, ?int $targetId = null, ?string $details = null): void
    {
        try {
            $log = new self();
            $log->create([
                'user_id'      => $userId,
                'action'       => $action,
                'target_table' => $table,
                'target_id'    => $targetId,
                'ip_address'   => $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1',
                'user_agent'   => substr($_SERVER['HTTP_USER_AGENT'] ?? '', 0, 500),
                'details'      => $details
            ]);
        } catch (\Throwable $e) {
            // Silently ignore to not break main flow
        }
    }

    public function getRecentLogs(int $limit = 20): array
    {
        $stmt = $this->db->prepare("
            SELECT l.*, u.nama_lengkap, u.role
            FROM audit_logs l
            LEFT JOIN users u ON l.user_id = u.id
            ORDER BY l.created_at DESC
            LIMIT :lim
        ");
        $stmt->bindValue(':lim', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll();
    }
}
