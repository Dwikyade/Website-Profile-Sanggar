<?php
// PROJECT-WEB-2025/admin/modul_prestasi/proses_edit_prestasi.php
require_once '../../config/database.php';

// Panggil ulang fungsi uploadGambarPrestasi atau include dari file helper
if (!function_exists('uploadGambarPrestasi')) {
    function uploadGambarPrestasi($file_input_name) {
        global $_FILES, $PROJECT_ROOT_PATH;
        if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == UPLOAD_ERR_OK) {
            $path_relatif = 'images/prestasi/';
            $dir_upload = PROJECT_ROOT_PATH . '/public/' . $path_relatif;
            if (!file_exists($dir_upload)) { mkdir($dir_upload, 0775, true); }
            $nama_file_unik = 'prestasi_' . time() . '_' . uniqid() . '.' . strtolower(pathinfo(basename($_FILES[$file_input_name]["name"]), PATHINFO_EXTENSION));
            // Validasi tipe file & ukuran bisa ditambahkan di sini
            if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $dir_upload . $nama_file_unik)) {
                return $path_relatif . $nama_file_unik;
            }
        }
        return null;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_edit_prestasi'])) {
    $id_prestasi = (int)$_POST['id_prestasi'];
    $judul_prestasi = trim($_POST['judul_prestasi']);
    $deskripsi_prestasi = trim($_POST['deskripsi_prestasi']);
    $penyelenggara = trim($_POST['penyelenggara']);
    $tingkat = trim($_POST['tingkat']);
    $tanggal_prestasi = $_POST['tanggal_prestasi'];
    $urutan_tampil = (int)$_POST['urutan_tampil'];
    $aktif = isset($_POST['aktif']) ? 1 : 0;
    $gambar_prestasi_lama = $_POST['gambar_prestasi_lama'];

    if (empty($judul_prestasi) || empty($tanggal_prestasi) || empty($id_prestasi)) { /* ... handle error validasi ... */ }

    // Inisialisasi path gambar dengan yang lama
    $path_gambar_db = $gambar_prestasi_lama;

    // Jika ada gambar baru diupload
    if (isset($_FILES['gambar_prestasi_baru']) && $_FILES['gambar_prestasi_baru']['error'] == UPLOAD_ERR_OK) {
        $hasil_upload = uploadGambarPrestasi('gambar_prestasi_baru');
        if ($hasil_upload) {
            // Hapus gambar lama dari server jika ada & berhasil upload baru
            if (!empty($gambar_prestasi_lama) && file_exists(PROJECT_ROOT_PATH . '/public/' . $gambar_prestasi_lama)) {
                unlink(PROJECT_ROOT_PATH . '/public/' . $gambar_prestasi_lama);
            }
            $path_gambar_db = $hasil_upload; // Gunakan path gambar yang baru
        } else {
            // Error saat upload, pesan error sudah di-set di session oleh fungsi upload
            if ($conn) close_connection($conn);
            header("Location: edit_prestasi.php?id=" . $id_prestasi);
            exit();
        }
    }

    // Update ke database
    $sql = "UPDATE Prestasi SET 
                judul_prestasi = ?, deskripsi_prestasi = ?, penyelenggara = ?, 
                tingkat = ?, tanggal_prestasi = ?, gambar_prestasi = ?, 
                urutan_tampil = ?, aktif = ? 
            WHERE id_prestasi = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssiii", 
        $judul_prestasi, $deskripsi_prestasi, $penyelenggara, 
        $tingkat, $tanggal_prestasi, $path_gambar_db, 
        $urutan_tampil, $aktif, $id_prestasi
    );

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['pesan_sukses'] = "Data prestasi berhasil diperbarui.";
    } else {
        $_SESSION['pesan_error'] = "Gagal memperbarui data prestasi: " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
    if ($conn) close_connection($conn);
    header("Location: list_prestasi.php");
    exit();
}
?>