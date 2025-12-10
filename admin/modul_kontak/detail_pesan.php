<?php
$admin_page_title = "Detail Pesan Masuk";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

if (!isset($_GET['id']) || empty($_GET['id'])) { exit('ID tidak valid'); }
$id_pesan = (int)$_GET['id'];

// Ambil detail pesan
$sql = "SELECT * FROM PesanKontak WHERE id_pesan = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "i", $id_pesan);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$pesan = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);
if (!$pesan) { exit('Pesan tidak ditemukan'); }

// Jika statusnya 'Belum Dibaca', update menjadi 'Sudah Dibaca'
if ($pesan['status_baca'] == 'Belum Dibaca') {
    $sql_update_status = "UPDATE PesanKontak SET status_baca = 'Sudah Dibaca' WHERE id_pesan = ?";
    $stmt_update = mysqli_prepare($conn, $sql_update_status);
    mysqli_stmt_bind_param($stmt_update, "i", $id_pesan);
    mysqli_stmt_execute($stmt_update);
    mysqli_stmt_close($stmt_update);
}
?>
<div class="detail-container">
    <a href="list_pesan.php" class="add-button" style="background-color: #6c757d; margin-bottom: 20px;">&larr; Kembali ke Daftar Pesan</a>

    <h2>Detail Pesan dari: <?php echo htmlspecialchars($pesan['nama_pengirim']); ?></h2>
    
    <div class="detail-section">
        <h3>Informasi Pengirim</h3>
        <dl class="detail-grid">
            <dt>Tanggal Kirim</dt><dd>: <?php echo date('d F Y, H:i', strtotime($pesan['tanggal_kirim'])); ?></dd>
            <dt>Nama</dt><dd>: <?php echo htmlspecialchars($pesan['nama_pengirim']); ?></dd>
            <dt>Email</dt><dd>: <a href="mailto:<?php echo htmlspecialchars($pesan['email_pengirim']); ?>"><?php echo htmlspecialchars($pesan['email_pengirim']); ?></a></dd>
            <dt>Telepon</dt><dd>: <?php echo htmlspecialchars($pesan['telepon_pengirim']); ?></dd>
            <dt>Status</dt><dd>: <?php echo htmlspecialchars($pesan['status_baca']); ?></dd>
        </dl>
    </div>

    <div class="detail-section">
        <h3>Isi Pesan</h3>
        <p style="white-space: pre-wrap;"><?php echo htmlspecialchars($pesan['isi_pesan']); ?></p>
    </div>
</div>

<?php
if ($conn) close_connection($conn);
require_once '../templates_admin/footer.php';
?>