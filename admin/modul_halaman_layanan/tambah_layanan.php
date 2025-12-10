<?php
$admin_page_title = "Tambah Layanan Beranda";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';
?>

<div class="form-container"> 
    <h2>Tambah Layanan Baru untuk Beranda</h2>

    <?php
    // Tampilkan pesan error dari sesi jika ada
    if (isset($_SESSION['pesan_error_form'])) {
        echo '<div class="admin-alert alert-danger">' . htmlspecialchars($_SESSION['pesan_error_form']) . '</div>';
        unset($_SESSION['pesan_error_form']);
    }
    ?>

    <form action="proses_tambah_layanan.php" method="POST" enctype="multipart/form-data">
        <fieldset>
            <legend>Informasi Dasar</legend>
            <div class="input-group">
                <label for="judul">Judul Layanan:</label>
                <input type="text" id="judul" name="judul" required placeholder="Contoh: Kelas Tari Tradisional">
            </div>
            <div class="input-group">
                <label for="deskripsi_singkat">Deskripsi Singkat:</label>
                <textarea id="deskripsi_singkat" name="deskripsi_singkat" rows="3" required placeholder="Tulis deskripsi singkat yang akan muncul di bawah judul..."></textarea>
            </div>
            <div class="input-group">
                <label for="ikon_path">Upload Ikon:</label>
                <div class="file-input-wrapper">
                    <span class="file-input-button">Pilih File...</span>
                    <input type="file" id="ikon_path" name="ikon_path" accept="image/*" onchange="displayFileName(this, 'file-name-display-ikon')">
                </div>
                <span class="file-name-display" id="file-name-display-ikon">Tidak ada file dipilih</span>
                <small>Rekomendasi ikon: file .png atau .svg dengan latar transparan.</small>
            </div>
        </fieldset>

        <fieldset>
            <legend>Rincian Deskripsi Panjang (Opsional)</legend>
            <div class="input-group">
                <label for="rincian_judul">Judul Rincian:</label>
                <input type="text" id="rincian_judul" name="rincian_judul" placeholder="Contoh: Detail Kelas Tari">
            </div>
            <div class="input-group">
                <label for="rincian_paragraf1">Paragraf 1 Rincian:</label>
                <textarea id="rincian_paragraf1" name="rincian_paragraf1" rows="4"></textarea>
            </div>
            <div class="input-group">
                <label for="rincian_list">Poin-poin List (Satu poin per baris):</label>
                <textarea id="rincian_list" name="rincian_list" rows="6" placeholder="Contoh:&#10;- Kelas untuk Anak-anak&#10;- Kelas untuk Dewasa&#10;- Jadwal Fleksibel"></textarea>
            </div>
            <div class="input-group">
                <label for="rincian_paragraf2">Paragraf 2 Rincian:</label>
                <textarea id="rincian_paragraf2" name="rincian_paragraf2" rows="4"></textarea>
            </div>
        </fieldset>

        <fieldset>
            <legend>Pengaturan Tambahan</legend>
            <div class="input-group">
                <label for="urutan_tampil">Urutan Tampil:</label>
                <input type="number" id="urutan_tampil" name="urutan_tampil" value="0">
                <small>Angka lebih kecil akan ditampilkan lebih dulu.</small>
            </div>
            <div class="checkbox-group">
                <input type="checkbox" id="aktif" name="aktif" value="1" checked>
                <label for="aktif">Aktifkan (Tampilkan di halaman layanan publik)</label>
            </div>
        </fieldset>

        <div class="submit-button-container">
            <button type="submit" name="submit_tambah_layanan">Simpan Layanan</button>
        </div>
    </form>
</div>

<?php
require_once '../templates_admin/footer.php';
?>