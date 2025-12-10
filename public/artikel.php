<?php
// artikel.php (Halaman Detail Artikel Berita Publik)

// Sesuaikan path ke config/database.php
// Jika artikel.php di public/ dan config/ di root:
require_once '../config/database.php';
// Jika artikel.php di root dan config/ di root:
// require_once 'config/database.php';

// 1. Ambil dan Validasi Slug Artikel dari URL
if (!isset($_GET['slug']) || empty(trim($_GET['slug']))) {
    // Jika tidak ada slug, mungkin arahkan ke halaman berita atau tampilkan error
    if ($conn) close_connection($conn);
    header("Location: news.php"); // Arahkan ke daftar berita
    exit();
}
$slug_artikel = trim($_GET['slug']);

// 2. Fetch Detail Artikel dari Database
// Gunakan prepared statement untuk keamanan
$sql_artikel = "SELECT * FROM ArtikelBerita WHERE slug_artikel = ? AND status_publikasi = 'terbit'";
$stmt_artikel = mysqli_prepare($conn, $sql_artikel);

if (!$stmt_artikel) {
    // Gagal prepare statement, bisa jadi error SQL atau koneksi
    error_log("MySQLi Prepare Error (artikel.php - select artikel): " . mysqli_error($conn));
    // Tampilkan pesan error umum ke pengguna atau redirect
    echo "Terjadi kesalahan saat memuat artikel.";
    if ($conn) close_connection($conn);
    exit();
}

mysqli_stmt_bind_param($stmt_artikel, "s", $slug_artikel);
mysqli_stmt_execute($stmt_artikel);
$result_artikel = mysqli_stmt_get_result($stmt_artikel);
$artikel = mysqli_fetch_assoc($result_artikel);
mysqli_stmt_close($stmt_artikel);

if (!$artikel) {
    // Artikel tidak ditemukan atau belum dipublikasikan
    // Anda bisa membuat halaman 404 khusus atau menampilkan pesan di sini
    $page_title_public = "Artikel Tidak Ditemukan";
    // Include header publik Anda (jika ada dan belum di-include)
    // require_once '../templates/header_public.php'; // Sesuaikan path
    echo "<div style='text-align:center; padding: 50px;'>";
    echo "<h1>Oops! Artikel Tidak Ditemukan</h1>";
    echo "<p>Artikel yang Anda cari mungkin sudah dihapus atau URL-nya berubah.</p>";
    echo "<a href='news.php'>Kembali ke Daftar Berita</a>";
    echo "</div>";
    // Include footer publik Anda
    // require_once '../templates/footer_public.php'; // Sesuaikan path
    if ($conn) close_connection($conn);
    exit();
}

// Jika artikel ditemukan, set judul halaman untuk tab browser
$page_title_public = htmlspecialchars($artikel['judul_artikel']);

// (Opsional) Ambil Kategori/Tag untuk artikel ini
$sql_kategori_artikel = "SELECT kb.nama_kategori, kb.slug_kategori 
                         FROM KategoriBerita kb
                         JOIN PetaArtikelKategori pak ON kb.id_kategori_berita = pak.id_kategori_berita
                         WHERE pak.id_artikel = ?";
$stmt_kategori = mysqli_prepare($conn, $sql_kategori_artikel);
$kategori_list = [];
if ($stmt_kategori) {
    mysqli_stmt_bind_param($stmt_kategori, "i", $artikel['id_artikel']);
    mysqli_stmt_execute($stmt_kategori);
    $result_kategori = mysqli_stmt_get_result($stmt_kategori);
    while ($row_kat = mysqli_fetch_assoc($result_kategori)) {
        $kategori_list[] = $row_kat;
    }
    mysqli_stmt_close($stmt_kategori);
}

// Asumsi Anda memiliki file header dan footer untuk halaman publik
// Jika artikel.php ada di folder public/ dan templates/ sejajar dengan public/ (di root proyek)
// maka pathnya adalah '../templates/header_public.php'
// Jika artikel.php ada di root, dan templates/ juga di root, pathnya 'templates/header_public.php'
// Untuk contoh ini, saya akan mengasumsikan tidak ada include header/footer agar lebih fokus pada konten artikel.
// Anda HARUS mengintegrasikannya dengan template header/footer publik Anda.

?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo $page_title_public . " - Sanggar Sekar Kemuning"; ?></title>

    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="./css/regist.css" />
    <link rel="stylesheet" href="./css/about.css" />
    <link rel="stylesheet" href="./css/services.css" />
    <link rel="stylesheet" href="css/galeri.css" /> 
    <link rel="stylesheet" href="css/news.css" />  
    <link rel="stylesheet" href="css/artikel.css" />  
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="icon" href="images/logo.png" /> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous"/>
    
</head>
<body>

    <header class="header">
      <a href="../admin/index.php" class="logo"
        ><img src="./images/logo.png" alt=""
      />
      <div class="fas fa-bars"></div>
      <nav class="navbar">
        <ul>
          <li><a href="index.php#home">Beranda</a></li>
          <li><a href="about.php">Tentang Kami</a></li>
          <li><a href="service.php">Layanan</a></li>
          <li><a href="activity.php">Kegiatan</a></li>
          <li><a href="galeri.php">Galeri</a></li>
          <li><a href="news.php">Berita</a></li>
          <li><a href="regist.php">Pendaftaran</a></li>
          <li><a href="index.php#faq">FAQ</a></li>
        </ul>
      </nav>
    </header>
    <div class="article-container">
        <h1 class="article-title"><?php echo htmlspecialchars($artikel['judul_artikel']); ?></h1>
        <p class="article-meta">
            <span><i class="fas fa-user"></i> <?php echo htmlspecialchars($artikel['penulis']); ?></span>
            <span><i class="fas fa-calendar-alt"></i> <?php echo date('d F Y, H:i', strtotime($artikel['tanggal_publikasi'])); ?> WIB</span>
            <?php if (!empty($kategori_list)): ?>
                <span><i class="fas fa-tags"></i>
                <?php 
                $kat_links = [];
                foreach ($kategori_list as $kat) {
                    // Anda mungkin ingin membuat link ke halaman arsip kategori di sini
                    $kat_links[] = '<a href="news.php?kategori=' . htmlspecialchars($kat['slug_kategori']) . '">' . htmlspecialchars($kat['nama_kategori']) . '</a>';
                }
                echo implode(', ', $kat_links);
                ?>
                </span>
            <?php endif; ?>
        </p>

        <?php if (!empty($artikel['path_gambar_utama'])): ?>
            <img src="<?php echo BASE_URL_PUBLIC . '/' . htmlspecialchars($artikel['path_gambar_utama']); ?>" 
                 alt="<?php echo htmlspecialchars($artikel['judul_artikel']); ?>" 
                 class="article-main-image">
        <?php endif; ?>
        
        <div class="article-content">
            <?php echo nl2br($artikel['isi_artikel']); // Menggunakan nl2br jika kontennya teks biasa. 
                                                     // Jika kontennya HTML dari editor WYSIWYG, jangan gunakan nl2br. ?>
        </div>

        <?php if (!empty($artikel['tanggal_acara_mulai'])): // Jika ini adalah agenda/event ?>
            <div class="event-details">
                <h4>Detail Acara:</h4>
                <p><strong><i class="fas fa-calendar-day"></i> Tanggal:</strong> 
                    <?php echo date('d F Y', strtotime($artikel['tanggal_acara_mulai'])); ?>
                    <?php if(!empty($artikel['tanggal_acara_selesai']) && $artikel['tanggal_acara_selesai'] != $artikel['tanggal_acara_mulai']) echo " s.d. " . date('d F Y', strtotime($artikel['tanggal_acara_selesai'])); ?>
                </p>
                <?php if (!empty($artikel['waktu_acara'])): ?>
                    <p><strong><i class="fas fa-clock"></i> Waktu:</strong> <?php echo date('H:i', strtotime($artikel['waktu_acara'])); ?> WIB</p>
                <?php endif; ?>
                <?php if (!empty($artikel['lokasi_acara'])): ?>
                    <p><strong><i class="fas fa-map-marker-alt"></i> Lokasi:</strong> <?php echo htmlspecialchars($artikel['lokasi_acara']); ?></p>
                <?php endif; ?>
            </div>
        <?php endif; ?>
        
        <a href="news.php" class="back-to-news-link"><i class="fas fa-arrow-left"></i> Kembali ke Daftar Berita</a>
    </div>


    <div class="footer">
      <div class="footer-top">
        <div class="container">
          <div class="row">
            <div class="col-lg-3 col-md-6 footer-links">
              <h4>About Us</h4>
              <ul>
                <li>
                  <i class="ion-ios-arrow-forward"></i> <a href="#">Beranda</a>
                </li>
                <li>
                  <i class="ion-ios-arrow-forward"></i>
                  <a href="about.php">Tentang Kami</a>
                </li>
                <li>
                  <i class="ion-ios-arrow-forward"></i>
                  <a href="service.php">Layanan</a>
                </li>
                <li>
                  <i class="ion-ios-arrow-forward"></i>
                  <a href="activity.php">Kegiatan</a>
                </li>
                <li>
                  <i class="ion-ios-arrow-forward"></i>
                  <a href="galeri.php">Galeri</a>
                </li>
              </ul>
            </div>

            <div class="col-lg-3 col-md-6 footer-links">
              <h4>Useful Links</h4>
              <ul>
                <li>
                  <i class="ion-ios-arrow-forward"></i>
                  <a href="news.php">Berita</a>
                </li>
                <li>
                  <i class="ion-ios-arrow-forward"></i> <a href="#team">Tim Kami</a>
                </li>
                <li>
                  <i class="ion-ios-arrow-forward"></i>
                  <a href="prestasi.php">Prestasi</a>
                </li>
                <li>
                  <i class="ion-ios-arrow-forward"></i>
                  <a href="#contact">Hubungi Kami</a>
                </li>
                <li>
                  <i class="ion-ios-arrow-forward"></i> <a href="#faq">FAQ</a>
                </li>
              </ul>
            </div>

            <div
              class="col-lg-3 col-md-6 footer-contact"
              style="font-size: 1.5rem"
            >
              <h4>Contact Us</h4>
              <p>
                Balai desa Pandankrajan <br />
                Kecamatan Kemlagi<br />
                Kabupaten Mojokerto<br />
                JawaTimur Kode Pos:61352 <br />
                <strong>Phone:</strong> 081335525823 WhatsApp<br />
                <strong>Email:</strong> sekarkemuning.majapahit<br />
                @gmail.com<br />
              </p>

              <div class="social-links">
                <a href="https://www.facebook.com/"
                  ><i class="ion-logo-facebook"></i
                ></a>
                <a href="https://twitter.com/login?lang=en"
                  ><i class="ion-logo-twitter"></i
                ></a>
                <a href="https://www.linkedin.com/"
                  ><i class="ion-logo-linkedin"></i
                ></a>
                <a href="https://www.instagram.com/"
                  ><i class="ion-logo-instagram"></i
                ></a>
                <a
                  href="https://accounts.google.com/servicelogin/signinchooser?flowName=GlifWebSignIn&flowEntry=ServiceLogin"
                  ><i class="ion-logo-googleplus"></i
                ></a>
              </div>
            </div>

            <div class="col-lg-3 col-md-6 footer-newsletter">
              <h4>Seni Adalah Nafas Budaya</h4>
              <p>
                Kami percaya bahwa setiap gerak, bunyi, dan warna adalah warisan yang patut dijaga. Bersama, mari kita rawat jati diri bangsa melalui seni.
              </p>
            </div>
          </div>
        </div>
      </div>

      <div class="container">
        <div class="row align-items-center">
          <div
            class="col-md-6 copyright"
            style="color: #fff; font-size: 1.3rem"
          >
            Copyright &copy; 2024 Sanggar Sekar Kemuning. All Rights Reserved.
          </div>
        </div>
      </div>
    </div>
    <a href="#" class="back-to-top"><i class="ion-ios-arrow-up"></i></a>
    <?php
// Tutup koneksi database
if (isset($conn)) {
    close_connection($conn);
}
?>
</body>
</html>