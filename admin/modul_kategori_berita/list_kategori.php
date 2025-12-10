<?php
$admin_page_title = "Daftar Kategori Berita";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

$sql = "SELECT * FROM KategoriBerita ORDER BY nama_kategori ASC";
$result = mysqli_query($conn, $sql);
?>

<h2>Manajemen Kategori Berita</h2>
<p>Kelola kategori atau tag yang bisa digunakan untuk mengelompokkan artikel berita.</p>
<a href="tambah_kategori.php" class="add-button"><i class="fas fa-plus"></i> Tambah Kategori Baru</a>

<?php
if (isset($_SESSION['pesan_sukses'])) {
    echo '<div class="notif-sukses"><span><i class="fas fa-check-circle"></i> ' . htmlspecialchars($_SESSION['pesan_sukses']) . '</span></div>';
    unset($_SESSION['pesan_sukses']);
}
if (isset($_SESSION['pesan_error'])) {
    echo '<div class="admin-alert alert-danger"><span><i class="fas fa-exclamation-triangle"></i> ' . htmlspecialchars($_SESSION['pesan_error']) . '</span></div>';
    unset($_SESSION['pesan_error']);
}
?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Nama Kategori</th>
            <th>Slug</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_kategori_berita']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                    <td><?php echo htmlspecialchars($row['slug_kategori']); ?></td>
                    <td class="action-links">
                        <a href="edit_kategori.php?id=<?php echo $row['id_kategori_berita']; ?>" class="btn-edit" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                        <a href="hapus_kategori.php?id=<?php echo $row['id_kategori_berita']; ?>" class="btn-delete" title="Hapus" onclick="return confirm('Yakin ingin menghapus kategori ini? Menghapus kategori akan melepaskan tautannya dari semua berita terkait.');"><i class="fas fa-trash"></i> Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="4">Belum ada kategori berita.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
if ($conn) close_connection($conn);
require_once '../templates_admin/footer.php';
?>