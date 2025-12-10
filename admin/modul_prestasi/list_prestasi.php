<?php
$admin_page_title = "Daftar Prestasi";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

$sql = "SELECT * FROM Prestasi ORDER BY tanggal_prestasi DESC";
$result = mysqli_query($conn, $sql);
?>

<h2>Manajemen Prestasi Sanggar</h2>
<a href="tambah_prestasi.php" class="add-button"><i class="fas fa-plus"></i> Tambah Prestasi Baru</a>

<?php /* Tampilkan Pesan Sukses/Error */ ?>
<?php
if (isset($_SESSION['pesan_sukses'])) {
    echo '<div class="admin-alert alert-success">' . htmlspecialchars($_SESSION['pesan_sukses']) . '</div>';
    unset($_SESSION['pesan_sukses']);
}
?>

<table>
    <thead>
        <tr>
            <th>Tanggal</th>
            <th>Judul Prestasi</th>
            <th>Tingkat</th>
            <th>Penyelenggara</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo date('d M Y', strtotime($row['tanggal_prestasi'])); ?></td>
                    <td><?php echo htmlspecialchars($row['judul_prestasi']); ?></td>
                    <td><?php echo htmlspecialchars($row['tingkat']); ?></td>
                    <td><?php echo htmlspecialchars($row['penyelenggara']); ?></td>
                    <td><?php echo ($row['aktif'] == 1) ? 'Aktif' : 'Tidak Aktif'; ?></td>
                    <td class="action-links">
                        <a href="edit_prestasi.php?id=<?php echo $row['id_prestasi']; ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                        <a href="hapus_prestasi.php?id=<?php echo $row['id_prestasi']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus prestasi ini?');"><i class="fas fa-trash"></i> Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="6">Belum ada data prestasi.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
if ($conn) close_connection($conn);
require_once '../templates_admin/footer.php';
?>