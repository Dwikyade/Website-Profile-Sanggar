<?php
// PROJECT-WEB-2025/admin/modul_galeri/proses_edit_item_galeri.php
require_once '../../config/database.php'; // $conn, PROJECT_ROOT_PATH, BASE_URL_PUBLIC

// Panggil ulang fungsi uploadGambarGaleri (atau pastikan sudah di-include dari file helper jika Anda membuatnya terpisah)
if (!function_exists('uploadGambarGaleri')) {
    function uploadGambarGaleri($file_input_name, $sub_folder_target) {
        global $_FILES, $PROJECT_ROOT_PATH; 
        if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == UPLOAD_ERR_OK) {
            $path_relatif_dari_public = 'images/galeri/' . $sub_folder_target . '/';
            $direktori_upload_server = PROJECT_ROOT_PATH . '/public/' . $path_relatif_dari_public;
            if (!file_exists($direktori_upload_server)) {
                if (!mkdir($direktori_upload_server, 0775, true)) {
                    $_SESSION['pesan_error_form'] = "Gagal membuat direktori upload: " . $direktori_upload_server;
                    return null; 
                }
            }
            $nama_file_asli = basename($_FILES[$file_input_name]["name"]);
            $ekstensi_file = strtolower(pathinfo($nama_file_asli, PATHINFO_EXTENSION));
            $nama_file_unik = uniqid($sub_folder_target . '_', true) . '.' . $ekstensi_file;
            $target_file_di_server = $direktori_upload_server . $nama_file_unik;
            $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($ekstensi_file, $allowed_types)) {
                $_SESSION['pesan_error_form'] = "Tipe file tidak diizinkan untuk " . htmlspecialchars($file_input_name) . ".";
                return null;
            }
            if ($_FILES[$file_input_name]["size"] > 5 * 1024 * 1024) { 
                $_SESSION['pesan_error_form'] = "Ukuran file terlalu besar untuk " . htmlspecialchars($file_input_name) . " (maks 5MB).";
                return null;
            }
            if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $target_file_di_server)) {
                return $path_relatif_dari_public . $nama_file_unik; 
            } else {
                $_SESSION['pesan_error_form'] = "Gagal mengupload file " . htmlspecialchars($file_input_name) . ".";
                return null;
            }
        } elseif (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] != UPLOAD_ERR_NO_FILE) {
            $_SESSION['pesan_error_form'] = "Error pada upload file " . htmlspecialchars($file_input_name) . ": Kode Error " . $_FILES[$file_input_name]['error'];
            return null;
        }
        return null; 
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_edit_item_galeri'])) {
    $id_item_galeri = (int)$_POST['id_item_galeri'];
    $id_kategori_galeri = (int)$_POST['id_kategori_galeri'];
    $judul_item = trim($_POST['judul_item']);
    $deskripsi_item = trim($_POST['deskripsi_item']);
    $alt_text_gambar = trim($_POST['alt_text_gambar']);
    $urutan_tampil = isset($_POST['urutan_tampil']) ? (int)$_POST['urutan_tampil'] : 0;
    $aktif = isset($_POST['aktif']) ? 1 : 0;

    // Path gambar lama (dari hidden input)
    $gambar_thumb_lama = $_POST['gambar_thumb_lama'];
    $gambar_full_lama = $_POST['gambar_full_lama'];

    if (empty($id_kategori_galeri) || empty($judul_item) || empty($id_item_galeri)) {
        $_SESSION['pesan_error_form'] = "ID Item, Kategori, dan Judul Item tidak boleh kosong.";
        header("Location: edit_item_galeri.php?id=" . $id_item_galeri); // Redirect kembali ke form edit
        exit();
    }

    // Inisialisasi path baru dengan path lama
    $path_gambar_thumb_db_baru = $gambar_thumb_lama;
    $path_gambar_full_db_baru = $gambar_full_lama;

    // Proses upload gambar thumbnail baru jika ada
    if (isset($_FILES['path_gambar_thumb_baru']) && $_FILES['path_gambar_thumb_baru']['error'] == UPLOAD_ERR_OK) {
        $hasil_upload_thumb = uploadGambarGaleri('path_gambar_thumb_baru', 'thumb');
        if ($hasil_upload_thumb) {
            // Hapus gambar thumbnail lama jika upload baru berhasil
            if (!empty($gambar_thumb_lama) && file_exists(PROJECT_ROOT_PATH . '/public/' . $gambar_thumb_lama)) {
                unlink(PROJECT_ROOT_PATH . '/public/' . $gambar_thumb_lama);
            }
            $path_gambar_thumb_db_baru = $hasil_upload_thumb;
        } else {
            // Error saat upload thumb baru, pesan error sudah di-set di $_SESSION oleh fungsi uploadGambarGaleri
            if ($conn) close_connection($conn);
            header("Location: edit_item_galeri.php?id=" . $id_item_galeri);
            exit();
        }
    }

    // Proses upload gambar full baru jika ada
    if (isset($_FILES['path_gambar_full_baru']) && $_FILES['path_gambar_full_baru']['error'] == UPLOAD_ERR_OK) {
        $hasil_upload_full = uploadGambarGaleri('path_gambar_full_baru', 'full');
        if ($hasil_upload_full) {
            // Hapus gambar full lama jika upload baru berhasil
            if (!empty($gambar_full_lama) && file_exists(PROJECT_ROOT_PATH . '/public/' . $gambar_full_lama)) {
                unlink(PROJECT_ROOT_PATH . '/public/' . $gambar_full_lama);
            }
            $path_gambar_full_db_baru = $hasil_upload_full;
        } else {
            // Error saat upload full baru
            if ($conn) close_connection($conn);
            header("Location: edit_item_galeri.php?id=" . $id_item_galeri);
            exit();
        }
    }
    
    // Update ke database
    $sql = "UPDATE ItemGaleri SET 
                id_kategori_galeri = ?, 
                judul_item = ?, 
                deskripsi_item = ?, 
                path_gambar_thumb = ?, 
                path_gambar_full = ?, 
                alt_text_gambar = ?, 
                urutan_tampil = ?, 
                aktif = ?,
                diperbarui_pada = NOW()
            WHERE id_item_galeri = ?";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "isssssiii", 
            $id_kategori_galeri, $judul_item, $deskripsi_item, 
            $path_gambar_thumb_db_baru, $path_gambar_full_db_baru, $alt_text_gambar, 
            $urutan_tampil, $aktif, $id_item_galeri
        );

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = "Item galeri berhasil diperbarui.";
        } else {
            $_SESSION['pesan_error'] = "Gagal memperbarui item galeri: " . mysqli_stmt_error($stmt);
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['pesan_error'] = "Gagal menyiapkan statement update: " . mysqli_error($conn);
    }

    if ($conn) close_connection($conn);
    header("Location: list_item_galeri.php");
    exit();

} else {
    $id_item_temp = isset($_POST['id_item_galeri']) ? (int)$_POST['id_item_galeri'] : (isset($_GET['id']) ? (int)$_GET['id'] : 0);
    $_SESSION['pesan_error'] = "Akses tidak valid atau data tidak lengkap.";
    if ($conn) close_connection($conn);
    if ($id_item_temp > 0) {
        header("Location: edit_item_galeri.php?id=" . $id_item_temp);
    } else {
        header("Location: list_item_galeri.php");
    }
    exit();
}
?>