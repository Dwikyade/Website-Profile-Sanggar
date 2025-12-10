<?php
// PROJECT-WEB-2025/admin/modul_prestasi/hapus_prestasi.php
require_once '../../config/database.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_prestasi = (int)$_GET['id'];

    // 1. Ambil path gambar untuk dihapus dari server
    $sql_select = "SELECT gambar_prestasi FROM Prestasi WHERE id_prestasi = ?";
    $stmt_select = mysqli_prepare($conn, $sql_select);
    $path_gambar_to_delete = null;

    if ($stmt_select) {
        mysqli_stmt_bind_param($stmt_select, "i", $id_prestasi);
        mysqli_stmt_execute($stmt_select);
        $result = mysqli_stmt_get_result($stmt_select);
        if ($row = mysqli_fetch_assoc($result)) {
            $path_gambar_to_delete = $row['gambar_prestasi'];
        }
        mysqli_stmt_close($stmt_select);
    }

    // 2. Hapus record dari database
    $sql_delete = "DELETE FROM Prestasi WHERE id_prestasi = ?";
    $stmt_delete = mysqli_prepare($conn, $sql_delete);

    if ($stmt_delete) {
        mysqli_stmt_bind_param($stmt_delete, "i", $id_prestasi);
        if (mysqli_stmt_execute($stmt_delete)) {
            if (mysqli_stmt_affected_rows($stmt_delete) > 0) {
                $_SESSION['pesan_sukses'] = "Data prestasi berhasil dihapus.";

                // 3. Hapus file gambar fisik dari server jika ada
                if ($path_gambar_to_delete && file_exists(PROJECT_ROOT_PATH . '/public/' . $path_gambar_to_delete)) {
                    unlink(PROJECT_ROOT_PATH . '/public/' . $path_gambar_to_delete);
                }
            } else {
                $_SESSION['pesan_error'] = "Data prestasi tidak ditemukan atau sudah dihapus.";
            }
        } else {
            $_SESSION['pesan_error'] = "Gagal menghapus data prestasi: " . mysqli_stmt_error($stmt_delete);
        }
        mysqli_stmt_close($stmt_delete);
    } else {
        $_SESSION['pesan_error'] = "Gagal menyiapkan statement hapus: " . mysqli_error($conn);
    }
} else {
    $_SESSION['pesan_error'] = "ID Prestasi tidak valid untuk dihapus.";
}

if ($conn) close_connection($conn);
header("Location: list_prestasi.php");
exit();
?>