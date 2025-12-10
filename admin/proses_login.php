<?php
// PROJECT-WEB-2025/admin/proses_login.php
require_once '../config/database.php'; // Path dari admin/proses_login.php ke config/

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['username'], $_POST['password'])) {
        $username = $_POST['username'];
        $password_input = $_POST['password'];

        $sql = "SELECT id_user, username, password_plaintext, role, status_aktif FROM PenggunaAdmin WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);

        if ($stmt) {
            mysqli_stmt_bind_param($stmt, "s", $username);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if ($user = mysqli_fetch_assoc($result)) {
                if ($user['status_aktif'] != 1) {
                    $_SESSION['login_error'] = "Akun pengguna tidak aktif.";
                }elseif ($password_input === $user['password_plaintext']) { // PERBANDINGAN STRING BIASA {
                    $_SESSION['admin_logged_in'] = true;
                    $_SESSION['admin_id_user'] = $user['id_user'];
                    $_SESSION['admin_username'] = $user['username'];
                    $_SESSION['admin_role'] = $user['role'];

                    $update_login_sql = "UPDATE PenggunaAdmin SET terakhir_login = NOW() WHERE id_user = ?";
                    $update_stmt = mysqli_prepare($conn, $update_login_sql);
                    if ($update_stmt) {
                        mysqli_stmt_bind_param($update_stmt, "i", $user['id_user']);
                        mysqli_stmt_execute($update_stmt);
                        mysqli_stmt_close($update_stmt);
                    }
                    mysqli_stmt_close($stmt);
                    if ($conn) close_connection($conn);
                    header("Location: " . BASE_URL_ADMIN . "/index.php");
                    exit;
                } else {
                    $_SESSION['login_error'] = "Kombinasi username atau password salah.";
                }
            } else {
                $_SESSION['login_error'] = "Kombinasi username atau password salah.";
            }
            if($stmt) mysqli_stmt_close($stmt); // Pastikan ditutup jika belum
        } else {
            $_SESSION['login_error'] = "Terjadi kesalahan sistem (DB Prepare).";
            error_log("MySQLi Prepare Error in proses_login.php (select user): " . mysqli_error($conn));
        }
    } else {
        $_SESSION['login_error'] = "Username dan password harus diisi.";
    }
    if ($conn) close_connection($conn);
    header("Location: " . BASE_URL_ADMIN . "/login.php"); 
    exit;
} else {
    $_SESSION['login_error'] = "Akses tidak valid.";
    if ($conn) close_connection($conn); // Tutup koneksi sebelum redirect
    header("Location: " . BASE_URL_ADMIN . "/login.php");
    exit;
}
?>