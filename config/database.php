<?php
require_once __DIR__ . '/app.php';

function db(): PDO {
    static $pdo = null;
    
    if ($pdo === null) {
        $dsn = sprintf(
            'pgsql:host=%s;port=%s;dbname=%s',
            DB_HOST, DB_PORT, DB_NAME
        );
        
        try {
            $pdo = new PDO($dsn, DB_USER, DB_PASS, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            if (IS_DEV) {
                die(" Koneksi PostgreSQL Gagal: " . $e->getMessage());
            }
            error_log("DB Error: " . $e->getMessage());
            die("Terjadi gangguan pada server.");
        }
    }
    
    return $pdo;
}   