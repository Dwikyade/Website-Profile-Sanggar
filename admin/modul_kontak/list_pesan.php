<?php
$admin_page_title = "Pesan Masuk";
require_once '../../config/database.php';
require_once '../templates_admin/header.php';

// Ambil semua pesan, yang belum dibaca di atas
$sql = "SELECT * FROM PesanKontak ORDER BY status_baca ASC, tanggal_kirim DESC";
$result = mysqli_query($conn, $sql);
?>

<h2>Pesan Masuk dari Halaman Kontak</h2>
<p>Daftar pesan yang dikirim oleh pengunjung melalui form "Hubungi Kami".</p>

<?php /* Tampilkan Pesan Sukses/Error dari Sesi */ ?>
<?php
if (isset($_SESSION['pesan_sukses'])) {
    echo '<div class="admin-alert alert-success">' . htmlspecialchars($_SESSION['pesan_sukses']) . '</div>';
    unset($_SESSION['pesan_sukses']);
}
?>

<table>
    <thead>
        <tr>
            <th>Tanggal Kirim</th>
            <th>Nama Pengirim</th>
            <th>Email</th>
            <th>Telepon</th>
            <th>Pesan (Singkat)</th>
            <th>Status</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        <?php if ($result && mysqli_num_rows($result) > 0): ?>
            <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr style="<?php echo ($row['status_baca'] == 'Belum Dibaca') ? 'font-weight: bold;' : ''; ?>">
                    <td><?php echo date('d M Y, H:i', strtotime($row['tanggal_kirim'])); ?></td>
                    <td><?php echo htmlspecialchars($row['nama_pengirim']); ?></td>
                    <td><a href="mailto:<?php echo htmlspecialchars($row['email_pengirim']); ?>"><?php echo htmlspecialchars($row['email_pengirim']); ?></a></td>
                    <td><?php echo htmlspecialchars($row['telepon_pengirim']); ?></td>
                    <td><?php echo htmlspecialchars(substr($row['isi_pesan'], 0, 40)); ?>...</td>
                    <td><?php echo htmlspecialchars($row['status_baca']); ?></td>
                    <td class="action-links">
                        <a href="detail_pesan.php?id=<?php echo $row['id_pesan']; ?>" class="btn-edit"><i class="fas fa-eye"></i> Lihat</a>
                        <a href="hapus_pesan.php?id=<?php echo $row['id_pesan']; ?>" class="btn-delete" onclick="return confirm('Yakin ingin menghapus pesan ini?');"><i class="fas fa-trash"></i> Hapus</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        <?php else: ?>
            <tr><td colspan="7">Belum ada pesan yang masuk.</td></tr>
        <?php endif; ?>
    </tbody>
</table>

<?php
if ($conn) close_connection($conn);
require_once '../templates_admin/footer.php';
?>