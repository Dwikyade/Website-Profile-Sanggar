<?php
// PROJECT-WEB-2025/admin/modul_kategori_berita/hapus_kategori.php
require_once '../../config/database.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_kategori_berita = (int)$_GET['id'];

    // Menghapus kategori akan otomatis menghapus entri terkait di PetaArtikelKategori
    // karena kita menggunakan ON DELETE CASCADE pada foreign key saat membuat tabel tersebut.
    // Jika tidak menggunakan ON DELETE CASCADE, Anda perlu menghapus dari PetaArtikelKategori terlebih dahulu.

    $sql = "DELETE FROM KategoriBerita WHERE id_kategori_berita = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_kategori_berita);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $_SESSION['pesan_sukses'] = "Kategori berita berhasil dihapus.";
            } else {
                $_SESSION['pesan_error'] = "Kategori tidak ditemukan atau sudah dihapus.";
            }
        } else {
            $_SESSION['pesan_error'] = "Gagal menghapus kategori: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['pesan_error'] = "Gagal menyiapkan statement hapus: " . mysqli_error($conn);
    }
} else {
    $_SESSION['pesan_error'] = "ID Kategori tidak valid untuk dihapus.";
}

if ($conn) close_connection($conn);
header("Location: list_kategori.php");
exit();
?>