<?php
// ===== Баптаулар =====
define('DB_HOST', 'localhost');
define('DB_NAME', 'drondar');
define('DB_USER', 'root');
define('DB_PASS', '');
// ЖИ көмекші үшін Anthropic API кілті (бос болса — жергілікті режим жұмыс істейді)
define('ANTHROPIC_API_KEY', '');
define('ANTHROPIC_MODEL', 'claude-sonnet-5-5');

session_start();
try {
    $pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME.';charset=utf8mb4', DB_USER, DB_PASS,
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC]);
} catch (PDOException $e) {
    die('Дерекқорға қосылу қатесі. config.php файлын тексеріңіз.');
}

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function logged() { return $_SESSION['user'] ?? null; }
function require_login() { if (!logged()) { header("Location: ".(strpos($_SERVER["SCRIPT_NAME"],"/admin/")!==false?"../":"")."login.php"); exit; } }
function require_admin() { require_login(); if ($_SESSION['user']['role'] !== 'admin') { http_response_code(403); die('Қол жеткізу тек әзірлеушіге рұқсат етілген.'); } }
function csrf() { if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16)); return $_SESSION['csrf']; }
function csrf_field() { return '<input type="hidden" name="csrf" value="'.csrf().'">'; }
function check_csrf() { if (($_POST['csrf'] ?? '') !== ($_SESSION['csrf'] ?? 'x')) die('CSRF қатесі'); }
