<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\User;
use App\Models\AuditLog;
use App\Helpers\Encryption;

class MemberAdminController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index(): void
    {
        $statusFilter = $_GET['status'] ?? '';
        $db = \App\Config\Database::getConnection();

        if (!empty($statusFilter)) {
            $stmt = $db->prepare("SELECT * FROM users WHERE status = :st ORDER BY id DESC");
            $stmt->execute(['st' => $statusFilter]);
        } else {
            $stmt = $db->query("SELECT * FROM users ORDER BY CASE WHEN status = 'pending' THEN 0 ELSE 1 END, id DESC");
        }

        $members = $stmt->fetchAll();
        foreach ($members as &$m) {
            $m['whatsapp'] = !empty($m['no_whatsapp_encrypted'])
                ? Encryption::decrypt($m['no_whatsapp_encrypted'])
                : '-';
        }

        $this->view('admin/members', [
            'title'        => 'Manajemen Anggota - K\'mplang Salatiga',
            'members'      => $members,
            'statusFilter' => $statusFilter,
            'enableAos'    => false
        ], 'admin');
    }

    public function updateStatus(string $id): void
    {
        $userId = (int) $id;
        $status = $_POST['status'] ?? 'active';

        if (!in_array($status, ['active', 'pending', 'rejected'], true)) {
            $this->setFlash('error', 'Status tidak valid.');
            $this->redirect('/admin/members');
        }

        $this->userModel->updateStatus($userId, $status);
        AuditLog::record($this->userId(), 'UPDATE_MEMBER_STATUS', 'users', $userId, "Status changed to $status");

        $this->setFlash('success', 'Status anggota berhasil diperbarui.');
        $this->redirect('/admin/members');
    }

    public function updateRole(string $id): void
    {
        $userId = (int) $id;
        $role = $_POST['role'] ?? 'anggota';

        if (!in_array($role, ['admin', 'pengurus', 'anggota'], true)) {
            $this->setFlash('error', 'Role pengguna tidak valid.');
            $this->redirect('/admin/members');
        }

        if ($userId === $this->userId()) {
            $this->setFlash('error', 'Demi keamanan, Anda tidak dapat mengubah role akun Anda sendiri.');
            $this->redirect('/admin/members');
        }

        $targetUser = $this->userModel->find($userId);
        if (!$targetUser) {
            $this->setFlash('error', 'Data anggota tidak ditemukan.');
            $this->redirect('/admin/members');
        }

        $this->userModel->updateRole($userId, $role);
        AuditLog::record(
            $this->userId(),
            'UPDATE_MEMBER_ROLE',
            'users',
            $userId,
            "Role {$targetUser['nama_lengkap']} diubah menjadi $role"
        );

        $this->setFlash('success', "Role untuk {$targetUser['nama_lengkap']} berhasil diubah menjadi " . ucfirst($role) . ".");
        $this->redirect('/admin/members');
    }


    public function delete(string $id): void
    {
        $userId = (int) $id;
        $this->userModel->delete($userId);
        AuditLog::record($this->userId(), 'DELETE_MEMBER', 'users', $userId, "Member deleted");

        $this->setFlash('success', 'Data anggota berhasil dihapus.');
        $this->redirect('/admin/members');
    }

    public function exportCsv(): void
    {
        $members = $this->userModel->getAllDecrypted('id ASC');

        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="Daftar_Anggota_Kmplang_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['No', 'Nama Lengkap', 'NIM', 'Email', 'Role', 'Status', 'Asal Daerah', 'Fakultas', 'WhatsApp', 'Tanggal Lahir', 'Motivasi', 'Tanggal Daftar']);

        $no = 1;
        foreach ($members as $m) {
            fputcsv($output, [
                $no++,
                $m['nama_lengkap'],
                $m['nim'] ?? '-',
                $m['email'],
                $m['role'],
                $m['status'],
                $m['asal_daerah'],
                $m['fakultas'] ?? '-',
                $m['whatsapp'] ?? '-',
                $m['tanggal_lahir'] ?? '-',
                $m['motivasi'] ?? '-',
                $m['created_at']
            ]);
        }

        fclose($output);
        exit;
    }
}
