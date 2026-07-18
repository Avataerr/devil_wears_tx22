<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . "/db.php";

function h($str) {
    return htmlspecialchars($str ?? "", ENT_QUOTES, "UTF-8");
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function is_logged_in() {
    return isset($_SESSION["user_id"]);
}

function is_admin() {
    return isset($_SESSION["role"]) && $_SESSION["role"] === "admin";
}

function require_login() {
    if (!is_logged_in()) {
        redirect("login.php");
    }
}

function require_admin() {
    if (!is_admin()) {
        redirect("login.php");
    }
}

function current_user_name() {
    return $_SESSION["name"] ?? "Guest";
}


function site_base() {
    $scriptDir = str_replace("\\", "/", dirname($_SERVER["SCRIPT_NAME"] ?? ""));
    if (preg_match("~\/admin$~", $scriptDir)) {
        $scriptDir = dirname($scriptDir);
    }
    if ($scriptDir === "/" || $scriptDir === "." || $scriptDir === "\\") {
        return "";
    }
    return rtrim($scriptDir, "/");
}

function site_url($path = "") {
    $base = site_base();
    $path = ltrim($path, "/");
    return ($base === "" ? "" : $base) . "/" . $path;
}

function log_action($conn, $action) {
    if (!isset($_SESSION["user_id"])) {
        return;
    }

    $uid = (int)$_SESSION["user_id"];
    $ip = $_SERVER["REMOTE_ADDR"] ?? "";

    $stmt = $conn->prepare("INSERT INTO audit_log (user_id, action, ip_address, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->bind_param("iss", $uid, $action, $ip);
    $stmt->execute();
    $stmt->close();
}
?>
