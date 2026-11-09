<?php

namespace App\Core;

use App\Config\Database;
use InvalidArgumentException;
use PDO;

/**
 * ============================================================================
 * Class Model - Base Data Access Layer (Active Record Ringan Berbasis PDO)
 * ============================================================================
 * Menyediakan operasi CRUD standar untuk seluruh Model turunan dengan:
 * 1. Parameter Binding murni (100% bebas SQL Injection pada nilai data).
 * 2. Sanitasi ketat pada nama kolom/identifier SQL (`sanitizeColumn`, `sanitizeOrderBy`).
 * 3. Dukungan transaksi database terintegrasi (`transaction()`).
 * ============================================================================
 */
class Model
{
    /** @var PDO Koneksi database aktif */
    protected PDO $db;

    /** @var string Nama tabel yang direpresentasikan oleh Model */
    protected string $table = '';

    public function __construct()
    {
        $this->db = Database::getConnection();
    }

    /**
     * Memvalidasi nama kolom SQL agar hanya berisi karakter alfanumerik dan underscore.
     * Mencegah SQL Injection melalui manipulasi key array pada operasi CRUD dinamis.
     */
    protected function sanitizeColumn(string $column): string
    {
        if (!preg_match('/^[a-zA-Z_][a-zA-Z0-9_]*$/', $column)) {
            throw new InvalidArgumentException("Nama kolom SQL tidak valid: {$column}");
        }
        return $column;
    }

    /**
     * Memvalidasi klausa ORDER BY agar aman dari injeksi SQL.
     * Contoh input valid: "id DESC", "divisi ASC, id ASC".
     */
    protected function sanitizeOrderBy(string $orderBy): string
    {
        $parts = explode(',', $orderBy);
        $cleanParts = [];

        foreach ($parts as $part) {
            $part = trim($part);
            if (preg_match('/^([a-zA-Z_][a-zA-Z0-9_.]*)(?:\s+(ASC|DESC))?$/i', $part, $matches)) {
                $col = $matches[1];
                $dir = strtoupper($matches[2] ?? 'ASC');
                $cleanParts[] = "{$col} {$dir}";
            }
        }

        return !empty($cleanParts) ? implode(', ', $cleanParts) : 'id DESC';
    }

    /**
     * Mengambil seluruh baris dari tabel dengan urutan tertentu.
     */
    public function all(string $orderBy = 'id DESC'): array
    {
        $safeOrder = $this->sanitizeOrderBy($orderBy);
        $stmt = $this->db->query("SELECT * FROM `{$this->table}` ORDER BY {$safeOrder}");
        return $stmt->fetchAll();
    }

    /**
     * Mencari satu baris berdasarkan Primary Key `id`.
     */
    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Memeriksa apakah baris dengan ID tertentu ada di dalam tabel.
     */
    public function exists(int $id): bool
    {
        $stmt = $this->db->prepare("SELECT 1 FROM `{$this->table}` WHERE id = :id LIMIT 1");
        $stmt->execute(['id' => $id]);
        return (bool) $stmt->fetchColumn();
    }

    /**
     * Mencari satu baris pertama berdasarkan kecocokan nilai pada suatu kolom.
     */
    public function findBy(string $column, mixed $value): ?array
    {
        $col = $this->sanitizeColumn($column);
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `{$col}` = :val LIMIT 1");
        $stmt->execute(['val' => $value]);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Mengambil daftar baris yang cocok dengan kondisi suatu kolom.
     */
    public function where(string $column, mixed $value, string $orderBy = 'id DESC'): array
    {
        $col = $this->sanitizeColumn($column);
        $safeOrder = $this->sanitizeOrderBy($orderBy);
        $stmt = $this->db->prepare("SELECT * FROM `{$this->table}` WHERE `{$col}` = :val ORDER BY {$safeOrder}");
        $stmt->execute(['val' => $value]);
        return $stmt->fetchAll();
    }

    /**
     * Menyisipkan baris baru ke tabel dan mengembalikan ID baris yang baru dibuat.
     */
    public function create(array $data): int
    {
        $columns = array_map([$this, 'sanitizeColumn'], array_keys($data));
        $fields = '`' . implode('`, `', $columns) . '`';
        $placeholders = ':' . implode(', :', $columns);

        $sql = "INSERT INTO `{$this->table}` ($fields) VALUES ($placeholders)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);

        return (int) $this->db->lastInsertId();
    }

    /**
     * Memperbarui kolom-kolom pada baris dengan ID tertentu.
     */
    public function update(int $id, array $data): bool
    {
        if (empty($data)) {
            return false;
        }

        $fields = [];
        foreach (array_keys($data) as $col) {
            $safeCol = $this->sanitizeColumn((string) $col);
            $fields[] = "`{$safeCol}` = :{$safeCol}";
        }
        $setClause = implode(', ', $fields);

        $sql = "UPDATE `{$this->table}` SET {$setClause} WHERE id = :id";
        $data['id'] = $id;

        $stmt = $this->db->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Menghapus satu baris berdasarkan ID.
     */
    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM `{$this->table}` WHERE id = :id");
        return $stmt->execute(['id' => $id]);
    }

    /**
     * Menghitung total seluruh baris di dalam tabel.
     */
    public function count(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM `{$this->table}`");
        return (int) $stmt->fetchColumn();
    }
}
