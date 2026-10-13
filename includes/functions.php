<?php
require_once __DIR__ . '/../config/database.php';

// CSRF
function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    return $_SESSION['csrf_token'];
}
function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . csrf_token() . '">';
}
function verify_csrf(): void {
    if (!hash_equals($_SESSION['csrf_token'] ?? '', $_POST['csrf_token'] ?? '')) {
        http_response_code(419);
        die('CSRF token tidak valid.');
    }
}

// FLASH
function set_flash(string $type, string $msg): void { $_SESSION['flash'][$type] = $msg; }
function get_flash(string $type): ?string {
    if (isset($_SESSION['flash'][$type])) {
        $msg = $_SESSION['flash'][$type];
        unset($_SESSION['flash'][$type]);
        return $msg;
    }
    return null;
}

// ESCAPE
function e($str): string { return htmlspecialchars($str ?? '', ENT_QUOTES, 'UTF-8'); }

// REDIRECT
function redirect(string $path): void {
    $url = str_starts_with($path, 'http') ? $path : APP_URL . '/' . ltrim($path, '/');
    header("Location: $url");
    exit;
}

// TOKEN
function generate_token(int $bytes = 32): string { return bin2hex(random_bytes($bytes)); }

// AUTH
function is_logged_in(): bool { return isset($_SESSION['user_id']); }
function current_user(): ?array {
    if (!is_logged_in()) return null;
    static $user = null;
    if ($user === null) {
        $stmt = db()->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$_SESSION['user_id']]);
        $user = $stmt->fetch() ?: null;
    }
    return $user;
}
function current_role(): ?string { return $_SESSION['role'] ?? null; }

// LOG
function log_activity(?int $userId, string $action, string $desc = ''): void {
    try {
        db()->prepare("INSERT INTO activity_log (user_id, action, description, ip_address, user_agent) VALUES (?, ?, ?, ?, ?)")
            ->execute([$userId, $action, $desc, $_SERVER['REMOTE_ADDR'] ?? null, $_SERVER['HTTP_USER_AGENT'] ?? null]);
    } catch (Exception $e) { error_log("Log error: " . $e->getMessage()); }
}

// INPUT
function post(string $key, $default = null) { return $_POST[$key] ?? $default; }
function get(string $key, $default = null) { return $_GET[$key] ?? $default; }
function old(string $key, $default = '') { return $_SESSION['old'][$key] ?? $default; }
function set_old(array $data): void { $_SESSION['old'] = $data; }
function clear_old(): void { unset($_SESSION['old']); }