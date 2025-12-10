<?php
$admin_page_title = "Edit Layanan";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['pesan_error'] = "ID Layanan tidak valid.";
    header("Location: list_layanan.php");
    exit();
}
$id_layanan = (int)$_GET['id'];

$sql = "SELECT * FROM HalamanLayanan WHERE id_layanan = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_layanan);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$layanan = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$layanan) {
    $_SESSION['pesan_error'] = "Layanan tidak ditemukan.";
    header("Location: list_layanan.php");
    exit();
}
?>
<div class="form-container">
<h2>Edit Layanan: <?php echo htmlspecialchars($layanan['judul']); ?></h2>

<?php
if (isset($_SESSION['pesan_error_form'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['pesan_error_form']) . '</div>';
    unset($_SESSION['pesan_error_form']);
}
?>

<form action="proses_edit_layanan.php" method="POST" enctype="multipart/form-data">
    <input type="hidden" name="id_layanan" value="<?php echo $layanan['id_layanan']; ?>">
    <input type="hidden" name="ikon_lama" value="<?php echo htmlspecialchars($layanan['ikon_path']); ?>">
    
    <div>
        <label for="judul">Judul Layanan:</label>
        <input type="text" id="judul" name="judul" value="<?php echo htmlspecialchars($layanan['judul']); ?>" required>
    </div>
    <div>
        <label for="deskripsi_singkat">Deskripsi Singkat:</label>
        <textarea id="deskripsi_singkat" name="deskripsi_singkat" rows="3" required><?php echo htmlspecialchars($layanan['deskripsi_singkat']); ?></textarea>
    </div>
    <div>
        <label for="ikon_path_baru">Ganti Ikon (Kosongkan jika tidak ingin ganti):</label>
        <?php if (!empty($layanan['ikon_path'])): ?>
            <p>Ikon saat ini: <br><img src="<?php echo BASE_URL_PUBLIC . '/' . htmlspecialchars($layanan['ikon_path']); ?>" alt="Ikon Saat Ini" style="max-width: 80px; height: auto; margin-bottom:10px; background-color:#f0f0f0; padding:5px; border-radius:4px;"></p>
        <?php endif; ?>
        <input type="file" id="ikon_path_baru" name="ikon_path_baru" accept="image/*">
    </div>
    
    <fieldset style="margin-top: 20px; padding:10px; border:1px solid #ccc; max-width:600px;">
        <legend>Rincian Deskripsi Panjang</legend>
        <div>
            <label for="rincian_judul">Judul Rincian:</label>
            <input type="text" id="rincian_judul" name="rincian_judul" value="<?php echo htmlspecialchars($layanan['rincian_judul']); ?>">
        </div>
        <div>
            <label for="rincian_paragraf1">Paragraf 1 Rincian:</label>
            <textarea id="rincian_paragraf1" name="rincian_paragraf1" rows="4"><?php echo htmlspecialchars($layanan['rincian_paragraf1']); ?></textarea>
        </div>
        <div>
            <label for="rincian_list">Poin-poin List (Satu poin per baris):</label>
            <textarea id="rincian_list" name="rincian_list" rows="6"><?php echo htmlspecialchars($layanan['rincian_list']); ?></textarea>
        </div>
        <div>
            <label for="rincian_paragraf2">Paragraf 2 Rincian:</label>
            <textarea id="rincian_paragraf2" name="rincian_paragraf2" rows="4"><?php echo htmlspecialchars($layanan['rincian_paragraf2']); ?></textarea>
        </div>
    </fieldset>

    <div>
        <label for="urutan_tampil">Urutan Tampil:</label>
        <input type="number" id="urutan_tampil" name="urutan_tampil" value="<?php echo htmlspecialchars($layanan['urutan_tampil']); ?>">
    </div>
    <div>
        <label class="checkbox-label"><input type="checkbox" name="aktif" value="1" <?php echo ($layanan['aktif'] == 1) ? 'checked' : ''; ?>> Aktifkan</label>
    </div>
    <div>
        <button type="submit" name="submit_edit_layanan">Update Layanan</button>
    </div>
</form>
</div>
<?php
if ($conn) close_connection($conn);
require_once '../templates_admin/footer.php';
?>