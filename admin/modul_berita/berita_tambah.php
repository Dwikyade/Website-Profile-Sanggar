<?php
// PROJECT-WEB-2025/admin/modul_berita/berita_tambah.php

// 1. Set Judul Halaman Spesifik (PENTING: SEBELUM include header.php)
$admin_page_title = "Tambah Berita Baru";

// 2. Include Config dan Header Admin
// Path dari admin/modul_berita/ ke config/ adalah ../../config/
require_once '../../config/database.php'; 
// Path dari admin/modul_berita/ ke templates_admin/ adalah ../templates_admin/
require_once '../templates_admin/header.php'; 

// 3. Ambil data yang diperlukan untuk form (misal: kategori)
// Pastikan $conn tersedia dari config/database.php
$kategori_sql = "SELECT id_kategori_berita, nama_kategori FROM KategoriBerita ORDER BY nama_kategori ASC";
$kategori_result = mysqli_query($conn, $kategori_sql);
?>
<div class="form-container">
        <h2>Tambah Berita Baru</h2> <?php
        // 4. TEMPATKAN KODE UNTUK MENAMPILKAN PESAN ERROR/SUKSES DARI SESI DI SINI
        if (isset($_SESSION['pesan_error'])) {
            // Anda bisa menambahkan kelas CSS dari admin_style.css untuk tampilan pesan yang lebih baik
            echo '<div class="admin-alert error-message">' . htmlspecialchars($_SESSION['pesan_error']) . '</div>';
            unset($_SESSION['pesan_error']); // Hapus pesan setelah ditampilkan
        }
        if (isset($_SESSION['pesan_error_form'])) { // Untuk error validasi form spesifik
            echo '<div class="admin-alert error-message">' . htmlspecialchars($_SESSION['pesan_error_form']) . '</div>';
            unset($_SESSION['pesan_error_form']);
        }
        if (isset($_SESSION['pesan_sukses'])) {
            echo '<div class="admin-alert success-message">' . htmlspecialchars($_SESSION['pesan_sukses']) . '</div>';
            unset($_SESSION['pesan_sukses']);
        }
        ?>

        <form action="berita_proses_tambah.php" method="POST" enctype="multipart/form-data">
            <div>
                <label for="judul_artikel">Judul Artikel:</label>
                <input type="text" id="judul_artikel" name="judul_artikel" required>
            </div>
            <div>
                <label for="slug_artikel">Slug Artikel (URL-friendly, biarkan kosong untuk otomatis):</label>
                <input type="text" id="slug_artikel" name="slug_artikel">
            </div>
            <div>
                <label for="kutipan_artikel">Kutipan/Ringkasan:</label>
                <textarea id="kutipan_artikel" name="kutipan_artikel" rows="3"></textarea>
            </div>
            <div>
                <label for="isi_artikel">Isi Artikel Lengkap:</label>
                <textarea id="isi_artikel" name="isi_artikel" rows="10" required></textarea>
            </div>
            <div>
                <label for="path_gambar_utama">Gambar Utama Artikel (Opsional):</label>
                <input type="file" id="path_gambar_utama" name="path_gambar_utama" accept="image/*">
            </div>
            <div>
                <label for="penulis">Penulis:</label>
                <input type="text" id="penulis" name="penulis" value="Admin Sanggar"> </div>
            <div>
                <label for="status_publikasi">Status Publikasi:</label>
                <select id="status_publikasi" name="status_publikasi">
                    <option value="draft">Draft</option>
                    <option value="terbit" selected>Terbit</option> <option value="arsip">Arsip</option>
                </select>
            </div>
            <div>
                <label for="tanggal_publikasi">Tanggal Publikasi (Kosongkan jika ingin waktu sekarang saat status 'Terbit'):</label>
                <input type="datetime-local" id="tanggal_publikasi" name="tanggal_publikasi">
            </div>
            
            <div>
                <label>Kategori Berita (Pilih satu atau lebih):</label>
                <?php if($kategori_result && mysqli_num_rows($kategori_result) > 0): ?>
                    <?php while($kat = mysqli_fetch_assoc($kategori_result)): ?>
                        <label class="checkbox-label">
                            <input type="checkbox" name="kategori_ids[]" value="<?php echo $kat['id_kategori_berita']; ?>">
                            <?php echo htmlspecialchars($kat['nama_kategori']); ?>
                        </label>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p>Belum ada kategori. 
                        <a href="<?php echo BASE_URL_ADMIN; ?>/modul_kategori_berita/tambah_kategori.php">Buat kategori baru</a>.
                    </p>
                    <?php endif; ?>
            </div>

            <fieldset style="margin-top: 20px; padding:10px; border:1px solid #ccc; max-width:600px;">
                <legend>Detail Acara (Opsional, jika berita adalah agenda)</legend>
                <div>
                    <label for="tanggal_acara_mulai">Tanggal Acara Mulai:</label>
                    <input type="date" id="tanggal_acara_mulai" name="tanggal_acara_mulai">
                </div>
                <div>
                    <label for="tanggal_acara_selesai">Tanggal Acara Selesai (Jika > 1 hari):</label>
                    <input type="date" id="tanggal_acara_selesai" name="tanggal_acara_selesai">
                </div>
                <div>
                    <label for="waktu_acara">Waktu Acara:</label>
                    <input type="time" id="waktu_acara" name="waktu_acara">
                </div>
                <div>
                    <label for="lokasi_acara">Lokasi Acara:</label>
                    <input type="text" id="lokasi_acara" name="lokasi_acara">
                </div>
            </fieldset>
            
            <div style="margin-top: 10px;">
                <label class="checkbox-label"><input type="checkbox" name="apakah_unggulan" value="1"> Tandai sebagai Unggulan?</label>
                <label class="checkbox-label"><input type="checkbox" name="apakah_pengumuman_sticky" value="1"> Jadikan Pengumuman Sticky?</label>
            </div>

            <div style="margin-top:20px;">
                <button type="submit" name="submit_tambah_berita">Simpan Berita</button>
            </div>
        </form>
</div>
<?php
// 6. Include Footer Admin
// Koneksi $conn akan ditutup di footer.php jika Anda meletakkan close_connection di sana,
// atau biarkan PHP menutupnya otomatis. Untuk konsistensi, kita bisa tutup di sini sebelum footer.
if (isset($conn) && $conn instanceof mysqli) { 
    close_connection($conn); 
}
require_once '../templates_admin/footer.php'; 
?>