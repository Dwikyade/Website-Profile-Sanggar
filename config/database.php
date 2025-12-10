<?php
// PROJECT-WEB-2025/config/database.php

// Aktifkan error reporting untuk development (nonaktifkan atau atur ke log di produksi)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

define('DB_HOST', 'localhost');
define('DB_USER', 'root');      // Ganti dengan user DB Anda
define('DB_PASS', '');          // Ganti dengan password DB Anda
define('DB_NAME', 'db_sanggar'); // Nama database Anda

$conn = mysqli_connect(DB_HOST, DB_USER, DB_PASS, DB_NAME);

if (!$conn) {
    die("Koneksi ke database gagal: " . mysqli_connect_error() . 
        "<br>Pastikan detail koneksi (host, user, password, nama DB) sudah benar dan server MySQL berjalan.");
}

mysqli_set_charset($conn, "utf8mb4");

// BASE URL (PENTING! SESUAIKAN JIKA NAMA FOLDER PROYEK ANDA BERBEDA DI htdocs)
// Ini adalah path dari root domain (localhost)
// Contoh jika proyek Anda di http://localhost/folderproyekku/
// maka SITE_ROOT_URL harus '/folderproyekku'
// Jika langsung di htdocs (http://localhost/), maka SITE_ROOT_URL cukup '' (string kosong)
// Untuk kasus Anda, sepertinya:
define('SITE_ROOT_URL', '/PROJECT-WEB-2025'); 

define('BASE_URL_PUBLIC', SITE_ROOT_URL . '/public');
define('BASE_URL_ADMIN', SITE_ROOT_URL . '/admin');

// Path absolut di server (filesystem)
define('PROJECT_ROOT_PATH', dirname(__DIR__)); // Ini akan menunjuk ke PROJECT-WEB-2025/

function close_connection($connection) {
    if ($connection) {
        mysqli_close($connection);
    }
}
?>