<?php
// PROJECT-WEB-2025/admin/modul_galeri/edit_item_galeri.php
$admin_page_title = "Edit Item Galeri";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['pesan_error'] = "ID Item Galeri tidak valid.";
    header("Location: list_item_galeri.php");
    exit();
}
$id_item_galeri = (int)$_GET['id'];

// Ambil data item yang akan diedit
$sql_item = "SELECT * FROM ItemGaleri WHERE id_item_galeri = ?";
$stmt_item = mysqli_prepare($conn, $sql_item);
mysqli_stmt_bind_param($stmt_item, "i", $id_item_galeri);
mysqli_stmt_execute($stmt_item);
$result_item = mysqli_stmt_get_result($stmt_item);
$item = mysqli_fetch_assoc($result_item);
mysqli_stmt_close($stmt_item);

if (!$item) {
    $_SESSION['pesan_error'] = "Item galeri tidak ditemukan.";
    header("Location: list_item_galeri.php");
    exit();
}

// Ambil Kategori Galeri untuk dropdown
$sql_kategori = "SELECT id_kategori_galeri, nama_kategori FROM KategoriGaleri ORDER BY nama_kategori ASC";
$result_kategori = mysqli_query($conn, $sql_kategori);
?>

<div class="form-container">
    <h2>Edit Item Galeri: <?php echo htmlspecialchars($item['judul_item']); ?></h2>

    <?php
    if (isset($_SESSION['pesan_error_form'])) {
        echo '<div class="admin-alert alert-danger">' . htmlspecialchars($_SESSION['pesan_error_form']) . '</div>';
        unset($_SESSION['pesan_error_form']);
    }
    ?>

    <form action="proses_edit_item_galeri.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_item_galeri" value="<?php echo $item['id_item_galeri']; ?>">
        <input type="hidden" name="gambar_thumb_lama" value="<?php echo htmlspecialchars($item['path_gambar_thumb']); ?>">
        <input type="hidden" name="gambar_full_lama" value="<?php echo htmlspecialchars($item['path_gambar_full']); ?>">

        <fieldset>
            <legend>Informasi Dasar</legend>
            <div class="input-group">
                <label for="id_kategori_galeri">Kategori Galeri:</label>
                <select id="id_kategori_galeri" name="id_kategori_galeri" required>
                    <option value="">-- Pilih Kategori --</option>
                    <?php if ($result_kategori && mysqli_num_rows($result_kategori) > 0): ?>
                        <?php mysqli_data_seek($result_kategori, 0); // Reset pointer jika perlu ?>
                        <?php while($kat = mysqli_fetch_assoc($result_kategori)): ?>
                            <option value="<?php echo $kat['id_kategori_galeri']; ?>" <?php echo ($kat['id_kategori_galeri'] == $item['id_kategori_galeri']) ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                            </option>
                        <?php endwhile; ?>
                    <?php endif; ?>
                </select>
            </div>
            <div class="input-group">
                <label for="judul_item">Judul Item/Caption:</label>
                <input type="text" id="judul_item" name="judul_item" value="<?php echo htmlspecialchars($item['judul_item']); ?>" required>
            </div>
            <div class="input-group">
                <label for="deskripsi_item">Deskripsi Tambahan (opsional):</label>
                <textarea id="deskripsi_item" name="deskripsi_item" rows="3"><?php echo htmlspecialchars($item['deskripsi_item']); ?></textarea>
            </div>
        </fieldset>

        <fieldset>
            <legend>Konten Media</legend>
            <div class="input-group">
                <label for="tipe_media">Tipe Media:</label>
                <select id="tipe_media" name="tipe_media" onchange="toggleMediaType(this.value)" required>
                    <option value="Gambar" <?php echo ($item['tipe_media'] == 'Gambar') ? 'selected' : ''; ?>>Gambar</option>
                    <option value="Video" <?php echo ($item['tipe_media'] == 'Video') ? 'selected' : ''; ?>>Video (YouTube/Vimeo)</option>
                </select>
            </div>

            <div id="field-video" style="display: none;">
                <div class="input-group">
                    <label for="url_video">URL Video (YouTube/Vimeo):</label>
                    <input type="url" id="url_video" name="url_video" value="<?php echo htmlspecialchars($item['url_video']); ?>" placeholder="Contoh: https://www.youtube.com/watch?v=xxxxxxxxxxx">
                </div>
            </div>

            <div class="input-group">
                <label for="path_gambar_thumb_baru">Ganti Gambar Thumbnail (Kosongkan jika tidak ingin ganti):</label>
                <?php if (!empty($item['path_gambar_thumb'])): ?>
                    <p>Thumbnail saat ini: <br><img src="<?php echo BASE_URL_PUBLIC . '/' . htmlspecialchars($item['path_gambar_thumb']); ?>" alt="Thumb Saat Ini" class="current-image-preview"></p>
                <?php endif; ?>
                <div class="file-input-wrapper">
                    <span class="file-input-button">Pilih File Thumbnail Baru...</span>
                    <input type="file" id="path_gambar_thumb_baru" name="path_gambar_thumb_baru" accept="image/*" onchange="displayFileName(this, 'file-name-display-thumb')">
                </div>
                <span class="file-name-display" id="file-name-display-thumb">Tidak ada file dipilih</span>
                <small>Thumbnail ini akan menjadi gambar sampul untuk video.</small>
            </div>

            <div id="field-gambar-full">
                <div class="input-group">
                    <label for="path_gambar_full_baru">Ganti Gambar Ukuran Penuh (Kosongkan jika tidak ingin ganti):</label>
                    <?php if (!empty($item['path_gambar_full'])): ?>
                        <p>Gambar penuh saat ini: <br><img src="<?php echo BASE_URL_PUBLIC . '/' . htmlspecialchars($item['path_gambar_full']); ?>" alt="Full Saat Ini" class="current-image-preview"></p>
                    <?php endif; ?>
                    <div class="file-input-wrapper">
                        <span class="file-input-button">Pilih File Ukuran Penuh...</span>
                        <input type="file" id="path_gambar_full_baru" name="path_gambar_full_baru" accept="image/*" onchange="displayFileName(this, 'file-name-display-full')">
                    </div>
                    <span class="file-name-display" id="file-name-display-full">Tidak ada file dipilih</span>
                    <small>Hanya diperlukan jika Tipe Media adalah "Gambar".</small>
                </div>
            </div>
        </fieldset>

        <fieldset>
            <legend>Pengaturan Tambahan</legend>
            <div class="input-group">
                <label for="alt_text_gambar">Teks Alternatif Gambar:</label>
                <input type="text" id="alt_text_gambar" name="alt_text_gambar" value="<?php echo htmlspecialchars($item['alt_text_gambar']); ?>">
            </div>
            <div class="input-group">
                <label for="urutan_tampil">Urutan Tampil:</label>
                <input type="number" id="urutan_tampil" name="urutan_tampil" value="<?php echo htmlspecialchars($item['urutan_tampil']); ?>">
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="aktif" name="aktif" value="1" <?php echo ($item['aktif'] == 1) ? 'checked' : ''; ?>>
                <label for="aktif">Aktifkan (Tampilkan di galeri publik)</label>
            </div>
        </fieldset>

        <div class="submit-button-container">
            <button type="submit" name="submit_edit_item_galeri">Update Item Galeri</button>
        </div>
    </form>
</div>

<script>
function toggleMediaType(type) {
    const fieldVideo = document.getElementById('field-video');
    const fieldGambarFull = document.getElementById('field-gambar-full');
    const inputUrlVideo = document.getElementById('url_video');

    if (type === 'Video') {
        fieldVideo.style.display = 'block';
        fieldGambarFull.style.display = 'none';
        inputUrlVideo.required = true; // URL video wajib untuk video
    } else { // Jika 'Gambar'
        fieldVideo.style.display = 'none';
        fieldGambarFull.style.display = 'block';
        inputUrlVideo.required = false;
    }
}

// Panggil sekali saat halaman dimuat untuk menyesuaikan dengan data yang ada
document.addEventListener('DOMContentLoaded', function() {
    toggleMediaType(document.getElementById('tipe_media').value);
});
</script>

<?php
if ($conn) close_connection($conn);
require_once '../templates_admin/footer.php';
?>