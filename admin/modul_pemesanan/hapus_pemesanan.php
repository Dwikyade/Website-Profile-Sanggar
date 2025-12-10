<?php
require_once '../../config/database.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_pemesanan = (int)$_GET['id'];

    $sql = "DELETE FROM PemesananJasa WHERE id_pemesanan = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_pemesanan);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = "Data pemesanan berhasil dihapus.";
        } else {
            $_SESSION['pesan_error'] = "Gagal menghapus data: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    }
} else {
    $_SESSION['pesan_error'] = "ID Pemesanan tidak valid.";
}

if ($conn) close_connection($conn);
header("Location: list_pemesanan.php");
exit();
?>