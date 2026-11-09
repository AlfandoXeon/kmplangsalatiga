<?php

namespace App\Config;

use PDO;
use PDOException;
use Throwable;

/**
 * ============================================================================
 * Class Database - Pengelola Koneksi PDO MySQL / MariaDB (Singleton & Tangguh)
 * ============================================================================
 * Fitur Keandalan:
 * 1. Singleton Connection Pooling untuk menghindari duplikasi koneksi per request.
 * 2. Host Fallback otomatis (`127.0.0.1` <-> `localhost`) apabila salah satu
 *    jalur socket TCP/IPv6 menolak koneksi pada lingkungan lokal (XAMPP).
 * 3. Health-check (`ping`) otomatis dan auto-reconnect bila koneksi terputus.
 * 4. Wrapper transaksi atomik (`transaction()`) dengan rollback otomatis saat gagal.
 * ============================================================================
 */
class Database
{
    /** @var ?PDO Instance koneksi tunggal selama siklus hidup request */
    private static ?PDO $connection = null;

    /**
     * Mengembalikan koneksi PDO aktif. Jika belum ada atau terputus,
     * akan menginisialisasi koneksi baru secara otomatis.
     */
    public static function getConnection(): PDO
    {
        if (self::$connection === null || !self::isConnectionAlive()) {
            self::$connection = self::connect();
        }

        return self::$connection;
    }

    /**
     * Membuka koneksi PDO ke MySQL dengan opsi keamanan ketat
     * (`ATTR_EMULATE_PREPARES = false` untuk prepared statement murni di sisi server)
     * serta mekanisme fallback host.
     */
    private static function connect(): PDO
    {
        $configuredHost = (string) App::get('DB_HOST', '127.0.0.1');
        $port = (string) App::get('DB_PORT', '3306');
        $dbname = (string) App::get('DB_NAME', 'db_kmplang');
        $user = (string) App::get('DB_USER', 'root');
        $pass = (string) App::get('DB_PASS', '');

        // Daftar kandidat host untuk menjaga ketahanan di Windows/XAMPP (IPv4 vs localhost)
        $hostsToTry = array_unique([$configuredHost, 'localhost', '127.0.0.1']);

        $options = [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES   => false,
            PDO::ATTR_STRINGIFY_FETCHES  => false,
            PDO::ATTR_TIMEOUT            => 5,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4 COLLATE utf8mb4_unicode_ci, SESSION sql_mode = 'STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION'"
        ];

        $lastException = null;

        // Coba hingga 2 putaran dengan jeda 150ms untuk mengatasi lonjakan koneksi sesaat di Cloud Hosting (InfinityFree)
        for ($attempt = 1; $attempt <= 2; $attempt++) {
            foreach ($hostsToTry as $host) {
                $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";
                try {
                    return new PDO($dsn, $user, $pass, $options);
                } catch (PDOException $e) {
                    $lastException = $e;
                }
            }
            if ($attempt < 2) {
                usleep(150000);
            }
        }

        $errMsg = $lastException ? $lastException->getMessage() : 'Unknown PDO Connection Error';
        Security::logError('DATABASE_CONNECTION_FAILED', $errMsg, __FILE__, __LINE__);

        throw new \RuntimeException('Koneksi basis data sedang sibuk: ' . $errMsg);
    }

    /**
     * Memeriksa apakah koneksi PDO yang tersimpan masih aktif dan responsif.
     */
    private static function isConnectionAlive(): bool
    {
        if (self::$connection === null) {
            return false;
        }

        try {
            self::$connection->query('SELECT 1');
            return true;
        } catch (Throwable) {
            return false;
        }
    }

    /**
     * Menjalankan serangkaian operasi database di dalam satu transaksi atomik.
     * Jika terjadi exception di tengah eksekusi, seluruh perubahan di-rollback otomatis.
     *
     * @template T
     * @param callable(PDO): T $callback
     * @return T
     * @throws Throwable
     */
    public static function transaction(callable $callback): mixed
    {
        $pdo = self::getConnection();
        $startedHere = false;

        if (!$pdo->inTransaction()) {
            $pdo->beginTransaction();
            $startedHere = true;
        }

        try {
            $result = $callback($pdo);
            if ($startedHere && $pdo->inTransaction()) {
                $pdo->commit();
            }
            return $result;
        } catch (Throwable $e) {
            if ($startedHere && $pdo->inTransaction()) {
                $pdo->rollBack();
            }
            throw $e;
        }
    }
}
