<?php
// PROJECT-WEB-2025/admin/modul_galeri/edit_kategori_galeri.php
$admin_page_title = "Edit Kategori Galeri";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['pesan_error'] = "ID Kategori tidak valid.";
    header("Location: list_kategori_galeri.php");
    exit();
}

$id_kategori = (int)$_GET['id'];
$sql = "SELECT * FROM KategoriGaleri WHERE id_kategori_galeri = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_kategori);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$kategori = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$kategori) {
    $_SESSION['pesan_error'] = "Kategori tidak ditemukan.";
    header("Location: list_kategori_galeri.php");
    exit();
}
?>
<div class="form-container">
<h2>Edit Kategori Galeri: <?php echo htmlspecialchars($kategori['nama_kategori']); ?></h2>

<?php
if (isset($_SESSION['pesan_error_form'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['pesan_error_form']) . '</div>';
    unset($_SESSION['pesan_error_form']);
}
?>

<form action="proses_edit_kategori_galeri.php" method="POST">
    <input type="hidden" name="id_kategori_galeri" value="<?php echo $kategori['id_kategori_galeri']; ?>">
    <div>
        <label for="nama_kategori">Nama Kategori:</label>
        <input type="text" id="nama_kategori" name="nama_kategori" value="<?php echo htmlspecialchars($kategori['nama_kategori']); ?>" required>
    </div>
    <div>
        <label for="slug_kategori">Slug Kategori:</label>
        <input type="text" id="slug_kategori" name="slug_kategori" value="<?php echo htmlspecialchars($kategori['slug_kategori']); ?>" required>
        <small>Contoh: seni-tari-tradisional. Hanya huruf kecil, angka, dan strip (-).</small>
    </div>
    <div>
        <label for="deskripsi_kategori">Deskripsi (opsional):</label>
        <textarea id="deskripsi_kategori" name="deskripsi_kategori" rows="4"><?php echo htmlspecialchars($kategori['deskripsi_kategori']); ?></textarea>
    </div>
    <div>
        <label for="urutan_tampil">Urutan Tampil (angka, opsional):</label>
        <input type="number" id="urutan_tampil" name="urutan_tampil" value="<?php echo htmlspecialchars($kategori['urutan_tampil']); ?>">
    </div>
    <div>
        <button type="submit" name="submit_edit_kategori">Update Kategori</button>
    </div>
</form>
</div>

<?php
if ($conn) close_connection($conn);
require_once '../templates_admin/footer.php';
?>