<?php
require_once '../../config/database.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_pendaftar = (int)$_GET['id'];

    $sql = "DELETE FROM PendaftarMurid WHERE id_pendaftar = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_pendaftar);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = "Data pendaftar berhasil dihapus.";
        } else {
            $_SESSION['pesan_error'] = "Gagal menghapus data: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    }
} else {
    $_SESSION['pesan_error'] = "ID Pendaftar tidak valid.";
}

if ($conn) close_connection($conn);
header("Location: list_pendaftar.php");
exit();
?>