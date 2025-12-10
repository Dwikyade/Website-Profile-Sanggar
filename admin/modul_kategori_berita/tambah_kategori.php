<?php
$admin_page_title = "Tambah Kategori Berita";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';
?>

<div class="form-container">
    <h2>Tambah Kategori Berita Baru</h2>
    <p class="form-description">Buat kategori baru untuk mengelompokkan artikel berita Anda.</p>

    <?php
    if (isset($_SESSION['pesan_error_form'])) {
        echo '<div class="admin-alert alert-danger">' . htmlspecialchars($_SESSION['pesan_error_form']) . '</div>';
        unset($_SESSION['pesan_error_form']);
    }
    ?>

    <form action="proses_tambah_kategori.php" method="POST">
        <fieldset>
            <legend>Detail Kategori</legend>
            <div class="input-group">
                <label for="nama_kategori">Nama Kategori:</label>
                <input type="text" id="nama_kategori" name="nama_kategori" required placeholder="Contoh: Pementasan Seni">
            </div>
            <div class="input-group">
                <label for="slug_kategori">Slug Kategori (URL-friendly):</label>
                <input type="text" id="slug_kategori" name="slug_kategori" placeholder="Biarkan kosong untuk dibuat otomatis">
                <small>Hanya gunakan huruf kecil, angka, dan strip (-). Contoh: pementasan-seni</small>
            </div>
        </fieldset>

        <div class="submit-button-container">
            <button type="submit" name="submit_tambah_kategori">Simpan Kategori</button>
            <a href="list_kategori.php" class="btn-admin-action" style="background-color:#6c757d;">Batal</a>
        </div>
    </form>
</div>

<?php
require_once '../templates_admin/footer.php';
?>