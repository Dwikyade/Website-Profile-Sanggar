
<?php
require_once '../../config/database.php';
require_once '../templates_admin/header.php'; // Naik 1 level ke admin, lalu ke templates_adminb

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $id_artikel = (int)$_GET['id'];

    // (Opsional) Hapus gambar terkait dari server
    $sql_get_image = "SELECT path_gambar_utama FROM artikelberita WHERE id_artikel = $id_artikel";
    $result_image = mysqli_query($conn, $sql_get_image);
    if ($row_image = mysqli_fetch_assoc($result_image)) {
        if (!empty($row_image['path_gambar_utama']) && file_exists("../" . $row_image['path_gambar_utama'])) {
            unlink("../" . $row_image['path_gambar_utama']);
        }
    }

    // Hapus dari PetaArtikelKategori dulu (meskipun ON DELETE CASCADE seharusnya menangani ini)
    $sql_delete_map = "DELETE FROM PetaArtikelKategori WHERE id_artikel = $id_artikel";
    mysqli_query($conn, $sql_delete_map);

    // Hapus artikel
    // Penting: Gunakan prepared statements di produksi
    $sql_delete_artikel = "DELETE FROM artikelberita WHERE id_artikel = $id_artikel";
    if (mysqli_query($conn, $sql_delete_artikel)) {
        echo "Berita berhasil dihapus. <a href='berita_list.php'>Kembali ke Daftar Berita</a>";
    } else {
        echo "Error deleting record: " . mysqli_error($conn);
    }
    close_connection($conn);
} else {
    header("Location: berita_list.php");
    exit();
}
?>