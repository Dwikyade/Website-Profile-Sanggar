<?php
$admin_page_title = "Tambah Prestasi";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';
?>

<div class="form-container">
    <h2>Tambah Prestasi Baru</h2>
    <form action="proses_tambah_prestasi.php" method="POST" enctype="multipart/form-data">
        <div class="input-group">
            <label for="judul_prestasi">Nama Prestasi / Lomba:</label>
            <input type="text" id="judul_prestasi" name="judul_prestasi" required>
        </div>
        <div class="input-group">
            <label for="deskripsi_prestasi">Deskripsi Singkat:</label>
            <textarea id="deskripsi_prestasi" name="deskripsi_prestasi" rows="4"></textarea>
        </div>
        <div class="form-row">
            <div class="col-md-6 input-group">
                <label for="penyelenggara">Penyelenggara:</label>
                <input type="text" id="penyelenggara" name="penyelenggara" placeholder="Contoh: Dinas Pendidikan">
            </div>
            <div class="col-md-6 input-group">
                <label for="tingkat">Tingkat:</label>
                <input type="text" id="tingkat" name="tingkat" placeholder="Contoh: Kabupaten, Nasional">
            </div>
        </div>
        <div class="input-group">
            <label for="tanggal_prestasi">Tanggal Diraih:</label>
            <input type="date" id="tanggal_prestasi" name="tanggal_prestasi" required>
        </div>
        <div class="input-group">
            <label for="gambar_prestasi">Upload Foto (Piala/Sertifikat/Acara):</label>
            <input type="file" id="gambar_prestasi" name="gambar_prestasi" accept="image/*">
        </div>
        <div class="input-group">
            <label for="urutan_tampil">Urutan Tampil:</label>
            <input type="number" id="urutan_tampil" name="urutan_tampil" value="0">
        </div>
        <div class="checkbox-group">
            <input type="checkbox" id="aktif" name="aktif" value="1" checked>
            <label for="aktif">Aktifkan (Tampilkan di halaman publik)</label>
        </div>
        <div class="submit-button-container">
            <button type="submit" name="submit_tambah_prestasi">Simpan Prestasi</button>
        </div>
    </form>
</div>

<?php
require_once '../templates_admin/footer.php';
?>