<?php
require_once '../../config/database.php';

// Fungsi upload (bisa diletakkan di file helper terpisah)
function uploadGambarPrestasi($file_input_name) {
    global $_FILES, $PROJECT_ROOT_PATH;
    if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == UPLOAD_ERR_OK) {
        $path_relatif = 'images/prestasi/'; // Subfolder khusus untuk foto prestasi
        $dir_upload = PROJECT_ROOT_PATH . '/public/' . $path_relatif;
        if (!file_exists($dir_upload)) { mkdir($dir_upload, 0775, true); }
        $nama_file_unik = 'prestasi_' . time() . '_' . uniqid() . '.' . strtolower(pathinfo(basename($_FILES[$file_input_name]["name"]), PATHINFO_EXTENSION));
        // ... (Logika validasi tipe & ukuran file seperti di modul lain) ...
        if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $dir_upload . $nama_file_unik)) {
            return $path_relatif . $nama_file_unik;
        }
    }
    return null;
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_tambah_prestasi'])) {
    $judul_prestasi = trim($_POST['judul_prestasi']);
    $deskripsi_prestasi = trim($_POST['deskripsi_prestasi']);
    $penyelenggara = trim($_POST['penyelenggara']);
    $tingkat = trim($_POST['tingkat']);
    $tanggal_prestasi = $_POST['tanggal_prestasi'];
    $urutan_tampil = (int)$_POST['urutan_tampil'];
    $aktif = isset($_POST['aktif']) ? 1 : 0;

    if (empty($judul_prestasi) || empty($tanggal_prestasi)) { /* ... handle error ... */ }

    $gambar_prestasi = uploadGambarPrestasi('gambar_prestasi');

    $sql = "INSERT INTO Prestasi (judul_prestasi, deskripsi_prestasi, penyelenggara, tingkat, tanggal_prestasi, gambar_prestasi, urutan_tampil, aktif) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssii", $judul_prestasi, $deskripsi_prestasi, $penyelenggara, $tingkat, $tanggal_prestasi, $gambar_prestasi, $urutan_tampil, $aktif);

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['pesan_sukses'] = "Prestasi baru berhasil ditambahkan.";
    } else {
        $_SESSION['pesan_error'] = "Gagal menambahkan prestasi: " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
    if ($conn) close_connection($conn);
    header("Location: list_prestasi.php");
    exit();
}
?>