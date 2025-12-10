<?php
// PROJECT-WEB-2025/admin/modul_kegiatan_beranda/edit_kegiatan.php
$admin_page_title = "Edit Kegiatan Beranda";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['pesan_error'] = "ID Kegiatan tidak valid.";
    header("Location: list_kegiatan.php");
    exit();
}
$id_kegiatan = (int)$_GET['id'];

// Ambil data dari tabel HalamanKegiatan
$sql = "SELECT * FROM HalamanKegiatan WHERE id_kegiatan = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_kegiatan);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$kegiatan = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$kegiatan) {
    $_SESSION['pesan_error'] = "Kegiatan tidak ditemukan.";
    header("Location: list_kegiatan.php");
    exit();
}
?>

<div class="form-container">
    <h2>Edit Kegiatan: <?php echo htmlspecialchars($kegiatan['judul']); ?></h2>

    <?php
    if (isset($_SESSION['pesan_error_form'])) {
        echo '<div class="admin-alert alert-danger">' . htmlspecialchars($_SESSION['pesan_error_form']) . '</div>';
        unset($_SESSION['pesan_error_form']);
    }
    ?>

    <form action="proses_edit_kegiatan.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_kegiatan" value="<?php echo $kegiatan['id_kegiatan']; ?>">
        <input type="hidden" name="ikon_lama" value="<?php echo htmlspecialchars($kegiatan['ikon_path']); ?>">

        <fieldset>
            <legend>Detail Kegiatan</legend>
            <div class="input-group">
                <label for="judul">Judul Kegiatan:</label>
                <input type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($kegiatan['judul']); ?>" required>
            </div>
            <div class="input-group">
                <label for="deskripsi">Deskripsi:</label>
                <textarea id="deskripsi" name="deskripsi" rows="5" required><?php echo htmlspecialchars($kegiatan['deskripsi']); ?></textarea>
            </div>
            <div class="input-group">
                <label for="ikon_path_baru">Ganti Ikon (Kosongkan jika tidak ingin ganti):</label>
                <?php if (!empty($kegiatan['ikon_path'])): ?>
                    <p>Ikon saat ini: <br><img src="<?php echo BASE_URL_PUBLIC . '/' . htmlspecialchars($kegiatan['ikon_path']); ?>" alt="Ikon Saat Ini" class="current-image-preview"></p>
                <?php endif; ?>
                <div class="file-input-wrapper">
                    <span class="file-input-button">Pilih File Ikon...</span>
                    <input type="file" id="ikon_path_baru" name="ikon_path_baru" accept="image/*" onchange="displayFileName(this, 'file-name-display-ikon')">
                </div>
                <span class="file-name-display" id="file-name-display-ikon">Tidak ada file dipilih</span>
            </div>
        </fieldset>
        
        <fieldset>
            <legend>Pengaturan Tambahan</legend>
            <div class="input-group">
                <label for="urutan_tampil">Urutan Tampil:</label>
                <input type="number" id="urutan_tampil" name="urutan_tampil" value="<?php echo htmlspecialchars($kegiatan['urutan_tampil']); ?>">
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="aktif" name="aktif" value="1" <?php echo ($kegiatan['aktif'] == 1) ? 'checked' : ''; ?>>
                <label for="aktif">Aktifkan (Tampilkan di halaman publik)</label>
            </div>
        </fieldset>

        <div class="submit-button-container">
            <button type="submit" name="submit_edit_kegiatan">Update Kegiatan</button>
            <a href="list_kegiatan.php" class="btn-admin-action" style="background-color:#6c757d;">Batal</a>
        </div>
    </form>
</div>

<?php
if ($conn) close_connection($conn);
require_once '../templates_admin/footer.php';
?>