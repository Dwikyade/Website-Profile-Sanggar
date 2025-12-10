<?php
// PROJECT-WEB-2025/admin/modul_galeri/proses_edit_kategori_galeri.php
require_once '../../config/database.php';

if (!function_exists('buatSlug')) {
    function buatSlug($string) {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9_ \-]/', '', $string);
        $string = preg_replace('/[\s_]+/', '-', $string);
        $string = trim($string, '-');
        return $string;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_edit_kategori'])) {
    $id_kategori_galeri = (int)$_POST['id_kategori_galeri'];
    $nama_kategori = trim($_POST['nama_kategori']);
    $slug_input = trim($_POST['slug_kategori']);
    $deskripsi_kategori = trim($_POST['deskripsi_kategori']);
    $urutan_tampil = isset($_POST['urutan_tampil']) ? (int)$_POST['urutan_tampil'] : 0;

    if (empty($nama_kategori) || empty($slug_input)) {
        $_SESSION['pesan_error_form'] = "Nama kategori dan slug tidak boleh kosong.";
        header("Location: edit_kategori_galeri.php?id=" . $id_kategori_galeri);
        exit();
    }
    
    $slug_kategori = buatSlug($slug_input); // Slug di-generate dari input slug yang sudah dibersihkan

    // Cek apakah slug baru (jika berubah) sudah ada untuk kategori lain
    $sql_cek_slug = "SELECT id_kategori_galeri FROM KategoriGaleri WHERE slug_kategori = ? AND id_kategori_galeri != ?";
    $stmt_cek_slug = mysqli_prepare($conn, $sql_cek_slug);
    mysqli_stmt_bind_param($stmt_cek_slug, "si", $slug_kategori, $id_kategori_galeri);
    mysqli_stmt_execute($stmt_cek_slug);
    $result_cek_slug = mysqli_stmt_get_result($stmt_cek_slug);
    if (mysqli_num_rows($result_cek_slug) > 0) {
        $_SESSION['pesan_error_form'] = "Slug kategori sudah digunakan oleh kategori lain.";
        mysqli_stmt_close($stmt_cek_slug);
        header("Location: edit_kategori_galeri.php?id=" . $id_kategori_galeri);
        exit();
    }
    mysqli_stmt_close($stmt_cek_slug);

    $sql = "UPDATE KategoriGaleri SET nama_kategori = ?, slug_kategori = ?, deskripsi_kategori = ?, urutan_tampil = ? WHERE id_kategori_galeri = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssii", $nama_kategori, $slug_kategori, $deskripsi_kategori, $urutan_tampil, $id_kategori_galeri);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = "Kategori galeri berhasil diperbarui.";
        } else {
            $_SESSION['pesan_error'] = "Gagal memperbarui kategori: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['pesan_error'] = "Gagal menyiapkan statement update: " . mysqli_error($conn);
    }
    
    if ($conn) close_connection($conn);
    header("Location: list_kategori_galeri.php");
    exit();

} else {
    if ($conn) close_connection($conn);
    // Jika bukan POST atau ID tidak ada, redirect ke daftar
    $id_kategori_temp = isset($_POST['id_kategori_galeri']) ? (int)$_POST['id_kategori_galeri'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);
    if ($id_kategori_temp > 0) {
         header("Location: edit_kategori_galeri.php?id=" . $id_kategori_temp);
    } else {
         header("Location: list_kategori_galeri.php");
    }
    exit();
}
?>