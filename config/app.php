<?php
if (basename($_SERVER['SCRIPT_FILENAME']) === 'app.php') {
    die('Akses langsung tidak diizinkan.');
}

function load_env($path) {
    if (!file_exists($path)) die("File .env tidak ditemukan: $path");
    $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        if (strpos($line, '=') === false) continue;
        [$key, $value] = explode('=', $line, 2);
        $key = trim($key);
        $value = trim(trim($value), '"\'');
        if (!array_key_exists($key, $_ENV)) {
            $_ENV[$key] = $value;
            putenv("$key=$value");
        }
    }
}
load_env(__DIR__ . '/../.env');

define('APP_NAME',     $_ENV['APP_NAME']     ?? 'KosinAja');
define('APP_URL',      rtrim($_ENV['APP_URL'] ?? '', '/'));
define('APP_ENV',      $_ENV['APP_ENV']      ?? 'production');
define('APP_TIMEZONE', $_ENV['APP_TIMEZONE'] ?? 'Asia/Jakarta');
define('DB_HOST',    $_ENV['DB_HOST']    ?? 'localhost');
define('DB_NAME',    $_ENV['DB_NAME']    ?? 'db_kosinaja');
define('DB_PORT',    $_ENV['DB_PORT']    ?? '5432');
define('DB_USER',    $_ENV['DB_USER']    ?? 'postgres');
define('DB_PASS',    $_ENV['DB_PASS']    ?? 'postgres');
define('DB_CHARSET', $_ENV['DB_CHARSET'] ?? 'utf8mb4');
define('IS_DEV', APP_ENV === 'development');

date_default_timezone_set(APP_TIMEZONE);

if (session_status() === PHP_SESSION_NONE) {
    $isHttps = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || ($_SERVER['SERVER_PORT'] ?? 80) == 443;
    session_set_cookie_params([
        'lifetime' => 0, 'path' => '/',
        'secure' => $isHttps, 'httponly' => true, 'samesite' => 'Lax',
    ]);
    session_start();
}

if (!isset($_SESSION['last_regenerate'])) {
    $_SESSION['last_regenerate'] = time();
} elseif (time() - $_SESSION['last_regenerate'] > 1800) {
    session_regenerate_id(true);
    $_SESSION['last_regenerate'] = time();
}

if (IS_DEV) {
    error_reporting(E_ALL);
    ini_set('display_errors', 1);
} else {
    error_reporting(0);
    ini_set('display_errors', 0);
    ini_set('log_errors', 1);
}