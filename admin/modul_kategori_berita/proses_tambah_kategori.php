<?php
// PROJECT-WEB-2025/admin/modul_kategori_berita/proses_tambah_kategori.php
require_once '../../config/database.php';

// Fungsi untuk membuat slug
if (!function_exists('buatSlug')) {
    function buatSlug($string) {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9_ \-]/', '', $string);
        $string = preg_replace('/[\s_]+/', '-', $string);
        return trim($string, '-');
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_tambah_kategori'])) {
    $nama_kategori = trim($_POST['nama_kategori']);
    $slug_input = trim($_POST['slug_kategori']);

    if (empty($nama_kategori)) {
        $_SESSION['pesan_error_form'] = "Nama kategori tidak boleh kosong.";
        header("Location: tambah_kategori.php");
        exit();
    }

    $slug_kategori = !empty($slug_input) ? buatSlug($slug_input) : buatSlug($nama_kategori);

    // Cek apakah slug sudah ada
    $sql_cek = "SELECT id_kategori_berita FROM KategoriBerita WHERE slug_kategori = ?";
    $stmt_cek = mysqli_prepare($conn, $sql_cek);
    mysqli_stmt_bind_param($stmt_cek, "s", $slug_kategori);
    mysqli_stmt_execute($stmt_cek);
    
    // --- BAGIAN YANG DIPERBAIKI ---
    // 1. Ambil hasil HANYA SEKALI dan simpan ke variabel.
    $result_cek_slug = mysqli_stmt_get_result($stmt_cek); 

    // 2. Gunakan variabel $result_cek_slug untuk pengecekan.
    if ($result_cek_slug && mysqli_num_rows($result_cek_slug) > 0) {
        $_SESSION['pesan_error_form'] = "Slug kategori '" . htmlspecialchars($slug_kategori) . "' sudah ada. Harap gunakan slug lain.";
        mysqli_stmt_close($stmt_cek); // Tutup statement sebelum redirect
        if ($conn) close_connection($conn);
        header("Location: tambah_kategori.php");
        exit();
    }
    // --- AKHIR PERBAIKAN ---

    mysqli_stmt_close($stmt_cek); // Tutup statement cek slug jika tidak ada duplikat

    // Lanjutkan dengan proses INSERT
    $sql_insert = "INSERT INTO KategoriBerita (nama_kategori, slug_kategori) VALUES (?, ?)";
    $stmt_insert = mysqli_prepare($conn, $sql_insert);
    mysqli_stmt_bind_param($stmt_insert, "ss", $nama_kategori, $slug_kategori);

    if (mysqli_stmt_execute($stmt_insert)) {
        $_SESSION['pesan_sukses'] = "Kategori berita baru berhasil ditambahkan.";
    } else {
        $_SESSION['pesan_error'] = "Gagal menambahkan kategori: " . mysqli_stmt_error($stmt_insert);
    }
    mysqli_stmt_close($stmt_insert);

} else {
    // Jika akses tidak valid
    $_SESSION['pesan_error'] = "Akses tidak valid.";
}

if ($conn) close_connection($conn);
header("Location: list_kategori.php"); // Arahkan kembali ke daftar kategori
exit();
?>