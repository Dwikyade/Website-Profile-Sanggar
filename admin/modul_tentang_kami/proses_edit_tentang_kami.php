<?php
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

// Fungsi upload (bisa diletakkan di file helper terpisah)
if (!function_exists('uploadGambarHalaman')) {
    function uploadGambarHalaman($file_input_name) {
        global $_FILES, $PROJECT_ROOT_PATH;
        if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == UPLOAD_ERR_OK) {
            $path_relatif = 'images/halaman/'; // Subfolder untuk gambar halaman statis seperti about, service, dll.
            $dir_upload = PROJECT_ROOT_PATH . '/public/' . $path_relatif;
            if (!file_exists($dir_upload)) { mkdir($dir_upload, 0775, true); }
            $nama_file_unik = 'halaman_' . time() . '_' . uniqid() . '.' . strtolower(pathinfo(basename($_FILES[$file_input_name]["name"]), PATHINFO_EXTENSION));
            // Validasi tipe & ukuran file
            if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $dir_upload . $nama_file_unik)) {
                return $path_relatif . $nama_file_unik;
            }
        }
        return null;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_update_tentang_kami'])) {
    // Ambil semua data teks
    $main_title = $_POST['main_title'];
    $main_subtitle = $_POST['main_subtitle'];
    $welcome_title = $_POST['welcome_title'];
    $welcome_p1 = $_POST['welcome_p1'];
    $welcome_p2 = $_POST['welcome_p2'];
    $vision_mission_title = $_POST['vision_mission_title'];
    $vision_mission_list = $_POST['vision_mission_list'];
    $history_title = $_POST['history_title'];
    $history_p1 = $_POST['history_p1'];
    $history_p2 = $_POST['history_p2'];
    $cta_title = $_POST['cta_title'];
    $cta_subtitle = $_POST['cta_subtitle'];

    // Proses gambar
    $welcome_image_db = $_POST['welcome_image_lama'];
    $history_image_db = $_POST['history_image_lama'];
    
    $upload_welcome = uploadGambarHalaman('welcome_image_baru');
    if ($upload_welcome) {
        if (!empty($welcome_image_db) && file_exists(PROJECT_ROOT_PATH . '/public/' . $welcome_image_db)) unlink(PROJECT_ROOT_PATH . '/public/' . $welcome_image_db);
        $welcome_image_db = $upload_welcome;
    }

    $upload_history = uploadGambarHalaman('history_image_baru');
    if ($upload_history) {
        if (!empty($history_image_db) && file_exists(PROJECT_ROOT_PATH . '/public/' . $history_image_db)) unlink(PROJECT_ROOT_PATH . '/public/' . $history_image_db);
        $history_image_db = $upload_history;
    }

    // Update ke database
    $sql = "UPDATE HalamanTentangKami SET
                main_title = ?, main_subtitle = ?, welcome_title = ?,
                welcome_p1 = ?, welcome_p2 = ?, welcome_image_path = ?,
                vision_mission_title = ?, vision_mission_list = ?,
                history_title = ?, history_p1 = ?, history_p2 = ?, history_image_path = ?,
                cta_title = ?, cta_subtitle = ?
            WHERE id = 1";
    
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssssssssss",
        $main_title, $main_subtitle, $welcome_title, 
        $welcome_p1, $welcome_p2, $welcome_image_db,
        $vision_mission_title, $vision_mission_list,
        $history_title, $history_p1, $history_p2, $history_image_db,
        $cta_title, $cta_subtitle
    );

    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['pesan_sukses'] = "Halaman 'Tentang Kami' berhasil diperbarui.";
    } else {
        $_SESSION['pesan_error'] = "Gagal memperbarui halaman: " . mysqli_stmt_error($stmt);
    }
    mysqli_stmt_close($stmt);
    if ($conn) close_connection($conn);
    header("Location: edit_tentang_kami.php");
    exit();
} else {
    header("Location: edit_tentang_kami.php");
    exit();
}
?>