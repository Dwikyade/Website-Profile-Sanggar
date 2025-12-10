<?php
// PROJECT-WEB-2025/admin/modul_galeri/hapus_item_galeri.php
require_once '../../config/database.php'; // $conn, PROJECT_ROOT_PATH

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_item_galeri = (int)$_GET['id'];

    // 1. Ambil path gambar untuk dihapus dari server
    $sql_select_paths = "SELECT path_gambar_thumb, path_gambar_full FROM ItemGaleri WHERE id_item_galeri = ?";
    $stmt_select = mysqli_prepare($conn, $sql_select_paths);
    $path_thumb_to_delete = null;
    $path_full_to_delete = null;

    if ($stmt_select) {
        mysqli_stmt_bind_param($stmt_select, "i", $id_item_galeri);
        mysqli_stmt_execute($stmt_select);
        $result_paths = mysqli_stmt_get_result($stmt_select);
        if ($row_paths = mysqli_fetch_assoc($result_paths)) {
            $path_thumb_to_delete = $row_paths['path_gambar_thumb'];
            $path_full_to_delete = $row_paths['path_gambar_full'];
        }
        mysqli_stmt_close($stmt_select);
    } else {
        $_SESSION['pesan_error'] = "Gagal mengambil data path gambar: " . mysqli_error($conn);
        if ($conn) close_connection($conn);
        header("Location: list_item_galeri.php");
        exit();
    }

    // 2. Hapus record dari database
    $sql_delete = "DELETE FROM ItemGaleri WHERE id_item_galeri = ?";
    $stmt_delete = mysqli_prepare($conn, $sql_delete);

    if ($stmt_delete) {
        mysqli_stmt_bind_param($stmt_delete, "i", $id_item_galeri);
        if (mysqli_stmt_execute($stmt_delete)) {
            if (mysqli_stmt_affected_rows($stmt_delete) > 0) {
                $_SESSION['pesan_sukses'] = "Item galeri berhasil dihapus.";

                // 3. Hapus file gambar dari server jika record DB berhasil dihapus
                if ($path_thumb_to_delete && file_exists(PROJECT_ROOT_PATH . '/public/' . $path_thumb_to_delete)) {
                    unlink(PROJECT_ROOT_PATH . '/public/' . $path_thumb_to_delete);
                }
                if ($path_full_to_delete && file_exists(PROJECT_ROOT_PATH . '/public/' . $path_full_to_delete)) {
                    unlink(PROJECT_ROOT_PATH . '/public/' . $path_full_to_delete);
                }
            } else {
                $_SESSION['pesan_error'] = "Item galeri tidak ditemukan atau sudah dihapus.";
            }
        } else {
            $_SESSION['pesan_error'] = "Gagal menghapus item galeri: " . mysqli_stmt_error($stmt_delete);
        }
        mysqli_stmt_close($stmt_delete);
    } else {
        $_SESSION['pesan_error'] = "Gagal menyiapkan statement hapus: " . mysqli_error($conn);
    }
} else {
    $_SESSION['pesan_error'] = "ID Item Galeri tidak valid untuk dihapus.";
}

if ($conn) close_connection($conn);
header("Location: list_item_galeri.php");
exit();
?>