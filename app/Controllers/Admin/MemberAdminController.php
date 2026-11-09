<?php

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Config\Database;
use App\Models\User;
use App\Models\AuditLog;
use App\Helpers\Encryption;

/**
 * ============================================================================
 * Class MemberAdminController - Verifikasi, Otorisasi Role & Ekspor CSV Anggota
 * ============================================================================
 * Mengelola persetujuan anggota baru, promosi/demosi role & jabatan organisasi,
 * dekripsi nomor WhatsApp untuk kontak cepat pengurus, serta ekspor CSV.
 * ============================================================================
 */
class MemberAdminController extends Controller
{
    private User $userModel;

    public function __construct()
    {
        $this->userModel = new User();
    }

    public function index(): void
    {
        $statusFilter = trim($_GET['status'] ?? '');
        $search = trim($_GET['q'] ?? '');
        $db = Database::getConnection();

        $sql = "SELECT * FROM users WHERE 1=1";
        $params = [];

        if ($statusFilter !== '' && in_array($statusFilter, ['pending', 'active', 'rejected'], true)) {
            $sql .= " AND status = :st";
            $params['st'] = $statusFilter;
        }

        if ($search !== '') {
            $sql .= " AND (nama_lengkap LIKE :q1 OR nim LIKE :q2 OR email LIKE :q3 OR asal_daerah LIKE :q4 OR fakultas LIKE :q5)";
            $like = "%{$search}%";
            $params['q1'] = $like;
            $params['q2'] = $like;
            $params['q3'] = $like;
            $params['q4'] = $like;
            $params['q5'] = $like;
        }

        $sql .= " ORDER BY CASE WHEN status = 'pending' THEN 0 ELSE 1 END, id DESC";

        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        $members = $stmt->fetchAll();

        foreach ($members as &$m) {
            $m['whatsapp'] = !empty($m['no_whatsapp_encrypted'])
                ? Encryption::decrypt($m['no_whatsapp_encrypted'])
                : '-';
        }

        $this->view('admin/members', [
            'title'        => 'Manajemen & Verifikasi Anggota — K\'mplang Salatiga',
            'members'      => $members,
            'statusFilter' => $statusFilter,
            'search'       => $search
        ], 'admin');
    }

    public function updateStatus(string $id): void
    {
        $userId = (int) $id;
        $status = $_POST['status'] ?? 'active';

        if (!in_array($status, ['active', 'pending', 'rejected'], true)) {
            $this->setFlash('error', 'Status keanggotaan tidak valid.');
            $this->redirect('/admin/members');
        }

        $this->userModel->updateStatus($userId, $status);
        AuditLog::record($this->userId(), 'UPDATE_MEMBER_STATUS', 'users', $userId, "Status changed to {$status}");

        $this->setFlash('success', 'Status verifikasi anggota berhasil diperbarui.');
        $this->redirect('/admin/members');
    }

    public function updateRole(string $id): void
    {
        $userId = (int) $id;
        $role = $_POST['role'] ?? 'anggota';
        $jabatan = trim($_POST['jabatan_organisasi'] ?? '');

        if (!in_array($role, ['admin', 'pengurus', 'anggota'], true)) {
            $this->setFlash('error', 'Role pengguna tidak valid.');
            $this->redirect('/admin/members');
        }

        if ($userId === $this->userId() && $role !== ($_SESSION['user']['role'] ?? 'admin')) {
            $this->setFlash('error', 'Demi keamanan sistem, Anda tidak dapat menurunkan role akun Anda sendiri yang sedang aktif.');
            $this->redirect('/admin/members');
        }

        $targetUser = $this->userModel->find($userId);
        if (!$targetUser) {
            $this->setFlash('error', 'Data anggota tidak ditemukan.');
            $this->redirect('/admin/members');
        }

        $payload = ['role' => $role];
        if ($jabatan !== '') {
            $payload['jabatan_organisasi'] = $jabatan;
        } else {
            $payload['jabatan_organisasi'] = match ($role) {
                'admin'    => 'Administrator',
                'pengurus' => 'Pengurus K\'mplang',
                default    => 'Anggota'
            };
        }

        $this->userModel->update($userId, $payload);

        AuditLog::record(
            $this->userId(),
            'UPDATE_MEMBER_ROLE',
            'users',
            $userId,
            "Role {$targetUser['nama_lengkap']} diubah menjadi {$role} ({$payload['jabatan_organisasi']})"
        );

        $this->setFlash('success', "Peran dan jabatan untuk {$targetUser['nama_lengkap']} berhasil diperbarui.");
        $this->redirect('/admin/members');
    }

    public function delete(string $id): void
    {
        $userId = (int) $id;
        if ($userId === $this->userId()) {
            $this->setFlash('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
            $this->redirect('/admin/members');
        }

        $this->userModel->delete($userId);
        AuditLog::record($this->userId(), 'DELETE_MEMBER', 'users', $userId, "Member #{$userId} deleted");

        $this->setFlash('success', 'Data anggota berhasil dihapus dari sistem.');
        $this->redirect('/admin/members');
    }

    /**
     * Mengekspor seluruh direktori anggota ke dalam berkas CSV (kompatibel Microsoft Excel).
     */
    public function exportCsv(): void
    {
        $members = $this->userModel->getAllDecrypted('id ASC');

        if (ob_get_level()) {
            ob_end_clean();
        }

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="Direktori_Anggota_Kmplang_' . date('Y-m-d') . '.csv"');

        $output = fopen('php://output', 'w');
        // Tambahkan UTF-8 BOM agar karakter bahasa Indonesia terbaca rapi di Microsoft Excel
        fprintf($output, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($output, [
            'No', 'Nama Lengkap', 'NIM', 'Email', 'Role', 'Jabatan Organisasi',
            'Status', 'Asal Daerah', 'Fakultas', 'Angkatan', 'WhatsApp',
            'Tanggal Lahir', 'Motivasi', 'Tanggal Daftar'
        ]);

        $no = 1;
        foreach ($members as $m) {
            fputcsv($output, [
                $no++,
                $m['nama_lengkap'],
                $m['nim'] ?? '-',
                $m['email'],
                $m['role'],
                $m['jabatan_organisasi'] ?? 'Anggota',
                $m['status'],
                $m['asal_daerah'],
                $m['fakultas'] ?? '-',
                $m['angkatan'] ?? '-',
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
