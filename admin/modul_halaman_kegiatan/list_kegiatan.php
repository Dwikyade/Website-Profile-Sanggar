<?php
$admin_page_title = "Daftar Kegiatan";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

// Ambil semua data layanan dari database
$sql = "SELECT * FROM HalamanKegiatan ORDER BY urutan_tampil ASC";
$result = mysqli_query($conn, $sql);
?>

<h2>Manajemen Kegiatan Halaman Publik</h2>
<p>Kelola item kegiatan yang akan ditampilkan di halaman "Kegiatan" publik.</p>
<a href="tambah_kegiatan.php" class="add-button"><i class="fas fa-plus"></i> Tambah Kegiatan Baru</a>

<?php
// Tampilkan pesan sukses atau error dari sesi
if (isset($_SESSION['pesan_sukses'])) {
    echo '<div class="admin-alert alert-success">' .
         '<span><i class="fas fa-check-circle"></i> ' . htmlspecialchars($_SESSION['pesan_sukses']) . '</span>' .
         '<button type="button" class="close-btn" onclick="this.parentElement.style.display=\'none\';">&times;</button>' .
         '</div>';
    unset($_SESSION['pesan_sukses']);
}
if (isset($_SESSION['pesan_error'])) {
    echo '<div class="admin-alert alert-danger">' .
         '<span><i class="fas fa-exclamation-triangle"></i> ' . htmlspecialchars($_SESSION['pesan_error']) . '</span>' .
         '<button type="button" class="close-btn" onclick="this.parentElement.style.display=\'none\';">&times;</button>' .
         '</div>';
    unset($_SESSION['pesan_error']);
}
?>

<table>
    <thead>
        <tr>
            <th>ID</th>
            <th>Ikon</th>
            <th>Judul</th>
            <th>Deskripsi Singkat</th>
            <th>Status</th>
            <th>Urutan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td><?php echo htmlspecialchars($row['id_kegiatan']); ?></td>
                    <td>
                        <?php if (!empty($row['ikon_path'])): ?>
                            <img src="<?php echo BASE_URL_PUBLIC . '/' . htmlspecialchars($row['ikon_path']); ?>" alt="Ikon" style="width: 50px; height: auto; background-color: #f0f0f0; padding: 5px; border-radius: 4px;">
                        <?php else: ?>
                            (Tanpa Ikon)
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['judul']); ?></td>
                    <td><?php echo htmlspecialchars(substr($row['deskripsi'], 0, 50)) . (strlen($row['deskripsi']) > 50 ? '...' : ''); ?></td>
                    <td>
                        <?php if ($row['aktif'] == 1): ?>
                            <span style="color: green;">Aktif</span>
                        <?php else: ?>
                            <span style="color: red;">Tidak Aktif</span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo htmlspecialchars($row['urutan_tampil']); ?></td>
                    <td class="action-links">
                        <a href="edit_kegiatan.php?id=<?php echo $row['id_kegiatan']; ?>" class="btn-edit" title="Edit"><i class="fas fa-edit"></i> Edit</a>
                        <a href="hapus_kegiatan.php?id=<?php echo $row['id_kegiatan']; ?>" class="btn-delete" title="Hapus" onclick="return confirm('Apakah Anda yakin ingin menghapus kegiatan ini?');"><i class="fas fa-trash"></i> Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7" style="text-align:center;">Belum ada data kegiatan. Silakan tambah baru.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
if ($conn) close_connection($conn);
require_once '../templates_admin/footer.php';
?>