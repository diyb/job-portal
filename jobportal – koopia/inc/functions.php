<?php
// Ühised abifunktsioonid

if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params(['httponly' => true, 'samesite' => 'Lax']);
    session_start();
}

define('ROOT_PATH', dirname(__DIR__));
define('UPLOAD_DIR', ROOT_PATH . '/uploads');
const CV_MAX_SIZE = 3 * 1024 * 1024;
const LOGO_MAX_SIZE = 1024 * 1024;

/** XSS kaitse: väljundi escape */
function e($v): string
{
    return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8');
}

/** Rakenduse baas-URL ('' või '/jobportal') – admin kasutab ../ */
function base_index(): string
{
    return defined('IS_ADMIN_APP') ? 'index.php' : 'index.php';
}

function url(string $page = 'start', array $params = []): string
{
    $q = ['page' => $page] + $params;
    return base_index() . '?' . http_build_query($q);
}

function front_url(string $page = 'start', array $params = []): string
{
    $prefix = defined('IS_ADMIN_APP') ? '../' : '';
    return $prefix . 'index.php?' . http_build_query(['page' => $page] + $params);
}

function asset(string $path): string
{
    $prefix = defined('IS_ADMIN_APP') ? '../' : '';
    return $prefix . 'public/' . ltrim($path, '/');
}

function redirect(string $to): never
{
    header('Location: ' . $to);
    exit;
}

function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/* ---------- Flash ---------- */
function flash(string $type, string $msg): void
{
    $_SESSION['flash'][] = ['type' => $type, 'msg' => $msg];
}

function flashes(): array
{
    $f = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $f;
}

/* ---------- CSRF ---------- */
function csrf_token(): string
{
    if (empty($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function csrf_field(): string
{
    return '<input type="hidden" name="csrf" value="' . e(csrf_token()) . '">';
}

function csrf_check(): void
{
    $ok = isset($_POST['csrf']) && hash_equals($_SESSION['csrf'] ?? '', (string)$_POST['csrf']);
    if (!$ok) {
        http_response_code(419);
        exit('Turvakontroll ebaõnnestus (CSRF). Lae leht uuesti ja proovi veel kord.');
    }
}

/* ---------- Autentimine ---------- */
function current_user(): ?array
{
    static $user = false;
    if ($user === false) {
        $user = null;
        if (!empty($_SESSION['user_id'])) {
            $st = db()->prepare('SELECT * FROM users WHERE id = ?');
            $st->execute([$_SESSION['user_id']]);
            $u = $st->fetch();
            if ($u && $u['status'] === 'active') {
                $user = $u;
            } else {
                unset($_SESSION['user_id']);
            }
        }
    }
    return $user;
}

function login_user(array $u): void
{
    session_regenerate_id(true);
    $_SESSION['user_id'] = (int)$u['id'];
}

function logout_user(): void
{
    $_SESSION = [];
    session_destroy();
}

function require_login(): array
{
    $u = current_user();
    if (!$u) {
        flash('warning', 'Palun logi enne sisse.');
        redirect(front_url('login'));
    }
    return $u;
}

/** Roli kontroll – tööotsija ei pääse tööandja ega admini vaadetesse */
function require_role(string $role): array
{
    $u = require_login();
    if ($u['role'] !== $role) {
        http_response_code(403);
        exit('Sul puudub õigus sellele lehele ligi pääseda.');
    }
    return $u;
}

/* ---------- Seaded ---------- */
function setting(string $key, string $default = ''): string
{
    static $cache = null;
    if ($cache === null) {
        $cache = [];
        try {
            foreach (db()->query('SELECT k, v FROM settings') as $r) {
                $cache[$r['k']] = $r['v'];
            }
        } catch (PDOException $e) {
        }
    }
    return $cache[$key] ?? $default;
}

/* ---------- Vaated ---------- */
function render(string $view, array $data = [], string $layout = 'layout'): void
{
    $viewDir = defined('IS_ADMIN_APP') ? __DIR__ . '/../jobportal_admin/viewAdmin' : __DIR__ . '/../view';
    extract($data, EXTR_SKIP);
    $cu = current_user();
    ob_start();
    require $viewDir . '/' . $view . '.php';
    $content = ob_get_clean();
    require $viewDir . '/' . $layout . '.php';
}

/* ---------- Muu ---------- */
function money_range($min, $max): string
{
    if (!$min && !$max) {
        return 'Kokkuleppel';
    }
    $f = fn($n) => number_format((int)$n, 0, ',', ' ');
    if ($min && $max) {
        return $f($min) . ' – ' . $f($max) . ' €';
    }
    return ($min ? 'alates ' . $f($min) : 'kuni ' . $f($max)) . ' €';
}

function fmt_date(?string $d): string
{
    return $d ? date('d.m.Y', strtotime($d)) : '';
}

function initials(string $name): string
{
    $out = '';
    foreach (preg_split('/\s+/', trim($name)) as $p) {
        $out .= mb_strtoupper(mb_substr($p, 0, 1));
        if (mb_strlen($out) >= 2) {
            break;
        }
    }
    return $out;
}

function status_badge(string $s): string
{
    $map = ['Uus' => 'primary', 'Ülevaatamisel' => 'warning', 'Sobiv' => 'success', 'Tagasi lükatud' => 'danger'];
    return '<span class="badge text-bg-' . ($map[$s] ?? 'secondary') . '">' . e($s) . '</span>';
}

function notify(int $userId, string $text, ?string $link = null): void
{
    db()->prepare('INSERT INTO notifications (user_id, text, link) VALUES (?,?,?)')->execute([$userId, $text, $link]);
}

function unread_counts(int $userId): array
{
    $n = db()->prepare('SELECT COUNT(*) FROM notifications WHERE user_id=? AND is_read=0');
    $n->execute([$userId]);
    $m = db()->prepare('SELECT COUNT(*) FROM messages WHERE receiver_id=? AND is_read=0');
    $m->execute([$userId]);
    return ['notifications' => (int)$n->fetchColumn(), 'messages' => (int)$m->fetchColumn()];
}

/**
 * Turvaline failide üleslaadimine.
 * $allowed: laiend => lubatud MIME-tüüpide massiiv. Fail salvestatakse juhusliku nimega.
 * Tagastab [stored_name, original_name, size] või viskab RuntimeException.
 */
function save_upload(array $file, string $subdir, array $allowed, int $maxSize, ?string $base = null): array
{
    $base = $base ?? UPLOAD_DIR;
    if (!isset($file['error']) || $file['error'] !== UPLOAD_ERR_OK) {
        throw new RuntimeException('Faili üleslaadimine ebaõnnestus.');
    }
    if ($file['size'] > $maxSize) {
        throw new RuntimeException('Fail on liiga suur (max ' . round($maxSize / 1048576, 1) . ' MB).');
    }
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!isset($allowed[$ext])) {
        throw new RuntimeException('Lubatud failitüübid: ' . implode(', ', array_keys($allowed)) . '.');
    }
    $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
    if (!in_array($mime, $allowed[$ext], true)) {
        throw new RuntimeException('Faili sisu ei vasta laiendile.');
    }
    $dir = $base . '/' . $subdir;
    if (!is_dir($dir) && !mkdir($dir, 0755, true)) {
        throw new RuntimeException('Üleslaadimise kausta ei saa luua.');
    }
    $stored = bin2hex(random_bytes(16)) . '.' . $ext;
    if (!move_uploaded_file($file['tmp_name'], $dir . '/' . $stored)) {
        throw new RuntimeException('Faili salvestamine ebaõnnestus.');
    }
    return [$stored, mb_substr(basename($file['name']), 0, 200), (int)$file['size']];
}

const CV_TYPES = [
    'pdf'  => ['application/pdf'],
    'doc'  => ['application/msword', 'application/x-ole-storage', 'application/octet-stream'],
    'docx' => ['application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/zip'],
];
const LOGO_TYPES = [
    'png'  => ['image/png'],
    'jpg'  => ['image/jpeg'],
    'jpeg' => ['image/jpeg'],
    'webp' => ['image/webp'],
];

/** Ettevõtte logo või initsiaalid */
function logo_html(array $c, int $size = 56): string
{
    $s = 'width:' . $size . 'px;height:' . $size . 'px;';
    if (!empty($c['logo'])) {
        return '<img class="company-logo" style="' . $s . '" src="' . e(asset('logos/' . $c['logo'])) . '" alt="' . e($c['name']) . '">';
    }
    return '<span class="company-logo company-logo-fallback" style="' . $s . 'font-size:' . round($size / 2.6) . 'px">' . e(initials($c['name'])) . '</span>';
}

function paginate(int $total, int $perPage, int $page): array
{
    $pages = max(1, (int)ceil($total / $perPage));
    $page = min(max(1, $page), $pages);
    return ['page' => $page, 'pages' => $pages, 'offset' => ($page - 1) * $perPage, 'per' => $perPage];
}
