<?php
// PROJECT-WEB-2025/admin/modul_berita/proses_tambah_berita.php
require_once '../../config/database.php';

// Fungsi untuk membuat slug yang bersih
if (!function_exists('buatSlug')) {
    function buatSlug($string) {
        $string = strtolower(trim($string));
        $string = preg_replace('/[^a-z0-9_ \-]/', '', $string);
        $string = preg_replace('/[\s_]+/', '-', $string);
        return trim($string, '-');
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit_tambah_berita'])) {
    
    $judul_artikel = trim($_POST['judul_artikel']);
    $slug_artikel_input = trim($_POST['slug_artikel']);
    $kutipan_artikel = trim($_POST['kutipan_artikel']);
    $isi_artikel = trim($_POST['isi_artikel']);
    $penulis = trim($_POST['penulis']);
    $status_publikasi = $_POST['status_publikasi'];

    if (empty($judul_artikel) || empty($isi_artikel)) {
        $_SESSION['pesan_error'] = "Judul dan Isi Artikel tidak boleh kosong.";
        header("Location: tambah_berita.php");
        exit();
    }

    // --- AWAL BAGIAN KOREKSI: PENANGANAN SLUG DUPLIKAT ---
    
    // Buat slug awal
    $base_slug = !empty($slug_artikel_input) ? buatSlug($slug_artikel_input) : buatSlug($judul_artikel);
    $slug_artikel = $base_slug;
    $counter = 2;

    // Cek apakah slug sudah ada di database
    $sql_cek_slug = "SELECT id_artikel FROM ArtikelBerita WHERE slug_artikel = ?";
    $stmt_cek = mysqli_prepare($conn, $sql_cek_slug);

    if ($stmt_cek) {
        mysqli_stmt_bind_param($stmt_cek, "s", $slug_artikel);
        
        // Loop untuk menemukan slug yang unik
        while (true) {
            mysqli_stmt_execute($stmt_cek);
            $result_cek = mysqli_stmt_get_result($stmt_cek);
            if (mysqli_num_rows($result_cek) == 0) {
                // Jika tidak ada duplikat, keluar dari loop
                break;
            }
            // Jika ada duplikat, tambahkan angka di belakangnya
            $slug_artikel = $base_slug . '-' . $counter;
            $counter++;
        }
        mysqli_stmt_close($stmt_cek);
    } else {
        // Gagal prepare statement, gunakan slug dasar dan biarkan DB yang handle jika error
        $slug_artikel = $base_slug; 
    }
    // --- AKHIR BAGIAN KOREKSI ---

    // ... (sisa kode Anda untuk persiapan variabel tanggal, boolean, dan upload gambar tetap sama) ...
    $kolom_tanggal_publikasi = !empty($_POST['tanggal_publikasi']) ? date('Y-m-d H:i:s', strtotime($_POST['tanggal_publikasi'])) : ($status_publikasi == 'terbit' ? date('Y-m-d H:i:s') : null);
    $kolom_tgl_acara_mulai = !empty($_POST['tanggal_acara_mulai']) ? $_POST['tanggal_acara_mulai'] : null;
    $kolom_tgl_acara_selesai = !empty($_POST['tanggal_acara_selesai']) ? $_POST['tanggal_acara_selesai'] : null;
    $kolom_waktu_acara = !empty($_POST['waktu_acara']) ? $_POST['waktu_acara'] : null;
    $kolom_lokasi_acara = !empty($_POST['lokasi_acara']) ? trim($_POST['lokasi_acara']) : null;
    $apakah_unggulan = isset($_POST['apakah_unggulan']) ? 1 : 0;
    $apakah_pengumuman_sticky = isset($_POST['apakah_pengumuman_sticky']) ? 1 : 0;
    
    $path_gambar_db = null;
    if (isset($_FILES['path_gambar_utama']) && $_FILES['path_gambar_utama']['error'] == UPLOAD_ERR_OK) {
        $path_relatif_dari_public = 'images/berita/';
        $direktori_upload_server = PROJECT_ROOT_PATH . '/public/' . $path_relatif_dari_public;
        if (!file_exists($direktori_upload_server)) { mkdir($direktori_upload_server, 0775, true); }
        // ... (logika upload gambar lengkap Anda di sini) ...
        $nama_file_unik = 'berita_' . time() . '_' . uniqid() . '.' . strtolower(pathinfo(basename($_FILES["path_gambar_utama"]["name"]), PATHINFO_EXTENSION));
        if (move_uploaded_file($_FILES["path_gambar_utama"]["tmp_name"], $direktori_upload_server . $nama_file_unik)) {
            $path_gambar_db = $path_relatif_dari_public . $nama_file_unik;
        }
    }


    // Query INSERT sekarang menggunakan variabel $slug_artikel yang sudah dijamin unik
    $sql = "INSERT INTO ArtikelBerita (judul_artikel, slug_artikel, kutipan_artikel, isi_artikel, path_gambar_utama, penulis, status_publikasi, tanggal_publikasi, tanggal_acara_mulai, tanggal_acara_selesai, waktu_acara, lokasi_acara, apakah_unggulan, apakah_pengumuman_sticky) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssssssssssssii", 
            $judul_artikel, $slug_artikel, $kutipan_artikel, $isi_artikel, $path_gambar_db, 
            $penulis, $status_publikasi, $kolom_tanggal_publikasi,
            $kolom_tgl_acara_mulai, $kolom_tgl_acara_selesai, $kolom_waktu_acara, $kolom_lokasi_acara,
            $apakah_unggulan, $apakah_pengumuman_sticky
        );

        if (mysqli_stmt_execute($stmt)) {
            // ... (logika simpan kategori) ...
            $_SESSION['pesan_sukses'] = "Berita baru berhasil ditambahkan!";
        } else {
            $_SESSION['pesan_error'] = "Gagal menyimpan berita: " . mysqli_stmt_error($stmt);
            // ... (logika hapus gambar jika gagal) ...
        }
        mysqli_stmt_close($stmt);
    } else {
        $_SESSION['pesan_error'] = "Gagal menyiapkan statement SQL: " . mysqli_error($conn);
    }

    if ($conn) close_connection($conn);
    header("Location: berita_list.php");
    exit();

} else {
    // ... (redirect jika akses tidak valid) ...
}
?>