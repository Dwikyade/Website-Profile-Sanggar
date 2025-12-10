<?php
// PROJECT-WEB-2025/admin/modul_kegiatan_beranda/proses_edit_kegiatan.php
require_once '../../config/database.php';

// Fungsi upload (bisa diletakkan di file helper terpisah)
if (!function_exists('uploadIkonKegiatan')) {
    function uploadIkonKegiatan($file_input_name) {
        global $_FILES, $PROJECT_ROOT_PATH;
        if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == UPLOAD_ERR_OK) {
            $path_relatif = 'images/kegiatan_icons/';
            $dir_upload = PROJECT_ROOT_PATH . '/public/' . $path_relatif;
            if (!file_exists($dir_upload)) { mkdir($dir_upload, 0775, true); }
            $nama_file_unik = 'kegiatan_' . time() . '_' . uniqid() . '.' . strtolower(pathinfo(basename($_FILES[$file_input_name]["name"]), PATHINFO_EXTENSION));
            // Anda bisa tambahkan validasi tipe file dan ukuran di sini jika perlu
            if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $dir_upload . $nama_file_unik)) {
                return $path_relatif . $nama_file_unik;
            }
        }
        return null;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_edit_kegiatan'])) {
    $id_kegiatan = (int)$_POST['id_kegiatan'];
    $judul = trim($_POST['judul']);
    $deskripsi = trim($_POST['deskripsi']);
    $urutan_tampil = (int)$_POST['urutan_tampil'];
    $aktif = isset($_POST['aktif']) ? 1 : 0;
    $ikon_lama = $_POST['ikon_lama'];

    if (empty($judul) || empty($deskripsi) || empty($id_kegiatan)) {
        $_SESSION['pesan_error_form'] = "ID, Judul, dan Deskripsi tidak boleh kosong.";
        header("Location: edit_kegiatan.php?id=" . $id_kegiatan);
        exit();
    }

    // Inisialisasi path ikon dengan yang lama
    $ikon_path_db = $ikon_lama;

    // Jika ada ikon baru diupload, proses
    if (isset($_FILES['ikon_path_baru']) && $_FILES['ikon_path_baru']['error'] == UPLOAD_ERR_OK) {
        $hasil_upload = uploadIkonKegiatan('ikon_path_baru');
        if ($hasil_upload) {
            // Hapus ikon lama jika ada & berhasil upload baru
            if (!empty($ikon_lama) && file_exists(PROJECT_ROOT_PATH . '/public/' . $ikon_lama)) {
                unlink(PROJECT_ROOT_PATH . '/public/' . $ikon_lama);
            }
            $ikon_path_db = $hasil_upload; // Gunakan path baru
        } else {
            // Jika upload gagal, kembali ke form edit dengan pesan error
            // (Pesan error sudah di-set di session oleh fungsi upload)
            if ($conn) close_connection($conn);
            header("Location: edit_kegiatan.php?id=" . $id_kegiatan);
            exit();
        }
    }

    // --- KODE SQL UPDATE YANG DIPERBAIKI ---
    // Hanya mengupdate kolom yang ada di tabel HalamanKegiatan
    $sql = "UPDATE HalamanKegiatan SET 
                judul = ?, 
                deskripsi = ?, 
                ikon_path = ?, 
                urutan_tampil = ?, 
                aktif = ? 
            WHERE id_kegiatan = ?";
    
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        // --- BIND PARAMETER YANG DIPERBAIKI ---
        // Jumlah dan tipe disesuaikan: 3 string (s) dan 3 integer (i)
        mysqli_stmt_bind_param($stmt, "sssiii", 
            $judul, 
            $deskripsi, 
            $ikon_path_db, 
            $urutan_tampil, 
            $aktif, 
            $id_kegiatan
        );

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = "Kegiatan berhasil diperbarui.";
        } else {
            $_SESSION['pesan_error'] = "Gagal memperbarui kegiatan: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['pesan_error'] = "Gagal menyiapkan statement update: " . mysqli_error($conn);
    }

    if ($conn) close_connection($conn);
    header("Location: list_kegiatan.php");
    exit();
}
?>