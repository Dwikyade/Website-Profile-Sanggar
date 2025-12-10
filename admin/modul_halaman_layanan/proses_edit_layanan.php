<?php
// PROJECT-WEB-2025/admin/modul_halaman_layanan/proses_edit_layanan.php
require_once '../../config/database.php';

// Fungsi upload (bisa diletakkan di file helper terpusat)
if (!function_exists('uploadIkonLayanan')) {
    function uploadIkonLayanan($file_input_name) {
        global $_FILES, $PROJECT_ROOT_PATH;
        if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == UPLOAD_ERR_OK) {
            $path_relatif = 'images/layanan_icons/';
            $dir_upload = PROJECT_ROOT_PATH . '/public/' . $path_relatif;
            if (!file_exists($dir_upload)) { mkdir($dir_upload, 0775, true); }
            
            // ... (Logika validasi tipe file, ukuran, dan pemindahan file yang aman) ...
            $nama_file_unik = 'layanan_' . time() . '_' . uniqid() . '.' . strtolower(pathinfo(basename($_FILES[$file_input_name]["name"]), PATHINFO_EXTENSION));
            $allowed_types = ['jpg', 'jpeg', 'png', 'svg', 'webp'];
            if (!in_array(strtolower(pathinfo($nama_file_unik, PATHINFO_EXTENSION)), $allowed_types)) {
                $_SESSION['pesan_error_form'] = "Tipe file ikon tidak diizinkan."; return null;
            }
            if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $dir_upload . $nama_file_unik)) {
                return $path_relatif . $nama_file_unik;
            }
        }
        return null;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_edit_layanan'])) {
    // Ambil semua data dari form
    $id_layanan = (int)$_POST['id_layanan'];
    $judul = trim($_POST['judul']);
    $deskripsi_singkat = trim($_POST['deskripsi_singkat']);
    $rincian_judul = trim($_POST['rincian_judul']);
    $rincian_paragraf1 = trim($_POST['rincian_paragraf1']);
    $rincian_list = trim($_POST['rincian_list']);
    $rincian_paragraf2 = trim($_POST['rincian_paragraf2']);
    $urutan_tampil = (int)$_POST['urutan_tampil'];
    $aktif = isset($_POST['aktif']) ? 1 : 0;
    $ikon_lama = $_POST['ikon_lama'];

    // Validasi dasar
    if (empty($judul) || empty($deskripsi_singkat) || empty($id_layanan)) {
        $_SESSION['pesan_error_form'] = "ID, Judul, dan Deskripsi Singkat tidak boleh kosong.";
        header("Location: edit_layanan.php?id=" . $id_layanan);
        exit();
    }

    // Inisialisasi path ikon dengan yang lama
    $ikon_path_db = $ikon_lama;

    // Jika ada ikon baru diupload, proses
    if (isset($_FILES['ikon_path_baru']) && $_FILES['ikon_path_baru']['error'] == UPLOAD_ERR_OK) {
        $hasil_upload = uploadIkonLayanan('ikon_path_baru');
        if ($hasil_upload) {
            // Hapus ikon lama jika ada & berhasil upload baru
            if (!empty($ikon_lama) && file_exists(PROJECT_ROOT_PATH . '/public/' . $ikon_lama)) {
                unlink(PROJECT_ROOT_PATH . '/public/' . $ikon_lama);
            }
            $ikon_path_db = $hasil_upload; // Gunakan path baru
        } else {
            // Jika upload gagal, kembali ke form edit dengan pesan error
            if ($conn) close_connection($conn);
            header("Location: edit_layanan.php?id=" . $id_layanan);
            exit();
        }
    }

    // Query UPDATE dengan 9 kolom SET dan 1 kolom WHERE (total 10 placeholder)
    $sql = "UPDATE HalamanLayanan SET 
                judul = ?, 
                deskripsi_singkat = ?, 
                rincian_judul = ?, 
                rincian_paragraf1 = ?, 
                rincian_list = ?, 
                rincian_paragraf2 = ?, 
                ikon_path = ?, 
                urutan_tampil = ?, 
                aktif = ? 
            WHERE id_layanan = ?";
    
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // --- INI BAGIAN YANG DIPERBAIKI ---
        // Tipe string harus memiliki 10 karakter: 7 string (s) dan 3 integer (i)
        mysqli_stmt_bind_param($stmt, "sssssssiii", 
            $judul, 
            $deskripsi_singkat, 
            $rincian_judul, 
            $rincian_paragraf1,
            $rincian_list, 
            $rincian_paragraf2, 
            $ikon_path_db, 
            $urutan_tampil, 
            $aktif, 
            $id_layanan // Variabel ke-10 untuk WHERE clause
        );
        // --- AKHIR PERBAIKAN ---

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = "Layanan berhasil diperbarui.";
        } else {
            $_SESSION['pesan_error'] = "Gagal memperbarui layanan: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['pesan_error'] = "Gagal menyiapkan statement update: " . mysqli_error($conn);
    }

    if ($conn) close_connection($conn);
    header("Location: list_layanan.php");
    exit();
} else {
    // Jika akses tidak valid
    $_SESSION['pesan_error'] = "Akses tidak valid.";
    if ($conn) close_connection($conn);
    header("Location: list_layanan.php");
    exit();
}
?>