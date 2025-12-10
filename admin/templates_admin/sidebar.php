<?php
// PROJECT-WEB-2025/admin/templates_admin/sidebar.php
// BASE_URL_ADMIN dan BASE_URL_PUBLIC seharusnya sudah ada dari header.php (yang di-include dari config/database.php)
if (!defined('BASE_URL_ADMIN')) { define('BASE_URL_ADMIN', '/PROJECT-WEB-2025/admin'); } // Fallback
if (!defined('BASE_URL_PUBLIC')) { define('BASE_URL_PUBLIC', '/PROJECT-WEB-2025/public'); } // Fallback

$current_page_sidebar = basename($_SERVER['PHP_SELF']);
// Dapatkan path skrip saat ini relatif terhadap document root server
$script_path_from_doc_root = $_SERVER['PHP_SELF']; 

// Fungsi untuk mengecek apakah link aktif
function isAdminMenuActive($link_path_from_admin_root, $current_script_path_from_admin_root) {
    // Normalisasi path, hapus slash di awal jika ada untuk perbandingan
    $link_path_normal = ltrim($link_path_from_admin_root, '/');
    $current_path_normal = ltrim($current_script_path_from_admin_root, '/');
    // Cek apakah path saat ini dimulai dengan path link (untuk mencakup sub-halaman modul)
    if (strpos($current_path_normal, $link_path_normal) === 0) {
        // Jika linknya adalah index.php dan path saat ini bukan hanya index.php (misal ada di modul), jangan aktifkan index.php
        if ($link_path_normal === 'index.php' && $current_path_normal !== 'index.php') {
            return '';
        }
        return 'active';
    }
    return '';
}
// Path saat ini relatif dari BASE_URL_ADMIN
$current_path_relative = str_replace(rtrim(BASE_URL_ADMIN, '/'), '', $script_path_from_doc_root);
?>
<aside class="admin-sidebar" id="adminSidebar">
    <div class="admin-logo">
        <a href="<?php echo BASE_URL_ADMIN; ?>/index.php">
            <img src="<?php echo BASE_URL_PUBLIC; ?>/images/logo.png" alt="Logo Sanggar">
            <h2>Sanggar Admin</h2>
        </a>
    </div>
    <a href="<?php echo BASE_URL_ADMIN; ?>/index.php" class="<?php echo isAdminMenuActive('index.php', $current_path_relative); ?>">
        <i class="fas fa-tachometer-alt"></i> <span>Dashboard</span>
    </a>
    <a href="<?php echo BASE_URL_ADMIN; ?>/modul_prestasi/list_prestasi.php" class="<?php /* ... logika kelas active ... */ ?>">
    <i class="fas fa-trophy"></i> <span>Kelola Prestasi</span>
</a>
    <a href="<?php echo BASE_URL_ADMIN; ?>/modul_tentang_kami/edit_tentang_kami.php" class="<?php echo isAdminMenuActive('modul_tentang_kami', $current_path_relative); ?>">
    <i class="fas fa-info-circle"></i> <span>Kelola Tentang Kami</span>
</a>
<a href="<?php echo BASE_URL_ADMIN; ?>/modul_halaman_layanan/list_layanan.php" class="<?php echo isAdminMenuActive('modul_halaman_layanan', $current_path_relative); ?>">
    <i class="fas fa-concierge-bell"></i> <span>Kelola Layanan</span>
</a>
<a href="<?php echo BASE_URL_ADMIN; ?>/modul_halaman_kegiatan/list_kegiatan.php" class="<?php echo isAdminMenuActive('modul_halaman_kegiatan', $current_path_relative); ?>">
    <i class="fas fa-tasks"></i> <span>Kelola Kegiatan</span>
</a>

    <a href="<?php echo BASE_URL_ADMIN; ?>/modul_berita/berita_list.php" class="<?php echo isAdminMenuActive('modul_berita/berita_list.php', $current_path_relative); ?>">
        <i class="fas fa-newspaper"></i> <span>Kelola Berita</span>
    </a>
    
    <a href="<?php echo BASE_URL_ADMIN; ?>/modul_galeri/list_item_galeri.php" class="<?php echo isAdminMenuActive('modul_galeri/list_item_galeri.php', $current_path_relative); ?>">
        <i class="fas fa-image"></i> <span>Item Galeri</span>
    </a>
    <hr style="border-top: 1px solid #4a627a; margin: 15px 20px;">
<a href="<?php echo BASE_URL_ADMIN; ?>/modul_kategori_berita/list_kategori.php" class="<?php echo isAdminMenuActive('modul_kategori_berita', $current_path_relative); ?>">
    <i class="fas fa-tags"></i> <span>Kategori Berita</span>
</a>
    <a href="<?php echo BASE_URL_ADMIN; ?>/modul_galeri/list_kategori_galeri.php" class="<?php echo isAdminMenuActive('modul_galeri/list_kategori_galeri.php', $current_path_relative); ?>">
        <i class="fas fa-images"></i> <span>Kategori Galeri</span>
    </a>
    <hr style="border-top: 1px solid #4a627a; margin: 15px 20px;">
    <a href="<?php echo BASE_URL_ADMIN; ?>/modul_pendaftar/list_pendaftar.php" class="...">
    <i class="fas fa-user-plus"></i> <span>Pendaftar Murid</span>
</a>
<a href="<?php echo BASE_URL_ADMIN; ?>/modul_pemesanan/list_pemesanan.php" class="...">
    <i class="fas fa-shopping-cart"></i> <span>Pemesanan Jasa</span>
</a>
    <a href="<?php echo BASE_URL_ADMIN; ?>/modul_kontak/list_pesan.php" class="...">
    <i class="fas fa-envelope"></i> <span>Pesan Masuk</span>
</a>
<hr style="border-top: 1px solid #4a627a; margin: 15px 20px;">
    <a href="<?php echo BASE_URL_PUBLIC; ?>/index.php" target="_blank">
        <i class="fas fa-globe"></i> <span>Lihat Website</span>
    </a>
</aside>