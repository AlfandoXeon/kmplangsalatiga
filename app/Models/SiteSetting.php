<?php

namespace App\Models;

use App\Core\Model;
use PDO;

class SiteSetting extends Model
{
    protected string $table = 'site_settings';
    private static ?array $cachedSettings = null;

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

    public function get(string $key, string $default = ''): string
    {
        $all = $this->getAllKeyValue();
        return $all[$key] ?? $default;
    }

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

        self::$cachedSettings = null; // Clear cache
        return $res;
    }
}
