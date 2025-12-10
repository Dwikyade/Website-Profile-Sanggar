<?php
// PROJECT-WEB-2025/admin/modul_galeri/list_kategori_galeri.php
$admin_page_title = "Daftar Kategori Galeri";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

$sql = "SELECT * FROM KategoriGaleri ORDER BY urutan_tampil ASC, nama_kategori ASC";
$result = mysqli_query($conn, $sql);
?>

<h2>Manajemen Kategori Galeri</h2>
<a href="tambah_kategori_galeri.php" class="add-button"><i class="fas fa-plus"></i> Tambah Kategori Baru</a>

<?php
if (isset($_SESSION['pesan_sukses'])) {
    echo '<div class="alert alert-success">' . htmlspecialchars($_SESSION['pesan_sukses']) . '</div>';
    unset($_SESSION['pesan_sukses']);
}
if (isset($_SESSION['pesan_error'])) {
    echo '<div class="alert alert-danger">' . htmlspecialchars($_SESSION['pesan_error']) . '</div>';
    unset($_SESSION['pesan_error']);
}
?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Kategori</th>
            <th>Slug</th>
            <th>Deskripsi</th>
            <th>Urutan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_kategori_galeri']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                    <td><?php echo htmlspecialchars($row['slug_kategori']); ?></td>
                    <td><?php echo nl2br(htmlspecialchars(substr($row['deskripsi_kategori'], 0, 50))) . (strlen($row['deskripsi_kategori']) > 50 ? '...' : ''); ?></td>
                    <td><?php echo htmlspecialchars($row['urutan_tampil']); ?></td>
                    <td class="action-links">
                        <a href="edit_kategori_galeri.php?id=<?php echo $row['id_kategori_galeri']; ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                        <a href="hapus_kategori_galeri.php?id=<?php echo $row['id_kategori_galeri']; ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus kategori ini? Menghapus kategori akan menghapus SEMUA item galeri di dalamnya.');"><i class="fas fa-trash"></i> Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="6">Belum ada kategori galeri.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
if ($conn) close_connection($conn);
require_once '../templates_admin/footer.php';
?>