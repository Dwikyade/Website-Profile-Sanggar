<?php
require_once '../../config/database.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_update_status'])) {
    $id_pemesanan = (int)$_POST['id_pemesanan'];
    $status = $_POST['status'];
    $catatan_admin = trim($_POST['catatan_admin']);

    // Validasi nilai status untuk keamanan
    $status_valid = ['Baru', 'Dikonfirmasi', 'Selesai', 'Dibatalkan'];
    if (!in_array($status, $status_valid)) {
        $_SESSION['pesan_error'] = "Nilai status tidak valid.";
        header("Location: list_pemesanan.php");
        exit();
    }
    
    if (empty($id_pemesanan)) { /* ... handle error jika ID kosong ... */ }

    $sql = "UPDATE PemesananJasa SET status = ?, catatan_admin = ? WHERE id_pemesanan = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssi", $status, $catatan_admin, $id_pemesanan);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['pesan_sukses'] = "Status pemesanan berhasil diperbarui.";
    } else {
        $_SESSION['pesan_error'] = "Gagal memperbarui status: " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
} else {
    $_SESSION['pesan_error'] = "Akses tidak valid.";
}

if ($conn) close_connection($conn);
header("Location: list_pemesanan.php");
exit();
?>