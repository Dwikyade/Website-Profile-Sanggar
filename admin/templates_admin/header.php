<?php
// PROJECT-WEB-2025/admin/templates_admin/header.php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Meskipun sudah ada di config, pastikan lagi di sini sebagai fallback.
}

// Jika config/database.php belum di-include oleh file pemanggil, BASE_URL_ADMIN mungkin belum ada.
// File pemanggil (seperti index.php, list_berita.php) WAJIB include config/database.php SEBELUM header.php ini.
if (!defined('BASE_URL_ADMIN')) {
    // Ini seharusnya tidak terjadi jika pola include sudah benar
    // Fallback darurat, tapi idealnya config/database.php yang mendefinisikannya.
    // Sesuaikan path ini jika struktur Anda sangat berbeda atau jika file config/database.php tidak ada.
    if (file_exists(dirname(__DIR__, 2) . '/config/database.php')) { // Cek 2 level di atas (dari templates_admin -> admin -> root)
         require_once dirname(__DIR__, 2) . '/config/database.php';
    } else {
        // Jika tetap tidak ketemu, set manual dengan warning (HARUS DIPERBAIKI)
        define('BASE_URL_ADMIN', '/PROJECT-WEB-2025/admin'); // GANTI JIKA PERLU
        define('BASE_URL_PUBLIC', '/PROJECT-WEB-2025/public'); // GANTI JIKA PERLU
        error_log("Peringatan: BASE_URL_ADMIN tidak terdefinisi, menggunakan fallback di admin/templates_admin/header.php. Harap periksa include config/database.php.");
    }
}

$current_page_basename = basename($_SERVER['PHP_SELF']);

if ($current_page_basename != 'login.php' && $current_page_basename != 'proses_login.php') {
    if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
        header("Location: " . BASE_URL_ADMIN . "/login.php");
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($admin_page_title) ? htmlspecialchars($admin_page_title) . " - Admin" : "Admin Sanggar"; ?></title>
    <link rel="stylesheet" href="<?php echo BASE_URL_ADMIN; ?>/css/admin_style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
    <link rel="icon" href="<?php echo BASE_URL_PUBLIC; ?>/images/logo.png" /> </head>
<body>
    <div class="admin-wrapper">
        <?php include_once __DIR__ . '/sidebar.php'; ?>
        <div class="admin-main-content">
            <header class="admin-top-header">
                <div class="hamburger-menu" onclick="toggleSidebar()">
                    <i class="fas fa-bars"></i>
                </div>
                <div class="page-title-admin">
                    <h3><?php echo isset($admin_page_title) ? htmlspecialchars($admin_page_title) : "Dashboard"; ?></h3>
                </div>
                <div class="user-info-admin">
                    <i class="fas fa-user-circle"></i>
                    <span>Selamat datang, <?php echo isset($_SESSION['admin_username']) ? htmlspecialchars($_SESSION['admin_username']) : 'Admin'; ?>!</span>
                    <a href="<?php echo BASE_URL_ADMIN; ?>/logout.php" class="logout-button-admin"><i class="fas fa-sign-out-alt"></i> Logout</a>
                </div>
            </header>
            <main class="admin-page-content">