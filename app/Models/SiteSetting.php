<?php

namespace App\Models;

use App\Core\Model;
use PDO;

/**
 * ============================================================================
 * Model SiteSetting - Penyimpanan Key-Value CMS Dinamis dengan In-Memory Cache
 * ============================================================================
 * Menyimpan teks konten website (hero, tentang, sambutan, kontak) dan
 * melakukan caching di memori statis agar tidak mengulang query SQL per request.
 * ============================================================================
 */
class SiteSetting extends Model
{
    protected string $table = 'site_settings';

    /** @var ?array<string, string> Cache pengaturan selama 1 siklus request */
    private static ?array $cachedSettings = null;

    /**
     * Mengambil seluruh pasangan `setting_key => setting_value`.
     */
    public function getAllKeyValue(): array
    {
        if (self::$cachedSettings !== null) {
            return self::$cachedSettings;
        }

        $stmt = $this->db->query("SELECT setting_key, setting_value FROM site_settings");
        $rows = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
        self::$cachedSettings = $rows ?: [];
        return self::$cachedSettings;
    }

    /**
     * Mengambil satu nilai pengaturan berdasarkan kunci, atau nilai default jika belum diatur.
     */
    public function get(string $key, string $default = ''): string
    {
        $all = $this->getAllKeyValue();
        return $all[$key] ?? $default;
    }

    /**
     * Menyimpan atau memperbarui satu pengaturan (UPSERT via ON DUPLICATE KEY UPDATE)
     * dan mereset cache in-memory.
     */
    public function set(string $key, ?string $value, string $group = 'general'): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO site_settings (setting_key, setting_value, group_name)
            VALUES (:key, :val, :grp)
            ON DUPLICATE KEY UPDATE setting_value = :val2, group_name = :grp2
        ");
        $res = $stmt->execute([
            'key'  => $key,
            'val'  => $value,
            'grp'  => $group,
            'val2' => $value,
            'grp2' => $group
        ]);

        self::$cachedSettings = null;
        return $res;
    }
}
