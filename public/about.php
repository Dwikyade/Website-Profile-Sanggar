<?php
// public/about.php

require_once '../config/database.php'; // Sesuaikan path ke config

// Ambil konten dari database. Kita hanya butuh 1 baris data.
$sql = "SELECT * FROM HalamanTentangKami WHERE id = 1";
$result = mysqli_query($conn, $sql);
$about_content = mysqli_fetch_assoc($result);

if (!$about_content) {
    die("Konten untuk halaman 'Tentang Kami' belum diatur di database.");
}

$page_title_public = "Tentang Kami";
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Tentang Kami - Sanggar Sekar Kemuning</title>
    <link rel="stylesheet" href="./css/style.css" />
    <link rel="stylesheet" href="./css/regist.css" />
    <link rel="stylesheet" href="./css/about.css" />
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
          <li><a href="index.php">Beranda</a></li>
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
      <h2>Beranda / Tentang Kami</h2>
      <div class="wave wave1"></div>
      <div class="wave wave2"></div>
      <div class="wave wave3"></div>
    </section>

    <section id="about-us-heading" class="content-heading">
      <h1 class="heading"><?php echo htmlspecialchars($about_content['main_title']); ?></h1>
      <p><?php echo htmlspecialchars($about_content['main_subtitle']); ?></p>
    </section>

    <section class="about-us-content">
      <div class="container">
        <div class="row">
          <div class="col-md-7">
            <h2><?php echo htmlspecialchars($about_content['welcome_title']); ?></h2>
            <p><?php echo nl2br(htmlspecialchars($about_content['welcome_p1'])); ?></p>
            <p><?php echo nl2br(htmlspecialchars($about_content['welcome_p2'])); ?></p>
          </div>
          <div class="col-md-5 align-self-center">
            <?php if (!empty($about_content['welcome_image_path'])): ?>
                <img src="<?php echo BASE_URL_PUBLIC . '/' . htmlspecialchars($about_content['welcome_image_path']); ?>" 
                     alt="Profil Sanggar Sekar Kemuning" class="img-fluid" />
            <?php endif; ?>
          </div>
        </div>

        <hr class="my-5" />

        <div class="row">
          <div class="col-md-12">
            <h3><?php echo htmlspecialchars($about_content['vision_mission_title']); ?></h3>
            <?php
            if (!empty($about_content['vision_mission_list'])) {
                $vision_mission_items = explode("\n", trim($about_content['vision_mission_list']));
                echo '<ul>';
                foreach ($vision_mission_items as $item) {
                    $trimmed_item = trim($item);
                    if (!empty($trimmed_item)) {
                        echo '<li>' . htmlspecialchars($trimmed_item) . '</li>';
                    }
                }
                echo '</ul>';
            }
            ?>
          </div>
        </div>

        <hr class="my-5" />

        <div class="row">
          <div class="col-md-5 align-self-center order-md-2">
            <?php if (!empty($about_content['history_image_path'])): ?>
                <img src="<?php echo BASE_URL_PUBLIC . '/' . htmlspecialchars($about_content['history_image_path']); ?>" 
                     alt="Sejarah Sanggar Sekar Kemuning" class="img-fluid" />
            <?php endif; ?>
          </div>
          <div class="col-md-7 order-md-1">
            <h3><?php echo htmlspecialchars($about_content['history_title']); ?></h3>
            <p><?php echo nl2br(htmlspecialchars($about_content['history_p1'])); ?></p>
            <p><?php echo nl2br(htmlspecialchars($about_content['history_p2'])); ?></p>
          </div>
        </div>
        <hr class="my-5" />
        <div class="row">
          <div class="col-md-12 text-center">
            <h3><?php echo htmlspecialchars($about_content['cta_title']); ?></h3>
            <p style="text-align: center"><?php echo htmlspecialchars($about_content['cta_subtitle']); ?></p>
            <a href="prestasi.php" class="btn btn-primary btn-lg mt-3">Prestasi Kami</a>
          </div>
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
