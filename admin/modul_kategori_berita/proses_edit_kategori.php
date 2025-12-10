<?php
// PROJECT-WEB-2025/admin/modul_kategori_berita/proses_edit_kategori.php
require_once '../../config/database.php';

// Fungsi untuk membuat slug (letakkan di file helper jika sering dipakai)
if (!function_exists('buatSlug')) {
    function buatSlug($string) {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9_ \-]/', '', $string);
        $string = preg_replace('/[\s_]+/', '-', $string);
        return trim($string, '-');
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_edit_kategori'])) {
    $id_kategori_berita = (int)$_POST['id_kategori_berita'];
    $nama_kategori = trim($_POST['nama_kategori']);
    $slug_input = trim($_POST['slug_kategori']);

    // Validasi dasar
    if (empty($nama_kategori) || empty($slug_input) || empty($id_kategori_berita)) {
        $_SESSION['pesan_error_form'] = "Nama kategori dan slug tidak boleh kosong.";
        header("Location: edit_kategori.php?id=" . $id_kategori_berita);
        exit();
    }
    
    $slug_kategori = buatSlug($slug_input);

    // Cek apakah slug baru (jika berubah) sudah ada untuk kategori lain
    $sql_cek_slug = "SELECT id_kategori_berita FROM KategoriBerita WHERE slug_kategori = ? AND id_kategori_berita != ?";
    $stmt_cek_slug = mysqli_prepare($conn, $sql_cek_slug);
    mysqli_stmt_bind_param($stmt_cek_slug, "si", $slug_kategori, $id_kategori_berita);
    mysqli_stmt_execute($stmt_cek_slug);
    $result_cek_slug = mysqli_stmt_get_result($stmt_cek_slug);
    if (mysqli_num_rows($result_cek_slug) > 0) {
        $_SESSION['pesan_error_form'] = "Slug kategori '$slug_kategori' sudah digunakan oleh kategori lain.";
        mysqli_stmt_close($stmt_cek_slug);
        header("Location: edit_kategori.php?id=" . $id_kategori_berita);
        exit();
    }
    mysqli_stmt_close($stmt_cek_slug);

    // Lanjutkan dengan proses UPDATE
    $sql = "UPDATE KategoriBerita SET nama_kategori = ?, slug_kategori = ?, diperbarui_pada = NOW() WHERE id_kategori_berita = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssi", $nama_kategori, $slug_kategori, $id_kategori_berita);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = "Kategori berita berhasil diperbarui.";
        } else {
            $_SESSION['pesan_error'] = "Gagal memperbarui kategori: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['pesan_error'] = "Gagal menyiapkan statement update: " . mysqli_error($conn);
    }
    
    if ($conn) close_connection($conn);
    header("Location: list_kategori.php");
    exit();

} else {
    // Jika bukan POST atau submit tidak sesuai, redirect ke daftar kategori
    if ($conn) close_connection($conn);
    header("Location: list_kategori.php");
    exit();
}
?>