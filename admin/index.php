<?php
require_once '../config/database.php';
require_once 'templates_admin/header.php';

// --- QUERIES UNTUK KARTU STATISTIK ---
// Total Berita
$query_total_berita = "SELECT COUNT(*) AS total FROM ArtikelBerita";
$res_total_berita = mysqli_query($conn, $query_total_berita);
$total_berita = ($res_total_berita) ? mysqli_fetch_assoc($res_total_berita)['total'] : 0;

// Total Item Galeri
$query_total_item_galeri = "SELECT COUNT(*) AS total FROM ItemGaleri";
$res_total_item_galeri = mysqli_query($conn, $query_total_item_galeri);
$total_item_galeri = ($res_total_item_galeri) ? mysqli_fetch_assoc($res_total_item_galeri)['total'] : 0;

// Total Pendaftar Murid Baru (status 'Baru')
$query_pendaftar_baru = "SELECT COUNT(*) AS total FROM PendaftarMurid WHERE status = 'Baru'";
$res_pendaftar_baru = mysqli_query($conn, $query_pendaftar_baru);
$total_pendaftar_baru = ($res_pendaftar_baru) ? mysqli_fetch_assoc($res_pendaftar_baru)['total'] : 0;

// Total Pesanan Jasa Baru (status 'Baru')
$query_pesanan_baru = "SELECT COUNT(*) AS total FROM PemesananJasa WHERE status = 'Baru'";
$res_pesanan_baru = mysqli_query($conn, $query_pesanan_baru);
$total_pesanan_baru = ($res_pesanan_baru) ? mysqli_fetch_assoc($res_pesanan_baru)['total'] : 0;

// Total Prestasi
$query_total_prestasi = "SELECT COUNT(*) AS total FROM Prestasi";
$res_total_prestasi = mysqli_query($conn, $query_total_prestasi);
$total_prestasi = ($res_total_prestasi) ? mysqli_fetch_assoc($res_total_prestasi)['total'] : 0;

// Total Pesan Masuk Belum Dibaca
$query_pesan_masuk_baru = "SELECT COUNT(*) AS total FROM PesanKontak WHERE status_baca = 'Belum Dibaca'";
$res_pesan_masuk_baru = mysqli_query($conn, $query_pesan_masuk_baru);
$total_pesan_masuk_baru = ($res_pesan_masuk_baru) ? mysqli_fetch_assoc($res_pesan_masuk_baru)['total'] : 0;


// --- QUERIES UNTUK DAFTAR AKTIVITAS TERBARU (LIMIT 5) ---
// Berita Terbaru
$sql_berita_terbaru = "SELECT id_artikel, judul_artikel, status_publikasi FROM ArtikelBerita ORDER BY diperbarui_pada DESC LIMIT 5";
$result_berita_terbaru = mysqli_query($conn, $sql_berita_terbaru);

// Galeri Terbaru
$sql_galeri_terbaru = "SELECT ig.id_item_galeri, ig.judul_item, ig.path_gambar_thumb, kg.nama_kategori 
                       FROM ItemGaleri ig 
                       LEFT JOIN KategoriGaleri kg ON ig.id_kategori_galeri = kg.id_kategori_galeri 
                       ORDER BY ig.diunggah_pada DESC LIMIT 5";
$result_galeri_terbaru = mysqli_query($conn, $sql_galeri_terbaru);

// Prestasi Terbaru
$sql_prestasi_terbaru = "SELECT id_prestasi, judul_prestasi, tanggal_prestasi FROM Prestasi ORDER BY tanggal_prestasi DESC LIMIT 5";
$result_prestasi_terbaru = mysqli_query($conn, $sql_prestasi_terbaru);

// Pesan Masuk Terbaru (Belum Dibaca)
$sql_pesan_terbaru = "SELECT id_pesan, nama_pengirim, isi_pesan FROM PesanKontak WHERE status_baca = 'Belum Dibaca' ORDER BY tanggal_kirim DESC LIMIT 5";
$result_pesan_terbaru = mysqli_query($conn, $sql_pesan_terbaru);

// Layanan Terakhir Diupdate
$sql_layanan_terbaru = "SELECT id_layanan, judul FROM HalamanLayanan ORDER BY diperbarui_pada DESC LIMIT 5";
$result_layanan_terbaru = mysqli_query($conn, $sql_layanan_terbaru);

// Kegiatan Beranda Terakhir Diupdate
$sql_kegiatan_terbaru = "SELECT id_kegiatan, judul FROM HalamanKegiatan ORDER BY id_kegiatan DESC LIMIT 5"; // Asumsi diurutkan berdasarkan ID terbaru
$result_kegiatan_terbaru = mysqli_query($conn, $sql_kegiatan_terbaru);

?>

<h1>Selamat Datang di Dashboard Admin!</h1>
<p>Dari sini Anda dapat mengelola berbagai konten website Sanggar Sekar Kemuning.</p>

<div class="dashboard-stats">
    <div class="stat-card">
        <h3>Pendaftar Baru</h3>
        <p class="stat-number"><?php echo $total_pendaftar_baru; ?></p>
        <a href="<?php echo BASE_URL_ADMIN; ?>/modul_pendaftar/list_pendaftar.php" class="btn-admin-action">Lihat Pendaftar</a>
    </div>
    <div class="stat-card">
        <h3>Pesanan Baru</h3>
        <p class="stat-number"><?php echo $total_pesanan_baru; ?></p>
        <a href="<?php echo BASE_URL_ADMIN; ?>/modul_pemesanan/list_pemesanan.php" class="btn-admin-action">Lihat Pesanan</a>
    </div>
     <div class="stat-card">
        <h3>Pesan Belum Dibaca</h3>
        <p class="stat-number"><?php echo $total_pesan_masuk_baru; ?></p>
        <a href="<?php echo BASE_URL_ADMIN; ?>/modul_kontak/list_pesan.php" class="btn-admin-action">Lihat Pesan</a>
    </div>
    <div class="stat-card">
        <h3>Total Prestasi</h3>
        <p class="stat-number"><?php echo $total_prestasi; ?></p>
        <a href="<?php echo BASE_URL_ADMIN; ?>/modul_prestasi/list_prestasi.php" class="btn-admin-action">Kelola Prestasi</a>
    </div>
</div>

<div class="row dashboard-activity-section">
    <div class="col-lg-6">
        <div class="dashboard-list-box">
            <h3><i class="fas fa-newspaper"></i> Aktivitas Berita Terbaru</h3>
            <ul class="recent-activity-list">
                <?php if ($result_berita_terbaru && mysqli_num_rows($result_berita_terbaru) > 0): ?>
                    <?php while($berita = mysqli_fetch_assoc($result_berita_terbaru)): ?>
                        <li>
                            <div class="activity-text">
                                <a href="<?php echo BASE_URL_ADMIN; ?>/modul_berita/edit_berita.php?id=<?php echo $berita['id_artikel']; ?>" title="Edit Berita Ini"><?php echo htmlspecialchars($berita['judul_artikel']); ?></a>
                            </div>
                            <span class="status-badge status-<?php echo strtolower($berita['status_publikasi']); ?>"><?php echo htmlspecialchars($berita['status_publikasi']); ?></span>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?><li>Tidak ada aktivitas berita terbaru.</li><?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="dashboard-list-box">
            <h3><i class="fas fa-images"></i> Item Galeri Terbaru</h3>
            <ul class="recent-activity-list">
                 <?php if ($result_galeri_terbaru && mysqli_num_rows($result_galeri_terbaru) > 0): ?>
                    <?php while($galeri = mysqli_fetch_assoc($result_galeri_terbaru)): ?>
                        <li>
                            <?php if (!empty($galeri['path_gambar_thumb'])): ?>
                                <img src="<?php echo BASE_URL_PUBLIC . '/' . htmlspecialchars($galeri['path_gambar_thumb']); ?>" class="activity-thumb">
                            <?php else: ?>
                                <div class="activity-thumb" style="background-color:#eee; display:flex; align-items:center; justify-content:center; color:#aaa;"><i class="fas fa-image"></i></div>
                            <?php endif; ?>
                            <div class="activity-text">
                                <a href="<?php echo BASE_URL_ADMIN; ?>/modul_galeri/edit_item_galeri.php?id=<?php echo $galeri['id_item_galeri']; ?>" title="Edit Item Ini"><?php echo htmlspecialchars($galeri['judul_item']); ?></a>
                                <span>di kategori "<?php echo htmlspecialchars($galeri['nama_kategori'] ?? 'Tanpa Kategori'); ?>"</span>
                            </div>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?><li>Tidak ada item galeri terbaru.</li><?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<div class="row dashboard-activity-section">
    <div class="col-lg-6">
        <div class="dashboard-list-box">
            <h3><i class="fas fa-trophy"></i> Prestasi Terbaru</h3>
            <ul class="recent-activity-list">
                <?php if ($result_prestasi_terbaru && mysqli_num_rows($result_prestasi_terbaru) > 0): ?>
                    <?php while($prestasi = mysqli_fetch_assoc($result_prestasi_terbaru)): ?>
                        <li>
                            <div class="activity-icon" style="margin-right:15px; color:#F5C000;"><i class="fas fa-award fa-2x"></i></div>
                            <div class="activity-text">
                                <a href="<?php echo BASE_URL_ADMIN; ?>/modul_prestasi/edit_prestasi.php?id=<?php echo $prestasi['id_prestasi']; ?>"><?php echo htmlspecialchars($prestasi['judul_prestasi']); ?></a>
                                <span>Diraih pada <?php echo date('d M Y', strtotime($prestasi['tanggal_prestasi'])); ?></span>
                            </div>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?><li>Tidak ada prestasi terbaru.</li><?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="dashboard-list-box">
            <h3><i class="fas fa-envelope-open-text"></i> Pesan Masuk (Belum Dibaca)</h3>
            <ul class="recent-activity-list">
                <?php if ($result_pesan_terbaru && mysqli_num_rows($result_pesan_terbaru) > 0): ?>
                    <?php while($pesan = mysqli_fetch_assoc($result_pesan_terbaru)): ?>
                        <li>
                            <div class="activity-icon" style="margin-right:15px; color:#3498db;"><i class="fas fa-user-circle fa-2x"></i></div>
                            <div class="activity-text">
                                <a href="<?php echo BASE_URL_ADMIN; ?>/modul_kontak/detail_pesan.php?id=<?php echo $pesan['id_pesan']; ?>">Pesan dari: <?php echo htmlspecialchars($pesan['nama_pengirim']); ?></a>
                                <span>"<?php echo htmlspecialchars(substr($pesan['isi_pesan'], 0, 40)); ?>..."</span>
                            </div>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?><li>Tidak ada pesan baru.</li><?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<div class="row dashboard-activity-section">
    <div class="col-lg-6">
        <div class="dashboard-list-box">
            <h3><i class="fas fa-concierge-bell"></i> Layanan Terakhir Diupdate</h3>
            <ul class="recent-activity-list">
                <?php if ($result_layanan_terbaru && mysqli_num_rows($result_layanan_terbaru) > 0): ?>
                    <?php while($layanan = mysqli_fetch_assoc($result_layanan_terbaru)): ?>
                        <li>
                            <div class="activity-text">
                                <a href="<?php echo BASE_URL_ADMIN; ?>/modul_halaman_layanan/edit_layanan.php?id=<?php echo $layanan['id_layanan']; ?>"><?php echo htmlspecialchars($layanan['judul']); ?></a>
                            </div>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?><li>Tidak ada data layanan.</li><?php endif; ?>
            </ul>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="dashboard-list-box">
            <h3><i class="fas fa-tasks"></i> Kegiatan Beranda Terakhir Diupdate</h3>
            <ul class="recent-activity-list">
                <?php if ($result_kegiatan_terbaru && mysqli_num_rows($result_kegiatan_terbaru) > 0): ?>
                    <?php while($kegiatan = mysqli_fetch_assoc($result_kegiatan_terbaru)): ?>
                        <li>
                            <div class="activity-text">
                                <a href="<?php echo BASE_URL_ADMIN; ?>/modul_halaman_kegiatan/edit_kegiatan.php?id=<?php echo $kegiatan['id_kegiatan']; ?>"><?php echo htmlspecialchars($kegiatan['judul']); ?></a>
                            </div>
                        </li>
                    <?php endwhile; ?>
                <?php else: ?><li>Tidak ada data kegiatan.</li><?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<?php
if ($conn) close_connection($conn);
require_once 'templates_admin/footer.php';
?>