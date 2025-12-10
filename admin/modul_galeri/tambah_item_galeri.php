<?php
// PROJECT-WEB-2025/admin/modul_galeri/tambah_item_galeri.php
$admin_page_title = "Tambah Item Galeri";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

// Ambil Kategori Galeri untuk dropdown
$sql_kategori = "SELECT id_kategori_galeri, nama_kategori FROM KategoriGaleri ORDER BY nama_kategori ASC";
$result_kategori = mysqli_query($conn, $sql_kategori);
?>

<div class="form-container">
    <h2>Tambah Item Galeri Baru</h2>

    <?php
    if (isset($_SESSION['pesan_error_form'])) {
        echo '<div class="admin-alert alert-danger">' . htmlspecialchars($_SESSION['pesan_error_form']) . '</div>';
        unset($_SESSION['pesan_error_form']);
    }
    ?>

    <form action="proses_tambah_item_galeri.php" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>Informasi Dasar</legend>
            <div class="input-group">
                <label for="id_kategori_galeri">Kategori Galeri:</label>
                <select id="id_kategori_galeri" name="id_kategori_galeri" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php if ($result_kategori && mysqli_num_rows($result_kategori) > 0): ?>
                        <?php while($kat = mysqli_fetch_assoc($result_kategori)): ?>
                            <option value="<?php echo $kat['id_kategori_galeri']; ?>"><?php echo htmlspecialchars($kat['nama_kategori']); ?></option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="input-group">
                <label for="judul_item">Judul Item / Caption:</label>
                <input type="text" id="judul_item" name="judul_item" required placeholder="Contoh: Pementasan Tari Remo">
            </div>
            <div class="input-group">
                <label for="deskripsi_item">Deskripsi Tambahan (opsional):</label>
                <textarea id="deskripsi_item" name="deskripsi_item" rows="3"></textarea>
            </div>
        </fieldset>
        
        <fieldset>
            <legend>Konten Media</legend>
            <div class="input-group">
                <label for="tipe_media">Tipe Media:</label>
                <select id="tipe_media" name="tipe_media" onchange="toggleMediaType(this.value)" required>
                    <option value="Gambar" selected>Gambar</option>
                    <option value="Video">Video (YouTube/Vimeo)</option>
                </select>
            </div>

            <div id="field-video" style="display: none;">
                <div class="input-group">
                    <label for="url_video">URL Video (YouTube/Vimeo):</label>
                    <input type="url" id="url_video" name="url_video" placeholder="Contoh: https://www.youtube.com/watch?v=xxxxxxxxxxx">
                    <small>Salin dan tempel URL lengkap dari video YouTube atau Vimeo.</small>
                </div>
            </div>

            <div class="input-group">
                <label for="path_gambar_thumb">Upload Gambar Thumbnail:</label>
                <div class="file-input-wrapper">
                    <span class="file-input-button">Pilih File Thumbnail...</span>
                    <input type="file" id="path_gambar_thumb" name="path_gambar_thumb" accept="image/*" onchange="displayFileName(this, 'file-name-display-thumb')" required>
                </div>
                <span class="file-name-display" id="file-name-display-thumb">Tidak ada file dipilih</span>
                <small>Wajib diisi untuk Gambar dan Video. Untuk video, ini akan menjadi gambar sampulnya.</small>
            </div>

            <div id="field-gambar-full">
                <div class="input-group">
                    <label for="path_gambar_full">Upload Gambar Ukuran Penuh (untuk Lightbox):</label>
                    <div class="file-input-wrapper">
                        <span class="file-input-button">Pilih File Ukuran Penuh...</span>
                        <input type="file" id="path_gambar_full" name="path_gambar_full" accept="image/*" onchange="displayFileName(this, 'file-name-display-full')">
                    </div>
                    <span class="file-name-display" id="file-name-display-full">Tidak ada file dipilih</span>
                    <small>Hanya diperlukan jika Tipe Media adalah "Gambar". Rekomendasi ukuran: misal 1200x800px.</small>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Pengaturan Tambahan</legend>
            <div class="input-group">
                <label for="alt_text_gambar">Teks Alternatif Gambar (untuk aksesibilitas, opsional):</label>
                <input type="text" id="alt_text_gambar" name="alt_text_gambar" placeholder="Deskripsi singkat gambar untuk tuna netra">
            </div>
            <div class="input-group">
                <label for="urutan_tampil">Urutan Tampil:</label>
                <input type="number" id="urutan_tampil" name="urutan_tampil" value="0">
                <small>Angka lebih kecil akan ditampilkan lebih dulu.</small>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="aktif" name="aktif" value="1" checked>
                <label for="aktif">Aktifkan (Tampilkan di galeri publik)</label>
            </div>
        </fieldset>

        <div class="submit-button-container">
            <button type="submit" name="submit_tambah_item_galeri">Simpan Item Galeri</button>
        </div>
    </form>
</div>

<?php
if ($conn) close_connection($conn);
require_once '../templates_admin/footer.php';
?>