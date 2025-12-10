<?php
// public/prestasi.php
require_once '../config/database.php';

// Ambil semua data prestasi yang aktif
$prestasi_items = [];
$sql = "SELECT * FROM Prestasi WHERE aktif = TRUE ORDER BY tanggal_prestasi DESC";
$result = mysqli_query($conn, $sql);
if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $prestasi_items[] = $row;
    }
}
$page_title_public = "Prestasi";
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
    <style>
        .prestasi-item {
            display: flex;
            background-color: #fff;
            margin-bottom: 30px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.08);
            border-radius: 8px;
            overflow: hidden;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }
        .prestasi-item:hover { transform: translateY(-5px); box-shadow: 0 8px 25px rgba(0,0,0,0.12); }
        .prestasi-gambar { flex: 0 0 250px; }
        .prestasi-gambar img { width: 100%; height: 100%; object-fit: cover; }
        .prestasi-konten { padding: 25px; }
        .prestasi-konten h3 { margin-top: 0; font-weight: 600; color: #333; }
        .prestasi-meta { color: #777; font-size: 0.9em; margin-bottom: 15px; }
        .prestasi-meta span { margin-right: 15px; }
        .prestasi-meta i { color: #F5C000; margin-right: 5px; }
        @media (max-width: 767px) {
            .prestasi-item { flex-direction: column; }
            .prestasi-gambar { flex-basis: 200px; }
        }
    </style>
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
      <h2>Beranda / Prestasi</h2>
      <div class="wave wave1"></div>
      <div class="wave wave2"></div>
      <div class="wave wave3"></div>
    </section>

    <section class="content-heading">
      <h1 class="heading">Prestasi Sanggar</h1>
      <p>Kumpulan pencapaian dan penghargaan yang telah diraih oleh Sanggar Sekar Kemuning dalam berbagai ajang.</p>
    </section>

    <section class="kegiatan-list-section py-5">
      <div class="container">
        <?php if (!empty($prestasi_items)): ?>
            <?php foreach($prestasi_items as $prestasi): ?>
            <div class="prestasi-item">
                <?php if (!empty($prestasi['gambar_prestasi'])): ?>
                <div class="prestasi-gambar">
                    <img src="<?php echo BASE_URL_PUBLIC . '/' . htmlspecialchars($prestasi['gambar_prestasi']); ?>" alt="<?php echo htmlspecialchars($prestasi['judul_prestasi']); ?>">
                </div>
                <?php endif; ?>
                <div class="prestasi-konten">
                    <h3><?php echo htmlspecialchars($prestasi['judul_prestasi']); ?></h3>
                    <div class="prestasi-meta">
                        <span><i class="fas fa-calendar-alt"></i> <?php echo date('d F Y', strtotime($prestasi['tanggal_prestasi'])); ?></span>
                        <?php if (!empty($prestasi['tingkat'])): ?>
                            <span><i class="fas fa-trophy"></i> <?php echo htmlspecialchars($prestasi['tingkat']); ?></span>
                        <?php endif; ?>
                         <?php if (!empty($prestasi['penyelenggara'])): ?>
                            <span><i class="fas fa-building"></i> <?php echo htmlspecialchars($prestasi['penyelenggara']); ?></span>
                        <?php endif; ?>
                    </div>
                    <p><?php echo nl2br(htmlspecialchars($prestasi['deskripsi_prestasi'])); ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <div class="text-center">
                <p>Belum ada data prestasi yang bisa ditampilkan.</p>
            </div>
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
</body>
</html>