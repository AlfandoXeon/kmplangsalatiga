<?php

namespace App\Models;

use App\Core\Model;
use App\Helpers\Encryption;
use PDO;

/**
 * ============================================================================
 * Model User - Manajemen Data Anggota, Pengurus, dan Administrator
 * ============================================================================
 * Menangani:
 * 1. Pencarian akun via Email atau NIM (`findByEmailOrNim`).
 * 2. Registrasi anggota baru dengan hashing Bcrypt (cost 12) & enkripsi AES-256
 *    pada nomor WhatsApp (`register`).
 * 3. Dekripsi nomor WhatsApp secara aman untuk halaman profil & panel admin.
 * 4. Pencarian & pemfilteran direktori anggota aktif (`getActiveDirectory`).
 * ============================================================================
 */
class User extends Model
{
    protected string $table = 'users';

    /**
     * Mencari pengguna berdasarkan Email atau NIM untuk keperluan Login.
     */
    public function findByEmailOrNim(string $identifier): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :id1 OR nim = :id2 LIMIT 1");
        $stmt->execute(['id1' => $identifier, 'id2' => $identifier]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    /**
     * Mendaftarkan anggota baru ke dalam database.
     * Password di-hash menggunakan Bcrypt, dan nomor WhatsApp dienkripsi AES-256-CBC.
     */
    public function register(array $data): int
    {
        $payload = [
            'nama_lengkap'          => $data['nama_lengkap'],
            'nim'                   => !empty($data['nim']) ? $data['nim'] : null,
            'email'                 => $data['email'],
            'password_hash'         => password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]),
            'role'                  => 'anggota',
            'jabatan_organisasi'    => $data['jabatan_organisasi'] ?? 'Anggota',
            'asal_daerah'           => $data['asal_daerah'],
            'fakultas'              => !empty($data['fakultas']) ? $data['fakultas'] : null,
            'angkatan'              => !empty($data['angkatan']) ? $data['angkatan'] : null,
            'no_whatsapp_encrypted' => !empty($data['whatsapp']) ? Encryption::encrypt($data['whatsapp']) : null,
            'tanggal_lahir'         => !empty($data['tanggal_lahir']) ? $data['tanggal_lahir'] : null,
            'motivasi'              => !empty($data['motivasi']) ? $data['motivasi'] : null,
            'status'                => 'pending' // Menunggu verifikasi pengurus/admin
        ];

        return $this->create($payload);
    }

    /**
     * Mencatat stempel waktu login terakhir pengguna.
     */
    public function recordLastLogin(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE users SET last_login_at = NOW() WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }

    /**
     * Mengambil data lengkap satu pengguna beserta nomor WhatsApp yang telah didekripsi.
     */
    public function findDecrypted(int $id): ?array
    {
        $user = $this->find($id);
        if (!$user) {
            return null;
        }

        $user['whatsapp'] = !empty($user['no_whatsapp_encrypted'])
            ? Encryption::decrypt($user['no_whatsapp_encrypted'])
            : '';

        return $user;
    }

    /**
     * Mengambil seluruh daftar pengguna dengan nomor WhatsApp terdekripsi (khusus Admin).
     */
    public function getAllDecrypted(string $orderBy = 'id DESC'): array
    {
        $users = $this->all($orderBy);
        foreach ($users as &$user) {
            $user['whatsapp'] = !empty($user['no_whatsapp_encrypted'])
                ? Encryption::decrypt($user['no_whatsapp_encrypted'])
                : '-';
        }
        return $users;
    }

    /**
     * Mengambil daftar anggota berstatus 'active' untuk Direktori Publik
     * dengan dukungan pencarian kata kunci dan filter angkatan.
     */
    public function getActiveDirectory(string $search = '', string $angkatan = ''): array
    {
        $sql = "
            SELECT id, nama_lengkap, nim, fakultas, angkatan, asal_daerah, role, jabatan_organisasi, status, created_at
            FROM users
            WHERE status = 'active'
        ";
        $params = [];

        if ($search !== '') {
            $sql .= " AND (
                nama_lengkap LIKE :q1 OR
                nim LIKE :q2 OR
                fakultas LIKE :q3 OR
                asal_daerah LIKE :q4 OR
                jabatan_organisasi LIKE :q5
            )";
            $like = "%{$search}%";
            $params['q1'] = $like;
            $params['q2'] = $like;
            $params['q3'] = $like;
            $params['q4'] = $like;
            $params['q5'] = $like;
        }

        if ($angkatan !== '') {
            $sql .= " AND angkatan = :angkatan";
            $params['angkatan'] = $angkatan;
        }

        $sql .= " ORDER BY CASE WHEN role = 'admin' THEN 0 WHEN role = 'pengurus' THEN 1 ELSE 2 END, nama_lengkap ASC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Mengambil daftar tahun angkatan unik dari anggota aktif.
     */
    public function getDistinctAngkatan(): array
    {
        $stmt = $this->db->query("
            SELECT DISTINCT angkatan
            FROM users
            WHERE status = 'active' AND angkatan IS NOT NULL AND angkatan != ''
            ORDER BY angkatan DESC
        ");
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    /**
     * Memperbarui status verifikasi anggota ('active', 'pending', 'rejected').
     */
    public function updateStatus(int $id, string $status): bool
    {
        return $this->update($id, ['status' => $status]);
    }

    /**
     * Memperbarui role akses pengguna ('admin', 'pengurus', 'anggota').
     */
    public function updateRole(int $id, string $role): bool
    {
        return $this->update($id, ['role' => $role]);
    }
}
