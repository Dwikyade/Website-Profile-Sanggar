<?php
// public/service.php

require_once '../config/database.php'; // Sesuaikan path

// Ambil konten utama halaman dari tabel PengaturanHalaman
$pengaturan = [];
$sql_pengaturan = "SELECT nama_pengaturan, isi_pengaturan_teks FROM PengaturanHalaman WHERE nama_pengaturan LIKE 'service_page_%'";
$result_pengaturan = mysqli_query($conn, $sql_pengaturan);
if ($result_pengaturan) {
    while ($row = mysqli_fetch_assoc($result_pengaturan)) {
        $pengaturan[$row['nama_pengaturan']] = $row['isi_pengaturan_teks'];
    }
}

// Ambil semua item layanan yang aktif
$layanan_items = [];
$sql_layanan = "SELECT * FROM HalamanLayanan WHERE aktif = TRUE ORDER BY urutan_tampil ASC";
$result_layanan = mysqli_query($conn, $sql_layanan);
if ($result_layanan) {
    while ($row = mysqli_fetch_assoc($result_layanan)) {
        $layanan_items[] = $row;
    }
}

$page_title_public = "Layanan";
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Layanan - Sanggar Sekar Kemuning</title>

    <link rel="stylesheet" href="./css/style.css" />
    <link rel="stylesheet" href="./css/regist.css" />
    <link rel="stylesheet" href="./css/about.css" />
    <link rel="stylesheet" href="./css/services.css" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"
    />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="icon" href="./images/logo.png" />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css"
    />
    <link
      rel="stylesheet"
      href="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/css/bootstrap.min.css"
      integrity="sha384-TX8t27EcRE3e/ihU7zmQxVncDAy5uIKz4rEkgIXeMed4M0jlfIDPvg6uqKI2xXr2"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"
      integrity="sha384-wvfXpqpZZVQGK6TAh5PVlGOfQNHSoD2xbE+QkPxCAFlNEevoEH3Sl0sibVcOQVnN"
      crossorigin="anonymous"
    />
    <link href="lib/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
    <link href="lib/ionicons/css/ionicons.min.css" rel="stylesheet" />
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet" />
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet" />
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
      <h2>Beranda / Layanan</h2>
      <div class="wave wave1"></div>
      <div class="wave wave2"></div>
      <div class="wave wave3"></div>
    </section>

    <section id="services-page-heading" class="content-heading">
      <h1 class="heading"><?php echo htmlspecialchars($pengaturan['service_page_title'] ?? 'Layanan'); ?></h1>
      <p><?php echo htmlspecialchars($pengaturan['service_page_subtitle'] ?? '...'); ?></p>
    </section>

    <section class="services-list-section">
      <div class="container">
        <div class="row">
          <?php if (!empty($layanan_items)): ?>
            <?php foreach($layanan_items as $layanan): ?>
            <div class="col-lg-4 col-sm-6 mb-4"> <div class="item text-center h-100"> <?php if (!empty($layanan['ikon_path'])): ?>
                    <img src="<?php echo BASE_URL_PUBLIC . '/' . htmlspecialchars($layanan['ikon_path']); ?>" 
                         alt="<?php echo htmlspecialchars($layanan['judul']); ?>" class="icon-img" />
                <?php endif; ?>
                <h6><?php echo htmlspecialchars($layanan['judul']); ?></h6>
                <p><?php echo nl2br(htmlspecialchars($layanan['deskripsi_singkat'])); ?></p>
                
                <?php if(!empty($layanan['rincian_paragraf1'])): ?>
                <hr class="detail-separator-layanan" />
                <h4><strong><?php echo htmlspecialchars($layanan['rincian_judul']); ?></strong></h4>
                <div class="deskripsi-panjang-content" style="text-align: left;">
                    <p><?php echo nl2br(htmlspecialchars($layanan['rincian_paragraf1'])); ?></p>
                    
                    <?php
                    // Memecah list dari database menjadi <li>
                    if (!empty($layanan['rincian_list'])) {
                        $list_items = explode("\n", trim($layanan['rincian_list']));
                        echo '<ul>';
                        foreach ($list_items as $list_item) {
                            if (!empty(trim($list_item))) {
                                echo '<li>' . htmlspecialchars(trim($list_item)) . '</li>';
                            }
                        }
                        echo '</ul>';
                    }
                    ?>
                    
                    <p><?php echo nl2br(htmlspecialchars($layanan['rincian_paragraf2'])); ?></p>
                </div>
                <?php endif; ?>
              </div>
            </div>
            <?php endforeach; ?>
          <?php else: ?>
            <div class="col-12"><p class="text-center">Saat ini belum ada layanan yang tersedia.</p></div>
          <?php endif; ?>
        </div>
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

    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/jquery/jquery-migrate.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>
    <?php if (isset($conn)) { close_connection($conn); } ?>
  </body>
</html>
