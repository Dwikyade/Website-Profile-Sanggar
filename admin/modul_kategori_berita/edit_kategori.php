<?php
$admin_page_title = "Edit Kategori Berita";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

if (!isset($_GET['id'])) { /* ... kode redirect jika id tidak ada ... */ }
$id_kategori = (int)$_GET['id'];
$sql = "SELECT * FROM KategoriBerita WHERE id_kategori_berita = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_kategori);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$kategori = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
if (!$kategori) { /* ... kode redirect jika data tidak ditemukan ... */ }
?>
<div class="form-container">
    <h2>Edit Kategori Berita</h2>
    <form action="proses_edit_kategori.php" method="POST">
        <input type="hidden" name="id_kategori_berita" value="<?php echo $kategori['id_kategori_berita']; ?>">
        <fieldset>
            <legend>Detail Kategori</legend>
            <div class="input-group">
                <label for="nama_kategori">Nama Kategori:</label>
                <input type="text" id="nama_kategori" name="nama_kategori" value="<?php echo htmlspecialchars($kategori['nama_kategori']); ?>" required>
            </div>
            <div class="input-group">
                <label for="slug_kategori">Slug Kategori:</label>
                <input type="text" id="slug_kategori" name="slug_kategori" value="<?php echo htmlspecialchars($kategori['slug_kategori']); ?>" required>
            </div>
        </fieldset>
        <div class="submit-button-container">
            <button type="submit" name="submit_edit_kategori">Update Kategori</button>
            <a href="list_kategori.php" class="btn-admin-action" style="background-color:#6c757d;">Batal</a>
        </div>
    </form>
</div>
<?php require_once '../templates_admin/footer.php'; ?>