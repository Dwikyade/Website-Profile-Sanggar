<?php
$admin_page_title = "Data Pemesanan Jasa";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

// Logika Paginasi
$pesanan_per_halaman = 10;
$halaman_sekarang = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$offset = ($halaman_sekarang - 1) * $pesanan_per_halaman;

$sql_total = "SELECT COUNT(*) AS total FROM PemesananJasa";
$result_total = mysqli_query($conn, $sql_total);
$total_pesanan = mysqli_fetch_assoc($result_total)['total'];
$total_halaman = ceil($total_pesanan / $pesanan_per_halaman);

// Ambil data pemesanan dengan limit dan offset
$sql = "SELECT * FROM PemesananJasa ORDER BY tanggal_pesan DESC LIMIT ?, ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, "ii", $offset, $pesanan_per_halaman);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<h2>Data Pemesanan Jasa</h2>
<p>Daftar pemesanan jasa (rias, sewa kostum, fotografi) yang masuk melalui website.</p>

<?php /* Tampilkan Pesan Sukses/Error dari Sesi */ ?>
<?php
if (isset($_SESSION['pesan_sukses'])) {
    echo '<div class="admin-alert alert-success">' . htmlspecialchars($_SESSION['pesan_sukses']) . '</div>';
    unset($_SESSION['pesan_sukses']);
}
if (isset($_SESSION['pesan_error'])) {
    echo '<div class="admin-alert alert-danger">' . htmlspecialchars($_SESSION['pesan_error']) . '</div>';
    unset($_SESSION['pesan_error']);
}
?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Pemesan</th>
            <th>Jenis Jasa</th>
            <th>Tanggal Acara</th>
            <th>Tanggal Pesan</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr style="<?php echo ($row['status'] == 'Baru') ? 'background-color: #e9f7ff;' : ''; ?>">
                    <td><?php echo htmlspecialchars($row['id_pemesanan']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_pemesan']); ?></td>
                    <td><?php echo htmlspecialchars($row['jenis_jasa']); ?></td>
                    <td><?php echo date('d M Y', strtotime($row['tanggal_acara'])); ?></td>
                    <td><?php echo date('d M Y, H:i', strtotime($row['tanggal_pesan'])); ?></td>
                    <td>
                        <span class="status-badge status-<?php echo strtolower($row['status']); ?>"><?php echo htmlspecialchars($row['status']); ?></span>
                    </td>
                    <td class="action-links">
                        <a href="detail_pemesanan.php?id=<?php echo $row['id_pemesanan']; ?>" class="btn-edit"><i class="fas fa-eye"></i> Detail</a>
                        <a href="hapus_pemesanan.php?id=<?php echo $row['id_pemesanan']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus data pemesanan ini secara permanen?');"><i class="fas fa-trash"></i> Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">Belum ada data pemesanan jasa.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php if ($total_halaman > 1): ?>
<nav class="pagination-nav">
    <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
        <a href="?halaman=<?php echo $i; ?>" class="<?php echo ($i == $halaman_sekarang) ? 'active' : ''; ?>"><?php echo $i; ?></a>
    <?php endfor; ?>
</nav>
<?php endif; ?>

<?php
mysqli_stmt_close($stmt);
if ($conn) close_connection($conn);
require_once '../templates_admin/footer.php';
?>