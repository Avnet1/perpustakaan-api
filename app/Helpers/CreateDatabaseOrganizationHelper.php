<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Log;
use PDO;

class CreateDatabaseOrganizationHelper
{
    public static function handle(mixed $row): void
    {
        $masterHost = env('DB_HOST');
        $masterPort = env('DB_PORT');
        $masterUser = env('DB_USERNAME');
        $masterPass = env('DB_PASSWORD');
        $adminDb   =  'postgres'; // fallback to postgres if not set

        if (!$row->db_user || !$row->db_pass || !$row->db_name) {
            Log::error("❌ Database credential tidak lengkap: " . json_encode($row));
            return;
        }

        $dbName = sanitizeIdentifier($row->db_name);
        $dbUser = sanitizeIdentifier($row->db_user);
        $dbPass = addslashes($row->db_pass);

        try {
            $pdo = new PDO(
                "pgsql:host=$masterHost;port=$masterPort;dbname=$adminDb",
                $masterUser,
                $masterPass,
                [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
            );

            // Check if database already exists
            $stmt = $pdo->query("SELECT 1 FROM pg_database WHERE datname = '$dbName'");
            if ($stmt->fetch()) {
                Log::warning("⚠️ Database \"$dbName\" sudah ada. Batalkan proses pembuatan.");
                return;
            }

            // Check if user already exists
            $userExists = $pdo->query("SELECT 1 FROM pg_roles WHERE rolname = '$dbUser'");
            $userAlreadyExists = $userExists && $userExists->fetch();

            // Create database
            $pdo->exec("CREATE DATABASE \"$dbName\"");
            Log::info("✅ Database \"$dbName\" berhasil dibuat.");

            // Create user if not exists
            if (!$userAlreadyExists) {
                $pdo->exec("CREATE USER \"$dbUser\" WITH PASSWORD '$dbPass'");
                Log::info("✅ User \"$dbUser\" berhasil dibuat.");
            } else {
                Log::info("ℹ️ User \"$dbUser\" sudah ada, lewati proses pembuatan user.");
            }

            // Grant privileges
            $pdo->exec("GRANT ALL PRIVILEGES ON DATABASE \"$dbName\" TO \"$dbUser\"");
            Log::info("✅ Privilege diberikan ke user \"$dbUser\" untuk database \"$dbName\".");

            Log::info("🎉 Proses pembuatan database untuk organisasi \"{$row->nama_organisasi}\" selesai.");
        } catch (\Throwable $e) {
            Log::error("❌ Gagal membuat database untuk organisasi \"{$row->nama_organisasi}\": " . $e->getMessage());
        }
    }
}
