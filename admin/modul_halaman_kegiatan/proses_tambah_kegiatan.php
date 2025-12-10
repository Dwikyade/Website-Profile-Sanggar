<?php
// PROJECT-WEB-2025/admin/modul_halaman_kegiatan/proses_tambah_kegiatan.php
require_once '../../config/database.php';

// Fungsi helper untuk upload ikon kegiatan
// Pastikan folder /public/images/kegiatan_icons/ ada dan bisa ditulis (writable)
if (!function_exists('uploadIkonKegiatan')) {
    function uploadIkonKegiatan($file_input_name) {
        global $_FILES, $PROJECT_ROOT_PATH;
        if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == UPLOAD_ERR_OK) {
            $path_relatif = 'images/kegiatan_icons/'; // Subfolder khusus untuk ikon kegiatan
            $dir_upload = PROJECT_ROOT_PATH . '/public/' . $path_relatif;
            if (!file_exists($dir_upload)) {
                if (!mkdir($dir_upload, 0775, true)) {
                     $_SESSION['pesan_error_form'] = "Gagal membuat direktori upload ikon.";
                     return null;
                }
            }
            // Logika validasi dan pemindahan file yang aman...
            $nama_file_unik = 'kegiatan_' . time() . '_' . uniqid() . '.' . strtolower(pathinfo(basename($_FILES[$file_input_name]["name"]), PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'jpeg', 'png', 'svg', 'webp'];
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

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_tambah_kegiatan'])) {
    // 1. Ambil data yang relevan untuk Kegiatan
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $urutan_tampil = (int)$_POST['urutan_tampil'];
    $aktif = isset($_POST['aktif']) ? 1 : 0;

    // Validasi data wajib
    if (empty($judul) || empty($deskripsi)) {
        $_SESSION['pesan_error_form'] = "Judul dan deskripsi tidak boleh kosong.";
        header("Location: tambah_kegiatan.php");
        exit();
    }

    // Proses upload ikon (opsional)
    $ikon_path = uploadIkonKegiatan('ikon_path');

    // 2. Query SQL yang sudah disederhanakan
    $sql = "INSERT INTO HalamanKegiatan (ikon_path, judul, deskripsi, urutan_tampil, aktif) VALUES (?, ?, ?, ?, ?)";
    
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // 3. Sesuaikan tipe data dan jumlah variabel di bind_param
        // sssii -> 3 string (ikon, judul, deskripsi), 2 integer (urutan, aktif)
        mysqli_stmt_bind_param($stmt, "sssii", 
            $ikon_path, 
            $judul, 
            $deskripsi, 
            $urutan_tampil, 
            $aktif
        );

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = "Kegiatan baru berhasil ditambahkan.";
        } else {
            $_SESSION['pesan_error'] = "Gagal menambahkan kegiatan: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['pesan_error'] = "Gagal menyiapkan statement: " . mysqli_error($conn);
    }

    if ($conn) close_connection($conn);
    header("Location: list_kegiatan.php");
    exit();
} else {
    // Jika akses tidak valid
    $_SESSION['pesan_error'] = "Akses tidak valid.";
    if ($conn) close_connection($conn);
    header("Location: tambah_kegiatan.php");
    exit();
}
?>