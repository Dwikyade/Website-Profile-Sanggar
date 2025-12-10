
<?php
// PROJECT-WEB-2025/admin/modul_berita/list_berita.php
$admin_page_title = "Daftar Berita";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

$sql = "SELECT id_artikel, judul_artikel, penulis, status_publikasi, tanggal_publikasi, diperbarui_pada 
        FROM artikelberita 
        ORDER BY diperbarui_pada DESC";
$result = mysqli_query($conn, $sql);
?>

<h2>Manajemen Berita</h2>
<a href="berita_tambah.php" class="add-button"><i class="fas fa-plus"></i> Tambah Berita Baru</a>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Judul</th>
            <th>Penulis</th>
            <th>Status</th>
            <th>Tgl Publikasi</th>
            <th>Update Terakhir</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_artikel']); ?></td>
                    <td><?php echo htmlspecialchars($row['judul_artikel']); ?></td>
                    <td><?php echo htmlspecialchars($row['penulis']); ?></td>
                    <td><?php echo htmlspecialchars($row['status_publikasi']); ?></td>
                    <td><?php echo $row['tanggal_publikasi'] ? date('d M Y, H:i', strtotime($row['tanggal_publikasi'])) : '-'; ?></td>
                    <td><?php echo date('d M Y, H:i', strtotime($row['diperbarui_pada'])); ?></td>
                    <td class="action-links">
                        <a href="berita_edit.php?id=<?php echo $row['id_artikel']; ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                        <a href="berita_hapus.php?id=<?php echo $row['id_artikel']; ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus berita ini?');"><i class="fas fa-trash"></i> Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Belum ada berita.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
if ($conn) close_connection($conn);
require_once '../templates_admin/footer.php';
?>