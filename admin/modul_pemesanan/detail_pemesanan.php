<?php
$admin_page_title = "Detail Pemesanan Jasa";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) { /* ... kode redirect jika id tidak ada ... */ }
$id_pemesanan = (int)$_GET['id'];

$sql = "SELECT * FROM PemesananJasa WHERE id_pemesanan = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_pemesanan);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pemesanan = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$pemesanan) { /* ... kode redirect jika data tidak ditemukan ... */ }
?>

<style>
    .detail-container { max-width: 800px; margin: auto; }
    .detail-section { background-color: #fff; padding: 20px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.07); margin-bottom: 20px;}
    .detail-section h3 { border-bottom: 2px solid #f0f0f0; padding-bottom: 10px; margin-top: 0; }
    .detail-grid { display: grid; grid-template-columns: 180px 1fr; gap: 10px 20px; align-items: center;}
    .detail-grid dt { font-weight: 600; color: #555; }
    .detail-grid dd { margin: 0; }
</style>

<div class="detail-container">
    <a href="list_pemesanan.php" class="add-button" style="background-color: #6c757d; margin-bottom: 20px;">&larr; Kembali ke Daftar</a>

    <h2>Detail Pemesanan: <?php echo htmlspecialchars($pemesanan['nama_pemesan']); ?></h2>
    
    <div class="detail-section">
        <h3>Informasi Pemesan & Acara</h3>
        <dl class="detail-grid">
            <dt>ID Pemesanan</dt><dd>: <?php echo htmlspecialchars($pemesanan['id_pemesanan']); ?></dd>
            <dt>Nama Pemesan</dt><dd>: <?php echo htmlspecialchars($pemesanan['nama_pemesan']); ?></dd>
            <dt>No. Telepon</dt><dd>: <?php echo htmlspecialchars($pemesanan['nomor_telepon']); ?></dd>
            <dt>Email</dt><dd>: <?php echo htmlspecialchars($pemesanan['email']); ?></dd>
            <dt>Jenis Jasa</dt><dd>: <strong><?php echo htmlspecialchars($pemesanan['jenis_jasa']); ?></strong></dd>
            <dt>Tanggal Acara</dt><dd>: <?php echo date('d F Y', strtotime($pemesanan['tanggal_acara'])); ?></dd>
            <dt>Lokasi Acara</dt><dd>: <?php echo htmlspecialchars($pemesanan['lokasi_acara']); ?></dd>
            <dt>Tanggal Pesan</dt><dd>: <?php echo date('d M Y, H:i', strtotime($pemesanan['tanggal_pesan'])); ?></dd>
        </dl>
    </div>

    <div class="detail-section">
        <h3>Deskripsi Kebutuhan</h3>
        <p><?php echo nl2br(htmlspecialchars($pemesanan['deskripsi_kebutuhan'])); ?></p>
    </div>

    <div class="detail-section">
        <h3>Update Status & Catatan</h3>
        <form action="proses_update_status_pemesanan.php" method="POST">
            <input type="hidden" name="id_pemesanan" value="<?php echo $pemesanan['id_pemesanan']; ?>">
            <div class="input-group">
                <label for="status">Ubah Status Pemesanan:</label>
                <select name="status" id="status" class="form-control" style="max-width: 300px;">
                    <option value="Baru" <?php if($pemesanan['status'] == 'Baru') echo 'selected'; ?>>Baru</option>
                    <option value="Dikonfirmasi" <?php if($pemesanan['status'] == 'Dikonfirmasi') echo 'selected'; ?>>Dikonfirmasi</option>
                    <option value="Selesai" <?php if($pemesanan['status'] == 'Selesai') echo 'selected'; ?>>Selesai</option>
                    <option value="Dibatalkan" <?php if($pemesanan['status'] == 'Dibatalkan') echo 'selected'; ?>>Dibatalkan</option>
                </select>
            </div>
            <div class="input-group">
                <label for="catatan_admin">Catatan Admin (Internal):</label>
                <textarea name="catatan_admin" id="catatan_admin" rows="4"><?php echo htmlspecialchars($pemesanan['catatan_admin']); ?></textarea>
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