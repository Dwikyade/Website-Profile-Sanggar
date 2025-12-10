<?php
// PROJECT-WEB-2025/admin/modul_galeri/proses_tambah_item_galeri.php
require_once '../../config/database.php'; // Untuk $conn, PROJECT_ROOT_PATH, session_start()

// Fungsi helper untuk upload gambar galeri
// Fungsi ini tetap sama dan akan kita gunakan untuk upload thumbnail
if (!function_exists('uploadGambarGaleri')) {
    function uploadGambarGaleri($file_input_name, $sub_folder_target) {
        global $_FILES, $PROJECT_ROOT_PATH; // Akses variabel global

        if (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] == UPLOAD_ERR_OK) {
            $path_relatif_dari_public = 'images/galeri/' . $sub_folder_target . '/';
            $direktori_upload_server = PROJECT_ROOT_PATH . '/public/' . $path_relatif_dari_public;

            if (!file_exists($direktori_upload_server)) {
                if (!mkdir($direktori_upload_server, 0775, true)) {
                     $_SESSION['pesan_error_form'] = "GAGAL membuat direktori upload: " . htmlspecialchars($direktori_upload_server);
                     return null;
                }
            }

            if (!is_writable($direktori_upload_server)) {
                 $_SESSION['pesan_error_form'] = "Direktori upload tidak bisa ditulis. Periksa izin folder.";
                 return null;
            }

            $nama_file_asli = basename($_FILES[$file_input_name]["name"]);
            $ekstensi_file = strtolower(pathinfo($nama_file_asli, PATHINFO_EXTENSION));
            $nama_file_unik = 'galeri_' . time() . '_' . uniqid() . '.' . $ekstensi_file;
            $target_file_di_server = $direktori_upload_server . $nama_file_unik;

            $allowed_types = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            if (!in_array($ekstensi_file, $allowed_types)) {
                $_SESSION['pesan_error_form'] = "Tipe file tidak diizinkan. Hanya JPG, JPEG, PNG, GIF, WEBP.";
                return null;
            }
            if ($_FILES[$file_input_name]["size"] > 5 * 1024 * 1024) { 
                $_SESSION['pesan_error_form'] = "Ukuran file terlalu besar (maks 5MB).";
                return null;
            }

            if (move_uploaded_file($_FILES[$file_input_name]["tmp_name"], $target_file_di_server)) {
                return $path_relatif_dari_public . $nama_file_unik; 
            } else {
                $_SESSION['pesan_error_form'] = "GAGAL memindahkan file yang diupload.";
                return null;
            }
        } elseif (isset($_FILES[$file_input_name]) && $_FILES[$file_input_name]['error'] != UPLOAD_ERR_NO_FILE) {
            $_SESSION['pesan_error_form'] = "Terjadi error saat upload file: Kode " . $_FILES[$file_input_name]['error'];
            return null;
        }
        return null;
    }
}


if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_tambah_item_galeri'])) {
    // Ambil dan bersihkan data dari form
    $id_kategori_galeri = (int)$_POST['id_kategori_galeri'];
    $tipe_media = $_POST['tipe_media']; // Tipe media: 'Gambar' atau 'Video'
    $judul_item = trim($_POST['judul_item']);
    $deskripsi_item = trim($_POST['deskripsi_item']);
    $url_video = trim($_POST['url_video']); // URL video dari form
    $alt_text_gambar = trim($_POST['alt_text_gambar']);
    $urutan_tampil = isset($_POST['urutan_tampil']) ? (int)$_POST['urutan_tampil'] : 0;
    $aktif = isset($_POST['aktif']) ? 1 : 0;

    // Validasi data wajib
    if (empty($id_kategori_galeri) || empty($judul_item) || empty($tipe_media)) {
        $_SESSION['pesan_error_form'] = "Kategori, Tipe Media, dan Judul Item tidak boleh kosong.";
        header("Location: tambah_item_galeri.php");
        exit();
    }
    
    // --- AWAL LOGIKA BARU BERDASARKAN TIPE MEDIA ---

    $path_gambar_thumb_db = null;
    $path_gambar_full_db = null;

    // Thumbnail wajib untuk semua tipe (gambar maupun video)
    if (!isset($_FILES['path_gambar_thumb']) || $_FILES['path_gambar_thumb']['error'] == UPLOAD_ERR_NO_FILE) {
        $_SESSION['pesan_error_form'] = "Gambar Thumbnail wajib diupload (untuk gambar sampul).";
        header("Location: tambah_item_galeri.php");
        exit();
    }
    $path_gambar_thumb_db = uploadGambarGaleri('path_gambar_thumb', 'thumb');
    
    // Jika upload thumbnail gagal, hentikan proses
    if ($path_gambar_thumb_db === null && isset($_SESSION['pesan_error_form'])) {
        header("Location: tambah_item_galeri.php");
        exit();
    }
    
    // Logika jika Tipe Media adalah "Video"
    if ($tipe_media === 'Video') {
        if (empty($url_video) || !filter_var($url_video, FILTER_VALIDATE_URL)) {
            $_SESSION['pesan_error_form'] = "URL Video wajib diisi dan harus berupa URL yang valid.";
            // Hapus thumbnail yang mungkin sudah terupload
            if ($path_gambar_thumb_db && file_exists(PROJECT_ROOT_PATH . '/public/' . $path_gambar_thumb_db)) {
                unlink(PROJECT_ROOT_PATH . '/public/' . $path_gambar_thumb_db);
            }
            header("Location: tambah_item_galeri.php");
            exit();
        }
        $path_gambar_full_db = null; // Kosongkan path gambar full untuk video
    
    // Logika jika Tipe Media adalah "Gambar"
    } elseif ($tipe_media === 'Gambar') {
        if (!isset($_FILES['path_gambar_full']) || $_FILES['path_gambar_full']['error'] == UPLOAD_ERR_NO_FILE) {
            $_SESSION['pesan_error_form'] = "Gambar Ukuran Penuh wajib diupload untuk tipe media Gambar.";
             if ($path_gambar_thumb_db && file_exists(PROJECT_ROOT_PATH . '/public/' . $path_gambar_thumb_db)) {
                unlink(PROJECT_ROOT_PATH . '/public/' . $path_gambar_thumb_db);
            }
            header("Location: tambah_item_galeri.php");
            exit();
        }
        $path_gambar_full_db = uploadGambarGaleri('path_gambar_full', 'full');
        $url_video = null; // Kosongkan URL video untuk gambar

        // Jika upload gambar full gagal, hentikan proses
        if ($path_gambar_full_db === null && isset($_SESSION['pesan_error_form'])) {
            if ($path_gambar_thumb_db && file_exists(PROJECT_ROOT_PATH . '/public/' . $path_gambar_thumb_db)) {
                unlink(PROJECT_ROOT_PATH . '/public/' . $path_gambar_thumb_db);
            }
            header("Location: tambah_item_galeri.php");
            exit();
        }
    } else {
        $_SESSION['pesan_error_form'] = "Tipe media tidak valid.";
        header("Location: tambah_item_galeri.php");
        exit();
    }
    // --- AKHIR LOGIKA BARU ---

    // Jika semua proses di atas berhasil, simpan ke database
    $sql = "INSERT INTO ItemGaleri (id_kategori_galeri, tipe_media, judul_item, deskripsi_item, path_gambar_thumb, path_gambar_full, url_video, alt_text_gambar, urutan_tampil, aktif) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "isssssssii", 
            $id_kategori_galeri, 
            $tipe_media,
            $judul_item, 
            $deskripsi_item, 
            $path_gambar_thumb_db, 
            $path_gambar_full_db, // Akan NULL jika video
            $url_video,            // Akan NULL jika gambar
            $alt_text_gambar, 
            $urutan_tampil, 
            $aktif
        );

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['pesan_sukses'] = "Item galeri baru berhasil ditambahkan.";
        } else {
            $_SESSION['pesan_error'] = "Gagal menyimpan data ke database: " . mysqli_stmt_error($stmt);
            // Hapus file yang mungkin sudah terupload jika DB insert gagal
            if ($path_gambar_thumb_db && file_exists(PROJECT_ROOT_PATH . '/public/' . $path_gambar_thumb_db)) unlink(PROJECT_ROOT_PATH . '/public/' . $path_gambar_thumb_db);
            if ($path_gambar_full_db && file_exists(PROJECT_ROOT_PATH . '/public/' . $path_gambar_full_db)) unlink(PROJECT_ROOT_PATH . '/public/' . $path_gambar_full_db);
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['pesan_error'] = "Gagal menyiapkan statement SQL: " . mysqli_error($conn);
    }

    if ($conn) close_connection($conn);
    header("Location: list_item_galeri.php");
    exit();
}
?>