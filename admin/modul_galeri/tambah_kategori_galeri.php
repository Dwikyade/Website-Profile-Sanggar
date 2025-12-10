<?php
// PROJECT-WEB-2025/admin/modul_galeri/tambah_kategori_galeri.php
$admin_page_title = "Tambah Kategori Galeri";
require_once '../../config/database.php'; // Hanya untuk BASE_URL_ADMIN, koneksi tidak dipakai di sini
require_once '../templates_admin/header.php';
?>
<div class="form-container">
<h2>Tambah Kategori Galeri Baru</h2>

<?php
if (isset($_SESSION['pesan_error_form'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['pesan_error_form']) . '</div>';
    unset($_SESSION['pesan_error_form']);
}
?>

<form action="proses_tambah_kategori_galeri.php" method="POST">
    <div>
        <label for="nama_kategori">Nama Kategori:</label>
        <input type="text" id="nama_kategori" name="nama_kategori" required>
    </div>
    <div>
        <label for="slug_kategori">Slug Kategori (opsional, akan dibuat otomatis jika kosong):</label>
        <input type="text" id="slug_kategori" name="slug_kategori">
        <small>Contoh: seni-tari-tradisional. Hanya huruf kecil, angka, dan strip (-).</small>
    </div>
    <div>
        <label for="deskripsi_kategori">Deskripsi (opsional):</label>
        <textarea id="deskripsi_kategori" name="deskripsi_kategori" rows="4"></textarea>
    </div>
    <div>
        <label for="urutan_tampil">Urutan Tampil (angka, opsional):</label>
        <input type="number" id="urutan_tampil" name="urutan_tampil" value="0">
    </div>
    <div>
        <button type="submit" name="submit_tambah_kategori">Simpan Kategori</button>
    </div>
</form>
</div>
<?php
// Tidak ada operasi DB di halaman form ini, jadi tidak perlu close_connection
require_once '../templates_admin/footer.php';
?>