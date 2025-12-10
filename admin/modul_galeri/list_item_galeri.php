<?php
// PROJECT-WEB-2025/admin/modul_galeri/list_item_galeri.php
$admin_page_title = "Daftar Item Galeri";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

// Ambil data item galeri beserta nama kategorinya menggunakan JOIN
// Tambahkan paginasi jika item sudah banyak
$sql = "SELECT ig.*, kg.nama_kategori 
        FROM ItemGaleri ig
        JOIN KategoriGaleri kg ON ig.id_kategori_galeri = kg.id_kategori_galeri
        ORDER BY ig.diperbarui_pada DESC"; // Atau urutkan berdasarkan kategori, lalu urutan_tampil item
$result = mysqli_query($conn, $sql);
?>

<h2>Manajemen Item Galeri</h2>
<a href="tambah_item_galeri.php" class="add-button"><i class="fas fa-plus"></i> Tambah Item Galeri Baru</a>

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
            <th>Thumbnail</th>
            <th>Judul Item</th>
            <th>Kategori</th>
            <th>Status</th>
            <th>Urutan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_item_galeri']); ?></td>
                    <td>
                        <?php if (!empty($row['path_gambar_thumb'])): ?>
                            <img src="<?php echo BASE_URL_PUBLIC . '/' . htmlspecialchars($row['path_gambar_thumb']); ?>" alt="Thumb" style="width: 80px; height: auto; border-radius: 4px;">
                        <?php else: ?>
                            (Tidak ada thumb)
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['judul_item']); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_kategori']); ?></td>
                    <td><?php echo ($row['aktif'] == 1) ? 'Aktif' : 'Tidak Aktif'; ?></td>
                    <td><?php echo htmlspecialchars($row['urutan_tampil']); ?></td>
                    <td class="action-links">
                        <a href="edit_item_galeri.php?id=<?php echo $row['id_item_galeri']; ?>" class="btn-edit"><i class="fas fa-edit"></i> Edit</a>
                        <a href="hapus_item_galeri.php?id=<?php echo $row['id_item_galeri']; ?>" class="btn-delete" onclick="return confirm('Apakah Anda yakin ingin menghapus item galeri ini?');"><i class="fas fa-trash"></i> Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr>
                <td colspan="7">Belum ada item galeri.</td>
            </tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
if ($conn) close_connection($conn);
require_once '../templates_admin/footer.php';
?>