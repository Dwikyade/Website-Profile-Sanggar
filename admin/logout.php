<?php
// PROJECT-WEB-2025/admin/logout.php
require_once '../config/database.php'; // Untuk session_start() dan BASE_URL_ADMIN

// Hapus semua variabel sesi
$_SESSION = array();

// Jika ingin menghancurkan sesi sepenuhnya, hapus juga cookie sesi.
// Catatan: Ini akan menghancurkan sesi, dan bukan hanya data sesi!
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}

session_destroy();
header("Location: " . BASE_URL_ADMIN . "/login.php");
exit;
?>