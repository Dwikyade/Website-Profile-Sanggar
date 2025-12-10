<?php
$admin_page_title = "Detail Pendaftar Murid";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) { /* ... kode redirect jika id tidak ada ... */ }
$id_pendaftar = (int)$_GET['id'];

$sql = "SELECT * FROM PendaftarMurid WHERE id_pendaftar = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_pendaftar);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pendaftar = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$pendaftar) { /* ... kode redirect jika data tidak ditemukan ... */ }
?>

<style>
    .detail-container { max-width: 800px; margin: auto; }
    .detail-section { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); margin-bottom: 20px;}
    .detail-section h3 { border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; margin-top: 0; }
    .detail-grid { display: grid; grid-template-columns: 180px 1fr; gap: 10px 20px; }
    .detail-grid dt { font-weight: 600; color: #555; }
    .detail-grid dd { margin: 0; }
</style>

<div class="detail-container">
    <a href="list_pendaftar.php" class="add-button" style="background-color: #6c757d; margin-bottom: 20px;">&larr; Kembali ke Daftar</a>

    <h2>Detail Pendaftar: <?php echo htmlspecialchars($pendaftar['nama_lengkap']); ?></h2>
    
    <div class="detail-section">
        <h3>Informasi Pribadi</h3>
        <dl class="detail-grid">
            <dt>ID Pendaftar</dt><dd>: <?php echo htmlspecialchars($pendaftar['id_pendaftar']); ?></dd>
            <dt>Nama Panggilan</dt><dd>: <?php echo htmlspecialchars($pendaftar['nama_panggilan']); ?></dd>
            <dt>Jenis Kelamin</dt><dd>: <?php echo htmlspecialchars($pendaftar['jenis_kelamin']); ?></dd>
            <dt>Tempat, Tanggal Lahir</dt><dd>: <?php echo htmlspecialchars($pendaftar['tempat_lahir']) . ', ' . date('d F Y', strtotime($pendaftar['tanggal_lahir'])); ?></dd>
            <dt>Alamat</dt><dd>: <?php echo nl2br(htmlspecialchars($pendaftar['alamat_lengkap'])); ?></dd>
        </dl>
    </div>

    <div class="detail-section">
        <h3>Informasi Kontak & Kelas</h3>
        <dl class="detail-grid">
            <dt>No. Telepon Siswa</dt><dd>: <?php echo htmlspecialchars($pendaftar['nomor_telepon']); ?></dd>
            <dt>Email Siswa</dt><dd>: <?php echo htmlspecialchars($pendaftar['email']); ?></dd>
            <dt>Nama Wali</dt><dd>: <?php echo htmlspecialchars($pendaftar['nama_wali']); ?></dd>
            <dt>Telepon Wali</dt><dd>: <?php echo htmlspecialchars($pendaftar['telepon_wali']); ?></dd>
            <dt>Pilihan Kelas</dt><dd>: <strong><?php echo htmlspecialchars($pendaftar['pilihan_kelas']); ?></strong></dd>
            <dt>Tanggal Mendaftar</dt><dd>: <?php echo date('d M Y, H:i', strtotime($pendaftar['tanggal_daftar'])); ?></dd>
        </dl>
    </div>

    <div class="detail-section">
        <h3>Update Status & Catatan</h3>
        <form action="proses_update_status_pendaftar.php" method="POST">
            <input type="hidden" name="id_pendaftar" value="<?php echo $pendaftar['id_pendaftar']; ?>">
            <div class="input-group">
                <label for="status">Ubah Status Pendaftaran:</label>
                <select name="status" id="status" class="form-control" style="max-width: 300px;">
                    <option value="Baru" <?php if($pendaftar['status'] == 'Baru') echo 'selected'; ?>>Baru</option>
                    <option value="Diterima" <?php if($pendaftar['status'] == 'Diterima') echo 'selected'; ?>>Diterima</option>
                    <option value="Ditolak" <?php if($pendaftar['status'] == 'Ditolak') echo 'selected'; ?>>Ditolak</option>
                    <option value="Selesai" <?php if($pendaftar['status'] == 'Selesai') echo 'selected'; ?>>Selesai</option>
                </select>
            </div>
            <div class="input-group">
                <label for="catatan_admin">Catatan Admin (Internal):</label>
                <textarea name="catatan_admin" id="catatan_admin" rows="4"><?php echo htmlspecialchars($pendaftar['catatan_admin']); ?></textarea>
            </div>
            <div class="submit-button-container">
                <button type="submit" name="submit_update_status">Update Status</button>
            </div>
        </form>
    </div>
</div>

<?php
if ($conn) close_connection($conn);
require_once '../templates_admin/footer.php';
?>