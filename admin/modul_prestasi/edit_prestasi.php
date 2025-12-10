<?php
$admin_page_title = "Edit Prestasi";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

// Validasi ID dari URL
if (!isset($_GET['id']) || !filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
    $_SESSION['pesan_error'] = "ID Prestasi tidak valid.";
    header("Location: list_prestasi.php");
    exit();
}
$id_prestasi = (int)$_GET['id'];

// Ambil data prestasi yang akan diedit
$sql = "SELECT * FROM Prestasi WHERE id_prestasi = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_prestasi);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$prestasi = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

// Jika data tidak ditemukan, redirect kembali
if (!$prestasi) {
    $_SESSION['pesan_error'] = "Data prestasi tidak ditemukan.";
    header("Location: list_prestasi.php");
    exit();
}
?>

<div class="form-container">
    <h2>Edit Prestasi: <?php echo htmlspecialchars($prestasi['judul_prestasi']); ?></h2>

    <?php
    // Tampilkan pesan error form jika ada
    if (isset($_SESSION['pesan_error_form'])) {
        echo '<div class="admin-alert alert-danger">' . htmlspecialchars($_SESSION['pesan_error_form']) . '</div>';
        unset($_SESSION['pesan_error_form']);
    }
    ?>

    <form action="proses_edit_prestasi.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id_prestasi" value="<?php echo $prestasi['id_prestasi']; ?>">
        <input type="hidden" name="gambar_prestasi_lama" value="<?php echo htmlspecialchars($prestasi['gambar_prestasi']); ?>">

        <div class="input-group">
            <label for="judul_prestasi">Nama Prestasi / Lomba:</label>
            <input type="text" id="judul_prestasi" name="judul_prestasi" value="<?php echo htmlspecialchars($prestasi['judul_prestasi']); ?>" required>
        </div>
        <div class="input-group">
            <label for="deskripsi_prestasi">Deskripsi Singkat:</label>
            <textarea id="deskripsi_prestasi" name="deskripsi_prestasi" rows="4"><?php echo htmlspecialchars($prestasi['deskripsi_prestasi']); ?></textarea>
        </div>
        <div class="form-row">
            <div class="col-md-6 input-group">
                <label for="penyelenggara">Penyelenggara:</label>
                <input type="text" id="penyelenggara" name="penyelenggara" value="<?php echo htmlspecialchars($prestasi['penyelenggara']); ?>">
            </div>
            <div class="col-md-6 input-group">
                <label for="tingkat">Tingkat:</label>
                <input type="text" id="tingkat" name="tingkat" value="<?php echo htmlspecialchars($prestasi['tingkat']); ?>">
            </div>
        </div>
        <div class="input-group">
            <label for="tanggal_prestasi">Tanggal Diraih:</label>
            <input type="date" id="tanggal_prestasi" name="tanggal_prestasi" value="<?php echo htmlspecialchars($prestasi['tanggal_prestasi']); ?>" required>
        </div>
        <div class="input-group">
            <label for="gambar_prestasi_baru">Ganti Foto (Kosongkan jika tidak ingin ganti):</label>
            <?php if (!empty($prestasi['gambar_prestasi'])): ?>
                <p>Foto saat ini: <br><img src="<?php echo BASE_URL_PUBLIC . '/' . htmlspecialchars($prestasi['gambar_prestasi']); ?>" alt="Foto Prestasi" style="max-width: 200px; height: auto; margin-bottom:10px; border-radius:4px;"></p>
            <?php endif; ?>
            <input type="file" id="gambar_prestasi_baru" name="gambar_prestasi_baru" accept="image/*">
        </div>
        <div class="input-group">
            <label for="urutan_tampil">Urutan Tampil:</label>
            <input type="number" id="urutan_tampil" name="urutan_tampil" value="<?php echo htmlspecialchars($prestasi['urutan_tampil']); ?>">
        </div>
        <div class="checkbox-group">
            <input type="checkbox" id="aktif" name="aktif" value="1" <?php echo ($prestasi['aktif'] == 1) ? 'checked' : ''; ?>>
            <label for="aktif">Aktifkan (Tampilkan di halaman publik)</label>
        </div>
        <div class="submit-button-container">
            <button type="submit" name="submit_edit_prestasi">Update Prestasi</button>
        </div>
    </form>
</div>

<?php
if ($conn) close_connection($conn);
require_once '../templates_admin/footer.php';
?>