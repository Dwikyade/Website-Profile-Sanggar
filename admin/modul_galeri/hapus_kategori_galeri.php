<?php
// PROJECT-WEB-2025/admin/modul_galeri/hapus_kategori_galeri.php
require_once '../../config/database.php';

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_kategori_galeri = (int)$_GET['id'];

    // PERHATIAN: Jika tabel ItemGaleri memiliki foreign key ke KategoriGaleri
    // dengan ON DELETE CASCADE, maka menghapus kategori akan otomatis menghapus
    // semua item galeri di dalamnya. Pastikan ini perilaku yang diinginkan.
    // Jika tidak, Anda perlu menghapus item secara manual atau mengubah foreign key.

    // Untuk keamanan tambahan, Anda bisa cek dulu apakah ada item di kategori ini
    // sebelum menghapus, dan berikan peringatan.
    // $sql_cek_item = "SELECT COUNT(*) as total_item FROM ItemGaleri WHERE id_kategori_galeri = ?";
    // ... (jalankan query cek item) ...
    // if ($total_item > 0) {
    //    $_SESSION['pesan_error'] = "Tidak bisa menghapus kategori karena masih ada item di dalamnya.";
    //    header("Location: list_kategori_galeri.php");
    //    exit();
    // }


    $sql = "DELETE FROM KategoriGaleri WHERE id_kategori_galeri = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "i", $id_kategori_galeri);
        if (mysqli_stmt_execute($stmt)) {
            if (mysqli_stmt_affected_rows($stmt) > 0) {
                $_SESSION['pesan_sukses'] = "Kategori galeri berhasil dihapus.";
            } else {
                $_SESSION['pesan_error'] = "Kategori tidak ditemukan atau sudah dihapus.";
            }
        } else {
            $_SESSION['pesan_error'] = "Gagal menghapus kategori: " . mysqli_stmt_error($stmt) . ". Pastikan tidak ada item galeri yang terkait jika tidak ada ON DELETE CASCADE.";
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['pesan_error'] = "Gagal menyiapkan statement hapus: " . mysqli_error($conn);
    }
} else {
    $_SESSION['pesan_error'] = "ID Kategori tidak valid untuk dihapus.";
}

if ($conn) close_connection($conn);
header("Location: list_kategori_galeri.php");
exit();
?>