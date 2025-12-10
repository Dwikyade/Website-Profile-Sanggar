<?php
require_once '../../config/database.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_kegiatan = (int)$_GET['id'];

    // Ambil path ikon untuk dihapus dari server
    $sql_select = "SELECT ikon_path FROM HalamanKegiatan WHERE id_kegiatan = ?";
    $stmt_select = mysqli_prepare($conn, $sql_select);
    $path_ikon_to_delete = null;
    if ($stmt_select) {
        mysqli_stmt_bind_param($stmt_select, "i", $id_kegiatan);
        mysqli_stmt_execute($stmt_select);
        $result = mysqli_stmt_get_result($stmt_select);
        if ($row = mysqli_fetch_assoc($result)) {
            $path_ikon_to_delete = $row['ikon_path'];
        }
        mysqli_stmt_close($stmt_select);
    }

    // Hapus record dari database
    $sql_delete = "DELETE FROM HalamanKegiatan WHERE id_kegiatan = ?";
    $stmt_delete = mysqli_prepare($conn, $sql_delete);
    if ($stmt_delete) {
        mysqli_stmt_bind_param($stmt_delete, "i", $id_kegiatan);
        if (mysqli_stmt_execute($stmt_delete)) {
            if (mysqli_stmt_affected_rows($stmt_delete) > 0) {
                $_SESSION['pesan_sukses'] = "Kegiatan berhasil dihapus.";
                // Hapus file ikon dari server
                if ($path_ikon_to_delete && file_exists(PROJECT_ROOT_PATH . '/public/' . $path_ikon_to_delete)) {
                    unlink(PROJECT_ROOT_PATH . '/public/' . $path_ikon_to_delete);
                }
            } else { $_SESSION['pesan_error'] = "Kegiatan tidak ditemukan."; }
        } else { $_SESSION['pesan_error'] = "Gagal menghapus kegiatan."; }
        mysqli_stmt_close($stmt_delete);
    }
} else {
    $_SESSION['pesan_error'] = "ID kegiatan tidak valid.";
}

if ($conn) close_connection($conn);
header("Location: list_kegiatan.php");
exit();
?>