<?php
// PROJECT-WEB-2025/admin/modul_pengaturan/edit_tentang_kami.php
$admin_page_title = "Pengaturan Halaman Tentang Kami";
require_once '../../config/database.php';
require_once '../templates_admin/header.php'; // header.php sudah membuka tag <body> dan layout utama

// Ambil konten saat ini
$sql = "SELECT * FROM HalamanTentangKami WHERE id = 1";
$result = mysqli_query($conn, $sql);
$content = mysqli_fetch_assoc($result);
if (!$content) {
    // Jika baris belum ada, buat baris kosong untuk diisi dan refresh halaman
    mysqli_query($conn, "INSERT INTO HalamanTentangKami (id) VALUES (1)");
    header("Refresh:0"); 
    exit();
}
?>

<div class="form-container">
    <h2>Pengaturan Konten Halaman "Tentang Kami"</h2>
    <p class="form-description">Ubah teks dan gambar yang akan tampil di halaman publik "Tentang Kami".</p>

    <?php
    // Tampilkan Pesan Sukses/Error dari Sesi
    if (isset($_SESSION['pesan_sukses'])) {
        echo '<div class="notif-sukses">' .
                '<div class="ikon-sukses"><i class="fas fa-check-circle"></i></div>' .
                '<div class="pesan-teks">' . htmlspecialchars($_SESSION['pesan_sukses']) . '</div>' .
                '<button type="button" class="close-btn" onclick="this.parentElement.style.display=\'none\';">&times;</button>' .
             '</div>';
        unset($_SESSION['pesan_sukses']);
    }
    if (isset($_SESSION['pesan_error'])) {
        echo '<div class="admin-alert alert-danger">' . htmlspecialchars($_SESSION['pesan_error']) . '</div>';
        unset($_SESSION['pesan_error']);
    }
    ?>

    <form action="proses_edit_tentang_kami.php" method="POST" enctype="multipart/form-data">
        <input type="hidden" name="id" value="1">
        <input type="hidden" name="welcome_image_lama" value="<?php echo htmlspecialchars($content['welcome_image_path'] ?? ''); ?>">
        <input type="hidden" name="history_image_lama" value="<?php echo htmlspecialchars($content['history_image_path'] ?? ''); ?>">

        <fieldset>
            <legend>Judul Utama Halaman</legend>
            <div class="input-group">
                <label for="main_title">Judul Utama:</label>
                <input type="text" id="main_title" name="main_title" value="<?php echo htmlspecialchars($content['main_title'] ?? ''); ?>">
            </div>
            <div class="input-group">
                <label for="main_subtitle">Sub Judul:</label>
                <input type="text" id="main_subtitle" name="main_subtitle" value="<?php echo htmlspecialchars($content['main_subtitle'] ?? ''); ?>">
            </div>
        </fieldset>

        <fieldset>
            <legend>Bagian Selamat Datang</legend>
            <div class="input-group">
                <label for="welcome_title">Judul Sambutan:</label>
                <input type="text" id="welcome_title" name="welcome_title" value="<?php echo htmlspecialchars($content['welcome_title'] ?? ''); ?>">
            </div>
            <div class="input-group">
                <label for="welcome_p1">Paragraf 1:</label>
                <textarea id="welcome_p1" name="welcome_p1" rows="4"><?php echo htmlspecialchars($content['welcome_p1'] ?? ''); ?></textarea>
            </div>
            <div class="input-group">
                <label for="welcome_p2">Paragraf 2:</label>
                <textarea id="welcome_p2" name="welcome_p2" rows="4"><?php echo htmlspecialchars($content['welcome_p2'] ?? ''); ?></textarea>
            </div>
            <div class="input-group">
                <label for="welcome_image_baru">Ganti Gambar Sambutan (opsional):</label>
                <?php if(!empty($content['welcome_image_path'])): ?>
                    <img src="<?php echo BASE_URL_PUBLIC . '/' . $content['welcome_image_path']; ?>" class="current-image-preview">
                <?php endif; ?>
                <div class="file-input-wrapper">
                    <span class="file-input-button">Pilih File...</span>
                    <input type="file" name="welcome_image_baru" accept="image/*" onchange="displayFileName(this, 'file-name-display-welcome')">
                </div>
                <span class="file-name-display" id="file-name-display-welcome">Tidak ada file dipilih</span>
            </div>
        </fieldset>
        
        <fieldset>
            <legend>Visi & Misi</legend>
            <div class="input-group">
                <label for="vision_mission_title">Judul Bagian:</label>
                <input type="text" id="vision_mission_title" name="vision_mission_title" value="<?php echo htmlspecialchars($content['vision_mission_title'] ?? ''); ?>">
            </div>
            <div class="input-group">
                <label for="vision_mission_list">Daftar Poin Visi & Misi (Satu poin per baris):</label>
                <textarea id="vision_mission_list" name="vision_mission_list" rows="8"><?php echo htmlspecialchars($content['vision_mission_list'] ?? ''); ?></textarea>
            </div>
        </fieldset>
        
        <fieldset>
            <legend>Bagian Sejarah</legend>
            <div class="input-group">
                <label for="history_title">Judul Sejarah:</label>
                <input type="text" id="history_title" name="history_title" value="<?php echo htmlspecialchars($content['history_title'] ?? ''); ?>">
            </div>
            <div class="input-group">
                <label for="history_p1">Paragraf 1:</label>
                <textarea id="history_p1" name="history_p1" rows="6"><?php echo htmlspecialchars($content['history_p1'] ?? ''); ?></textarea>
            </div>
            <div class="input-group">
                <label for="history_p2">Paragraf 2:</label>
                <textarea id="history_p2" name="history_p2" rows="6"><?php echo htmlspecialchars($content['history_p2'] ?? ''); ?></textarea>
            </div>
            <div class="input-group">
                <label for="history_image_baru">Ganti Gambar Sejarah (opsional):</label>
                <?php if(!empty($content['history_image_path'])): ?>
                    <img src="<?php echo BASE_URL_PUBLIC . '/' . $content['history_image_path']; ?>" class="current-image-preview">
                <?php endif; ?>
                <div class="file-input-wrapper">
                    <span class="file-input-button">Pilih File...</span>
                    <input type="file" name="history_image_baru" accept="image/*" onchange="displayFileName(this, 'file-name-display-history')">
                </div>
                <span class="file-name-display" id="file-name-display-history">Tidak ada file dipilih</span>
            </div>
        </fieldset>

        <fieldset>
            <legend>Bagian Ajakan Bergabung (Call to Action)</legend>
            <div class="input-group">
                <label for="cta_title">Judul Ajakan:</label>
                <input type="text" id="cta_title" name="cta_title" value="<?php echo htmlspecialchars($content['cta_title'] ?? ''); ?>">
            </div>
            <div class="input-group">
                <label for="cta_subtitle">Teks Ajakan:</label>
                <textarea id="cta_subtitle" name="cta_subtitle" rows="3"><?php echo htmlspecialchars($content['cta_subtitle'] ?? ''); ?></textarea>
            </div>
        </fieldset>

        <div class="submit-button-container">
            <button type="submit" name="submit_update_tentang_kami">Simpan Perubahan</button>
        </div>
    </form>
</div>

<?php
if ($conn) close_connection($conn);
require_once '../templates_admin/footer.php';
?>