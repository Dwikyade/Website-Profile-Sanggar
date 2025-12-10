<?php
// PROJECT-WEB-2025/admin/modul_galeri/proses_tambah_kategori_galeri.php
require_once '../../config/database.php';

// Fungsi untuk membuat slug (bisa diletakkan di file helper jika sering dipakai)
if (!function_exists('buatSlug')) { // Cek jika fungsi belum ada
    function buatSlug($string) {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9_ \-]/', '', $string);
        $string = preg_replace('/[\s_]+/', '-', $string);
        $string = trim($string, '-');
        return $string;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_tambah_kategori'])) {
    $nama_kategori = trim($_POST['nama_kategori']);
    $slug_input = trim($_POST['slug_kategori']);
    $deskripsi_kategori = trim($_POST['deskripsi_kategori']);
    $urutan_tampil = isset($_POST['urutan_tampil']) ? (int)$_POST['urutan_tampil'] : 0;

    if (empty($nama_kategori)) {
        $_SESSION['pesan_error_form'] = "Nama kategori tidak boleh kosong.";
        header("Location: tambah_kategori_galeri.php");
        exit();
    }

    $slug_kategori = !empty($slug_input) ? buatSlug($slug_input) : buatSlug($nama_kategori);

    // Cek apakah slug sudah ada
    $sql_cek_slug = "SELECT id_kategori_galeri FROM KategoriGaleri WHERE slug_kategori = ?";
    $stmt_cek_slug = mysqli_prepare($conn, $sql_cek_slug);
    mysqli_stmt_bind_param($stmt_cek_slug, "s", $slug_kategori);
    mysqli_stmt_execute($stmt_cek_slug);
    $result_cek_slug = mysqli_stmt_get_result($stmt_cek_slug);
    if (mysqli_num_rows($result_cek_slug) > 0) {
        $_SESSION['pesan_error_form'] = "Slug kategori sudah ada. Harap gunakan slug lain atau biarkan kosong untuk dibuat otomatis.";
        mysqli_stmt_close($stmt_cek_slug);
        header("Location: tambah_kategori_galeri.php");
        exit();
    }
    mysqli_stmt_close($stmt_cek_slug);

    $sql = "INSERT INTO KategoriGaleri (nama_kategori, slug_kategori, deskripsi_kategori, urutan_tampil) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "sssi", $nama_kategori, $slug_kategori, $deskripsi_kategori, $urutan_tampil);
        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = "Kategori galeri baru berhasil ditambahkan.";
        } else {
            $_SESSION['pesan_error'] = "Gagal menambahkan kategori: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['pesan_error'] = "Gagal menyiapkan statement: " . mysqli_error($conn);
    }

    if ($conn) close_connection($conn);
    header("Location: list_kategori_galeri.php");
    exit();

} else {
    // Jika bukan POST atau submit tidak ditekan
    if ($conn) close_connection($conn);
    header("Location: tambah_kategori_galeri.php");
    exit();
}
?>