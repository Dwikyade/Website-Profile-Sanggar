<?php
// PROJECT-WEB-2025/admin/modul_kontak/hapus_pesan.php
require_once '../../config/database.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_pesan = (int)$_GET['id'];

    $sql = "DELETE FROM PesanKontak WHERE id_pesan = ?";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_pesan);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = "Pesan berhasil dihapus.";
        } else {
            $_SESSION['pesan_error'] = "Gagal menghapus pesan: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    }
} else {
    $_SESSION['pesan_error'] = "ID Pesan tidak valid.";
}
if ($conn) close_connection($conn);
header("Location: list_pesan.php");
exit();
?>