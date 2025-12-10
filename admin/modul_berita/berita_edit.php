

<?php
require_once '../../config/database.php';
require_once '../templates_admin/header.php'; // Naik 1 level ke admin, lalu ke templates_admin

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: berita_list.php");
    exit();
}
$id_artikel = (int)$_GET['id'];

// Ambil data artikel yang akan diedit
$sql_artikel = "SELECT * FROM artikelberita WHERE id_artikel = $id_artikel";
$result_artikel = mysqli_query($conn, $sql_artikel);
if (mysqli_num_rows($result_artikel) == 0) {
    echo "Artikel tidak ditemukan.";
    exit();
}
$artikel = mysqli_fetch_assoc($result_artikel);

// Ambil semua kategori
$kategori_sql = "SELECT id_kategori_berita, nama_kategori FROM KategoriBerita ORDER BY nama_kategori ASC";
$kategori_result = mysqli_query($conn, $kategori_sql);

// Ambil kategori yang sudah terpilih untuk artikel ini
$selected_kategori_ids = [];
$map_sql = "SELECT id_kategori_berita FROM PetaArtikelKategori WHERE id_artikel = $id_artikel";
$map_result = mysqli_query($conn, $map_sql);
while ($map_row = mysqli_fetch_assoc($map_result)) {
    $selected_kategori_ids[] = $map_row['id_kategori_berita'];
}

// Konversi tanggal untuk input datetime-local dan date
$tanggal_publikasi_val = $artikel['tanggal_publikasi'] ? date('Y-m-d\TH:i', strtotime($artikel['tanggal_publikasi'])) : '';
$tanggal_acara_mulai_val = $artikel['tanggal_acara_mulai'] ? date('Y-m-d', strtotime($artikel['tanggal_acara_mulai'])) : '';
$tanggal_acara_selesai_val = $artikel['tanggal_acara_selesai'] ? date('Y-m-d', strtotime($artikel['tanggal_acara_selesai'])) : '';

?>
<!DOCTYPE html>
<html lang="id">
    <head>
        <meta charset="UTF-8">
        <title>Admin - Edit Berita</title>
        <style>
            /* ... (CSS yang sama dengan berita_tambah.php) ... */
            .current-image { max-width: 200px; max-height: 200px; display: block; margin-bottom: 10px; }
            </style>
</head>
<body>
    
<div class="form-container">
    <h1>Edit Berita</h1>
    <form action="berita_proses_edit.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_artikel" value="<?php echo $artikel['id_artikel']; ?>">
        
        <div>
            <label for="judul_artikel">Judul Artikel:</label>
            <input type="text" id="judul_artikel" name="judul_artikel" value="<?php echo htmlspecialchars($artikel['judul_artikel']); ?>" required>
        </div>
        <div>
            <label for="slug_artikel">Slug Artikel:</label>
            <input type="text" id="slug_artikel" name="slug_artikel" value="<?php echo htmlspecialchars($artikel['slug_artikel']); ?>" required>
        </div>
        <div>
            <label for="kutipan_artikel">Kutipan/Ringkasan:</label>
            <textarea id="kutipan_artikel" name="kutipan_artikel"><?php echo htmlspecialchars($artikel['kutipan_artikel']); ?></textarea>
        </div>
        <div>
            <label for="isi_artikel">Isi Artikel Lengkap:</label>
            <textarea id="isi_artikel" name="isi_artikel" required><?php echo htmlspecialchars($artikel['isi_artikel']); ?></textarea>
        </div>
        <div>
            <label for="path_gambar_utama">Ganti Gambar Utama (Kosongkan jika tidak ingin ganti):</label>
            <?php if ($artikel['path_gambar_utama']): ?>
                <p>Gambar saat ini: <img src="../<?php echo htmlspecialchars($artikel['path_gambar_utama']); ?>" alt="Gambar Utama" class="current-image"></p>
                <input type="hidden" name="gambar_lama" value="<?php echo htmlspecialchars($artikel['path_gambar_utama']); ?>">
            <?php endif; ?>
            <input type="file" id="path_gambar_utama" name="path_gambar_utama_baru" accept="image/*">
        </div>
        <div>
            <label for="penulis">Penulis:</label>
            <input type="text" id="penulis" name="penulis" value="<?php echo htmlspecialchars($artikel['penulis']); ?>">
        </div>
        <div>
            <label for="status_publikasi">Status Publikasi:</label>
            <select id="status_publikasi" name="status_publikasi">
                <option value="draft" <?php echo ($artikel['status_publikasi'] == 'draft') ? 'selected' : ''; ?>>Draft</option>
                <option value="terbit" <?php echo ($artikel['status_publikasi'] == 'terbit') ? 'selected' : ''; ?>>Terbit</option>
                <option value="arsip" <?php echo ($artikel['status_publikasi'] == 'arsip') ? 'selected' : ''; ?>>Arsip</option>
            </select>
        </div>
        <div>
            <label for="tanggal_publikasi">Tanggal Publikasi:</label>
            <input type="datetime-local" id="tanggal_publikasi" name="tanggal_publikasi" value="<?php echo $tanggal_publikasi_val; ?>">
        </div>

        <div>
            <label>Kategori Berita:</label>
            <?php if(mysqli_num_rows($kategori_result) > 0): ?>
                <?php mysqli_data_seek($kategori_result, 0); // Reset pointer result set ?>
                <?php while($kat = mysqli_fetch_assoc($kategori_result)): ?>
                    <label class="checkbox-label">
                        <input type="checkbox" name="kategori_ids[]" value="<?php echo $kat['id_kategori_berita']; ?>" <?php echo in_array($kat['id_kategori_berita'], $selected_kategori_ids) ? 'checked' : ''; ?>>
                        <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                    </label>
                <?php endwhile; ?>
            <?php endif; ?>
        </div>

        <fieldset style="margin-top: 20px; padding:10px; border:1px solid #ccc; max-width:600px;">
            <legend>Detail Acara (Opsional)</legend>
            <div>
                <label for="tanggal_acara_mulai">Tanggal Acara Mulai:</label>
                <input type="date" id="tanggal_acara_mulai" name="tanggal_acara_mulai" value="<?php echo $tanggal_acara_mulai_val; ?>">
            </div>
            <div>
                <label for="tanggal_acara_selesai">Tanggal Acara Selesai:</label>
                <input type="date" id="tanggal_acara_selesai" name="tanggal_acara_selesai" value="<?php echo $tanggal_acara_selesai_val; ?>">
            </div>
            <div>
                <label for="waktu_acara">Waktu Acara:</label>
                <input type="time" id="waktu_acara" name="waktu_acara" value="<?php echo htmlspecialchars($artikel['waktu_acara']); ?>">
            </div>
            <div>
                <label for="lokasi_acara">Lokasi Acara:</label>
                <input type="text" id="lokasi_acara" name="lokasi_acara" value="<?php echo htmlspecialchars($artikel['lokasi_acara']); ?>">
            </div>
        </fieldset>
        
        <div style="margin-top: 10px;">
            <label class="checkbox-label"><input type="checkbox" name="apakah_unggulan" value="1" <?php echo ($artikel['apakah_unggulan'] == 1) ? 'checked' : ''; ?>> Tandai sebagai Unggulan?</label>
            <label class="checkbox-label"><input type="checkbox" name="apakah_pengumuman_sticky" value="1" <?php echo ($artikel['apakah_pengumuman_sticky'] == 1) ? 'checked' : ''; ?>> Jadikan Pengumuman Sticky?</label>
        </div>

        <div style="margin-top:20px;">
            <button type="submit" name="submit">Update Berita</button>
        </div>
    </form>
</div>
    <?php close_connection($conn); ?>
</body>
</html>
