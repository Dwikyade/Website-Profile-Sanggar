<?php
// news.php

// 1. SESUAIKAN PATH KE FILE KONEKSI DATABASE ANDA
// Jika news.php ada di root, dan folder config sejajar, maka 'config/database.php'
// Jika news.php ada di public/, dan folder config di root, maka '../config/database.php'
require_once '../config/database.php'; // Harap sesuaikan path ini jika news.php TIDAK di dalam folder public/

// --- PENGUMUMAN PENTING (STICKY) ---
$sql_sticky = "SELECT judul_artikel, slug_artikel, kutipan_artikel, path_gambar_utama 
               FROM ArtikelBerita  -- Pastikan nama tabel konsisten (ArtikelBerita atau artikelberita)
               WHERE status_publikasi = 'terbit' AND apakah_pengumuman_sticky = TRUE 
               ORDER BY tanggal_publikasi DESC LIMIT 1";
$result_sticky = mysqli_query($conn, $sql_sticky);
$sticky_announcement = null; // Inisialisasi
if ($result_sticky) {
    $sticky_announcement = mysqli_fetch_assoc($result_sticky);
}


// --- AGENDA KEGIATAN MENDATANG (Contoh: 2 agenda terdekat) ---
$sql_agenda = "SELECT judul_artikel, slug_artikel, tanggal_acara_mulai, tanggal_acara_selesai, waktu_acara, lokasi_acara, kutipan_artikel
               FROM ArtikelBerita -- Pastikan nama tabel konsisten
               WHERE status_publikasi = 'terbit' AND tanggal_acara_mulai >= CURDATE() 
               ORDER BY tanggal_acara_mulai ASC LIMIT 2";
$result_agenda = mysqli_query($conn, $sql_agenda);

// --- DAFTAR BERITA UTAMA (dengan Paginasi) ---
$berita_per_halaman = 3; 
$halaman_sekarang = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
if ($halaman_sekarang < 1) $halaman_sekarang = 1;
$offset = ($halaman_sekarang - 1) * $berita_per_halaman;

$sql_total_berita = "SELECT COUNT(*) AS total FROM ArtikelBerita WHERE status_publikasi = 'terbit' AND apakah_pengumuman_sticky = FALSE";
$result_total_berita = mysqli_query($conn, $sql_total_berita);
$total_berita = 0;
if ($result_total_berita) {
    $data_total_berita = mysqli_fetch_assoc($result_total_berita);
    $total_berita = $data_total_berita['total'];
}
$total_halaman = $total_berita > 0 ? ceil($total_berita / $berita_per_halaman) : 0;

$sql_berita_utama = "SELECT id_artikel, judul_artikel, slug_artikel, kutipan_artikel, path_gambar_utama, penulis, tanggal_publikasi 
                     FROM ArtikelBerita 
                     WHERE status_publikasi = 'terbit' AND apakah_pengumuman_sticky = FALSE
                     ORDER BY tanggal_publikasi DESC 
                     LIMIT ?, ?"; // Menggunakan placeholder untuk prepared statement
$stmt_berita_utama = mysqli_prepare($conn, $sql_berita_utama);
mysqli_stmt_bind_param($stmt_berita_utama, "ii", $offset, $berita_per_halaman);
mysqli_stmt_execute($stmt_berita_utama);
$result_berita_utama = mysqli_stmt_get_result($stmt_berita_utama);

$page_title_public = "Berita & Agenda"; // Untuk judul tab browser
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($page_title_public); ?> - Sanggar Sekar Kemuning</title>

    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="./css/regist.css" />
    <link rel="stylesheet" href="./css/about.css" />
    <link rel="stylesheet" href="./css/services.css" />
    <link rel="stylesheet" href="css/galeri.css" /> 
     <link rel="stylesheet" href="css/news.css" /> 
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="icon" href="images/logo.png" /> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css"/>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css" integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2" crossorigin="anonymous"/>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN" crossorigin="anonymous"/>
    <script src="./js/main.js"></script>
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

    <section id="page-banner" class="home"> 
        <h1 class="heading">Beranda / Berita & Agenda</h1>
        <div class="wave wave1"></div>
      <div class="wave wave2"></div>
      <div class="wave wave3"></div>
    </section>

    <section id="berita-page-heading" class="content-heading"> 
      <h1 class="heading">Berita Terbaru & Agenda Kegiatan</h1>
      <p>
        Ikuti informasi terkini mengenai pendaftaran, jadwal kegiatan,
        pementasan, dan berbagai penawaran menarik dari Sanggar Sekar Kemuning.
      </p>
    </section>

    <section class="berita-section">
      <div class="container">
        
        <?php if ($sticky_announcement): ?>
        <div class="pengumuman-penting"> 
          <h2 class="sub-heading">
             <?php echo htmlspecialchars($sticky_announcement['judul_artikel']); ?>
          </h2> 
          <?php if (!empty($sticky_announcement['path_gambar_utama'])): 
                // KOREKSI PATH GAMBAR STICKY
                $url_gambar_sticky = BASE_URL_PUBLIC . '/' . htmlspecialchars($sticky_announcement['path_gambar_utama']);
          ?>
          <img src="<?php echo $url_gambar_sticky; ?>" 
               alt="<?php echo htmlspecialchars($sticky_announcement['judul_artikel']); ?>"
               class="img-fluid mb-3" />
          <?php else: ?>
          <img src="<?php echo BASE_URL_PUBLIC; ?>/images/home.png" alt="Pendaftaran Sanggar Sekar Kemuning" class="img-fluid mb-3"/> <?php endif; ?>

          <p><?php echo nl2br(htmlspecialchars($sticky_announcement['kutipan_artikel'])); ?></p>
          <a href="artikel.php?slug=<?php echo htmlspecialchars($sticky_announcement['slug_artikel']); ?>" class="btn">
            Info Lengkap & Formulir Pendaftaran
          </a>
        </div>
        <?php endif; ?>
        
        <?php if ($result_agenda && mysqli_num_rows($result_agenda) > 0): ?>
        <div class="agenda-kegiatan"> 
          <h2 class="sub-heading">Agenda Kegiatan Mendatang</h2>
          <div class="row">
            <?php while($agenda = mysqli_fetch_assoc($result_agenda)): ?>
            <div class="col-md-6">
              <div class="agenda-item"> 
                <h4><a href="artikel.php?slug=<?php echo htmlspecialchars($agenda['slug_artikel']); ?>" style="color:inherit; text-decoration:none;"><?php echo htmlspecialchars($agenda['judul_artikel']); ?></a></h4>
                <p><i class="fas fa-calendar-alt"></i> 
                    <?php echo date('d M Y', strtotime($agenda['tanggal_acara_mulai'])); ?>
                    <?php if($agenda['tanggal_acara_selesai'] && $agenda['tanggal_acara_selesai'] != $agenda['tanggal_acara_mulai']) echo " - " . date('d M Y', strtotime($agenda['tanggal_acara_selesai'])); ?>
                </p>
                <?php if($agenda['waktu_acara']): ?>
                <p><i class="fas fa-clock"></i> <?php echo date('H:i', strtotime($agenda['waktu_acara'])); ?> WIB</p>
                <?php endif; ?>
                <?php if($agenda['lokasi_acara']): ?>
                <p><i class="fas fa-map-marker-alt"></i> <?php echo htmlspecialchars($agenda['lokasi_acara']); ?></p>
                <?php endif; ?>
                <p><?php echo nl2br(htmlspecialchars($agenda['kutipan_artikel'])); ?></p>
                <a href="artikel.php?slug=<?php echo htmlspecialchars($agenda['slug_artikel']); ?>" class="read-more-link" style="font-size:1.3rem; color:#F5C000;">Detail Acara &rarr;</a>
              </div>
            </div>
            <?php endwhile; ?>
          </div>
        </div>
        <?php endif; ?>
        
        <div class="berita-terbaru"> 
          <h2 class="sub-heading">Berita & Informasi Lainnya</h2>
          <div class="row">
            <?php if ($result_berita_utama && mysqli_num_rows($result_berita_utama) > 0): ?>
                <?php while($berita = mysqli_fetch_assoc($result_berita_utama)): ?>
                <div class="col-md-12"> 
                  <article class="berita-post"> 
                    <div class="row">
                      <div class="col-md-4">
                        <div class="berita-thumb-container"> 
                          <a href="artikel.php?slug=<?php echo htmlspecialchars($berita['slug_artikel']); ?>">
                            <?php if (!empty($berita['path_gambar_utama'])): 
                                  // KOREKSI PATH GAMBAR BERITA UTAMA
                                  $url_gambar_berita = BASE_URL_PUBLIC . '/' . htmlspecialchars($berita['path_gambar_utama']);
                            ?>
                            <img src="<?php echo $url_gambar_berita; ?>"
                                 alt="<?php echo htmlspecialchars($berita['judul_artikel']); ?>"
                                 class="berita-thumb" />
                            <?php else: ?>
                            <img src="<?php echo BASE_URL_PUBLIC; ?>/images/placeholder_berita.png" alt="Berita Sekar Kemuning" class="berita-thumb"/> <?php endif; ?>
                          </a>
                        </div>
                      </div>
                      <div class="col-md-8">
                        <div class="berita-content"> 
                          <h3>
                            <a href="artikel.php?slug=<?php echo htmlspecialchars($berita['slug_artikel']); ?>" style="color:inherit; text-decoration:none;">
                                <?php echo htmlspecialchars($berita['judul_artikel']); ?>
                            </a>
                          </h3>
                          <p class="berita-meta"> 
                            <i class="fas fa-calendar-alt"></i> <?php echo date('d F Y', strtotime($berita['tanggal_publikasi'])); ?>
                            <?php if(!empty($berita['penulis'])): ?>
                                <span class="d-none d-sm-inline">|</span>
                                <br class="d-sm-none" /><i class="fas fa-user"></i> <?php echo htmlspecialchars($berita['penulis']); ?>
                            <?php endif; ?>
                          </p>
                          <p class="excerpt"> <?php echo nl2br(htmlspecialchars($berita['kutipan_artikel'])); ?></p>
                          <a href="artikel.php?slug=<?php echo htmlspecialchars($berita['slug_artikel']); ?>" class="read-more-link" style="font-size:1.4rem; color:#F5C000; font-weight:bold;">
                            Baca Selengkapnya &rarr;
                          </a>
                        </div>
                      </div>
                    </div>
                  </article>
                </div>
                <?php endwhile; ?>
            <?php else: ?>
                <div class="col-md-12">
                    <p style="text-align:center; padding: 20px; font-size:1.6rem;">Belum ada berita untuk ditampilkan.</p>
                </div>
            <?php endif; ?>
          </div>
        </div>
        
        <?php if ($total_halaman > 1): ?>
        <nav class="pagination-nav">
            <?php if ($halaman_sekarang > 1): ?>
                <a href="?halaman=<?php echo $halaman_sekarang - 1; ?>">&laquo; Sebelumnya</a>
            <?php endif; ?>

            <?php for ($i = 1; $i <= $total_halaman; $i++): ?>
                <?php if ($i == $halaman_sekarang): ?>
                    <span class="active"><?php echo $i; ?></span>
                <?php else: ?>
                    <a href="?halaman=<?php echo $i; ?>"><?php echo $i; ?></a>
                <?php endif; ?>
            <?php endfor; ?>

            <?php if ($halaman_sekarang < $total_halaman): ?>
                <a href="?halaman=<?php echo $halaman_sekarang + 1; ?>">Berikutnya &raquo;</a>
            <?php endif; ?>
        </nav>
        <?php endif; ?>
        
      </div>
    </section>

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
  </body>
    
<?php
// Tutup koneksi database
if (isset($conn) && $conn instanceof mysqli) { // Tambah pengecekan instanceof mysqli
    mysqli_close($conn);
}
?>
  </body>
</html>