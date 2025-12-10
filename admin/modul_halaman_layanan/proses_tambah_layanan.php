<?php
// PROJECT-WEB-2025/admin/modul_halaman_layanan/proses_tambah_layanan.php
require_once '../../config/database.php';

// Fungsi helper untuk upload ikon layanan (bisa diletakkan di file helper terpusat)
if (!function_exists('uploadIkonLayanan')) {
    function uploadIkonLayanan($file_input_name) {
        global $_FILES, $PROJECT_ROOT_PATH;
        if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == UPLOAD_ERR_OK) {
            $path_relatif = 'images/layanan_icons/'; // Subfolder khusus untuk ikon layanan
            $dir_upload = PROJECT_ROOT_PATH . '/public/' . $path_relatif;
            if (!file_exists($dir_upload)) {
                if (!mkdir($dir_upload, 0775, true)) {
                     $_SESSION['pesan_error_form'] = "Gagal membuat direktori upload ikon.";
                     return null;
                }
            }
            $nama_file_unik = 'layanan_' . time() . '_' . uniqid() . '.' . strtolower(pathinfo(basename($_FILES[$file_input_name]["name"]), PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'jpeg', 'png', 'svg', 'webp']; // Izinkan SVG untuk ikon
            if (!in_array(strtolower(pathinfo($nama_file_unik, PATHINFO_EXTENSION)), $allowed_types)) {
                $_SESSION['pesan_error_form'] = "Tipe file ikon tidak diizinkan.";
                return null;
            }
            if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $dir_upload . $nama_file_unik)) {
                return $path_relatif . $nama_file_unik;
            }
        }
        return null; // Tidak ada file atau upload gagal
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_tambah_layanan'])) {
    $judul = trim($_POST['judul']);
    $deskripsi_singkat = trim($_POST['deskripsi_singkat']);
    $rincian_judul = trim($_POST['rincian_judul']);
    $rincian_paragraf1 = trim($_POST['rincian_paragraf1']);
    $rincian_list = trim($_POST['rincian_list']);
    $rincian_paragraf2 = trim($_POST['rincian_paragraf2']);
    $urutan_tampil = (int)$_POST['urutan_tampil'];
    $aktif = isset($_POST['aktif']) ? 1 : 0;

    if (empty($judul) || empty($deskripsi_singkat)) {
        $_SESSION['pesan_error_form'] = "Judul dan deskripsi singkat tidak boleh kosong.";
        header("Location: tambah_layanan.php");
        exit();
    }

    $ikon_path = uploadIkonLayanan('ikon_path');

    $sql = "INSERT INTO HalamanLayanan (ikon_path, judul, deskripsi_singkat, rincian_judul, rincian_paragraf1, rincian_list, rincian_paragraf2, urutan_tampil, aktif) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "sssssssii", 
        $ikon_path, $judul, $deskripsi_singkat, 
        $rincian_judul, $rincian_paragraf1, $rincian_list, $rincian_paragraf2, 
        $urutan_tampil, $aktif);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['pesan_sukses'] = "Layanan baru berhasil ditambahkan.";
    } else {
        $_SESSION['pesan_error'] = "Gagal menambahkan layanan: " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
    if ($conn) close_connection($conn);
    header("Location: list_layanan.php");
    exit();
}
?>