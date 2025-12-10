
<?php
require_once '../../config/database.php';
require_once '../templates_admin/header.php'; // Naik 1 level ke admin, lalu ke templates_admin

if (isset($_POST['submit'])) {
    $id_artikel = (int)$_POST['id_artikel'];
    $judul_artikel = mysqli_real_escape_string($conn, $_POST['judul_artikel']);
    $slug_artikel = mysqli_real_escape_string($conn, $_POST['slug_artikel']);
    $kutipan_artikel = mysqli_real_escape_string($conn, $_POST['kutipan_artikel']);
    $isi_artikel = mysqli_real_escape_string($conn, $_POST['isi_artikel']);
    $penulis = mysqli_real_escape_string($conn, $_POST['penulis']);
    $status_publikasi = mysqli_real_escape_string($conn, $_POST['status_publikasi']);
    
    $tanggal_publikasi = !empty($_POST['tanggal_publikasi']) ? "'" . mysqli_real_escape_string($conn, $_POST['tanggal_publikasi']) . "'" : "NULL";
    if ($status_publikasi == 'terbit' && $tanggal_publikasi == "NULL") {
         // Ambil tanggal publikasi lama jika ada, atau set NOW()
        $old_date_sql = "SELECT tanggal_publikasi FROM artikelberita WHERE id_artikel = $id_artikel";
        $old_date_res = mysqli_query($conn, $old_date_sql);
        $old_date_row = mysqli_fetch_assoc($old_date_res);
        $tanggal_publikasi = $old_date_row['tanggal_publikasi'] ? "'" . $old_date_row['tanggal_publikasi'] . "'" : "NOW()";
    }


    $tanggal_acara_mulai = !empty($_POST['tanggal_acara_mulai']) ? "'" . mysqli_real_escape_string($conn, $_POST['tanggal_acara_mulai']) . "'" : "NULL";
    $tanggal_acara_selesai = !empty($_POST['tanggal_acara_selesai']) ? "'" . mysqli_real_escape_string($conn, $_POST['tanggal_acara_selesai']) . "'" : "NULL";
    $waktu_acara = !empty($_POST['waktu_acara']) ? "'" . mysqli_real_escape_string($conn, $_POST['waktu_acara']) . "'" : "NULL";
    $lokasi_acara = !empty($_POST['lokasi_acara']) ? "'" . mysqli_real_escape_string($conn, $_POST['lokasi_acara']) . "'" : "NULL";

    $apakah_unggulan = isset($_POST['apakah_unggulan']) ? 1 : 0;
    $apakah_pengumuman_sticky = isset($_POST['apakah_pengumuman_sticky']) ? 1 : 0;

    $path_gambar_utama_sql_update = "";
    if (isset($_FILES['path_gambar_utama_baru']) && $_FILES['path_gambar_utama_baru']['error'] == 0) {
        // Hapus gambar lama jika ada
        if (!empty($_POST['gambar_lama']) && file_exists("../" . $_POST['gambar_lama'])) {
            unlink("../" . $_POST['gambar_lama']);
        }

        $target_dir = "../public/images/berita/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $image_name = time() . '_' . basename($_FILES["path_gambar_utama_baru"]["name"]);
        $target_file = $target_dir . $image_name;
        if (move_uploaded_file($_FILES["path_gambar_utama_baru"]["tmp_name"], $target_file)) {
            $path_gambar_baru = "public/images/berita/" . $image_name;
            $path_gambar_utama_sql_update = ", path_gambar_utama = '" . mysqli_real_escape_string($conn, $path_gambar_baru) . "'";
        } else {
            echo "Maaf, terjadi error saat mengupload file gambar baru Anda.";
            // Handle error
        }
    }

    // Query SQL untuk update
    // Penting: Gunakan prepared statements di produksi
    $sql = "UPDATE artikelberita SET 
                judul_artikel = '$judul_artikel', 
                slug_artikel = '$slug_artikel', 
                kutipan_artikel = '$kutipan_artikel', 
                isi_artikel = '$isi_artikel', 
                penulis = '$penulis', 
                status_publikasi = '$status_publikasi', 
                tanggal_publikasi = $tanggal_publikasi,
                tanggal_acara_mulai = $tanggal_acara_mulai,
                tanggal_acara_selesai = $tanggal_acara_selesai,
                waktu_acara = $waktu_acara,
                lokasi_acara = $lokasi_acara,
                apakah_unggulan = $apakah_unggulan,
                apakah_pengumuman_sticky = $apakah_pengumuman_sticky
                $path_gambar_utama_sql_update 
            WHERE id_artikel = $id_artikel";

    if (mysqli_query($conn, $sql)) {
        // Update Kategori (hapus semua yang lama, insert yang baru)
        $delete_map_sql = "DELETE FROM PetaArtikelKategori WHERE id_artikel = $id_artikel";
        mysqli_query($conn, $delete_map_sql);

        if (!empty($_POST['kategori_ids']) && is_array($_POST['kategori_ids'])) {
            foreach ($_POST['kategori_ids'] as $id_kategori) {
                $id_kategori_safe = (int)$id_kategori;
                $map_sql = "INSERT INTO PetaArtikelKategori (id_artikel, id_kategori_berita) VALUES ($id_artikel, $id_kategori_safe)";
                mysqli_query($conn, $map_sql);
            }
        }
        echo "Berita berhasil diperbarui. <a href='berita_list.php'>Kembali ke Daftar Berita</a>";
    } else {
        echo "Error updating record: " . mysqli_error($conn);
    }

    close_connection($conn);
} else {
    header("Location: berita_list.php");
    exit();
}
?>