<?php

namespace App\Models;

use App\Core\Model;
use App\Helpers\Encryption;
use PDO;

class User extends Model
{
    protected string $table = 'users';

    public function findByEmailOrNim(string $identifier): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = :id1 OR nim = :id2 LIMIT 1");
        $stmt->execute(['id1' => $identifier, 'id2' => $identifier]);
        $user = $stmt->fetch();
        return $user ?: null;
    }

    public function register(array $data): int
    {
        $data['password_hash'] = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 12]);
        unset($data['password']);

        if (!empty($data['whatsapp'])) {
            $data['no_whatsapp_encrypted'] = Encryption::encrypt($data['whatsapp']);
        }
        unset($data['whatsapp']);

        $data['role'] = 'anggota';
        $data['status'] = 'pending'; // Menunggu persetujuan admin

        return $this->create($data);
    }

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

    public function updateStatus(int $id, string $status): bool
    {
        return $this->update($id, ['status' => $status]);
    }

    public function updateRole(int $id, string $role): bool
    {
        return $this->update($id, ['role' => $role]);
    }
}

