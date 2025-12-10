<?php
// public/galeri.php
require_once '../config/database.php'; // Sesuaikan path

// Ambil semua kategori galeri yang aktif dan memiliki item
$sql_kategori = "SELECT kg.id_kategori_galeri, kg.nama_kategori, kg.slug_kategori
                 FROM KategoriGaleri kg
                 WHERE EXISTS (SELECT 1 FROM ItemGaleri ig WHERE ig.id_kategori_galeri = kg.id_kategori_galeri AND ig.aktif = TRUE)
                 ORDER BY kg.urutan_tampil ASC, kg.nama_kategori ASC";
$result_kategori = mysqli_query($conn, $sql_kategori);

$page_title_public = "Galeri";
?>
<!DOCTYPE html>
<html lang="id">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo isset($page_title_public) ? htmlspecialchars($page_title_public) . " - Sanggar Sekar Kemuning" : "Sanggar Sekar Kemuning"; ?></title>

    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="./css/regist.css" />
    <link rel="stylesheet" href="./css/about.css" />
    <link rel="stylesheet" href="./css/services.css" />
    <link rel="stylesheet" href="css/galeri.css" /> 
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css"/>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <link rel="icon" href="images/logo.png" /> <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.1.3/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/ionicons/2.0.1/css/ionicons.min.css"/>
    <link rel="stylesheet" href="lib/owlcarousel/assets/owl.carousel.min.css" /> <link rel="stylesheet" href="lib/owlcarousel/assets/owl.theme.default.min.css" />
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet" />
    <link href="lib/lightbox/css/lightbox.min.css" rel="stylesheet" />
    <script src="./js/main.js"></script>
  
    
    <style>
        /* Gaya untuk ikon play di atas thumbnail video */
        .galeri-item a { position: relative; display: block; }
        .play-icon-overlay {
            position: absolute; top: 0; left: 0; width: 100%; height: 100%;
            display: flex; align-items: center; justify-content: center;
            background-color: rgba(0, 0, 0, 0.3);
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        .galeri-item a:hover .play-icon-overlay { opacity: 1; }
        .play-icon-overlay i { font-size: 4rem; color: rgba(255, 255, 255, 0.9); }
        .galeri-carousel .galeri-item img.img-fluid { height: 280px; object-fit: cover; }
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
      <h2>Beranda / Galeri</h2>
      <div class="wave wave1"></div>
      <div class="wave wave2"></div>
      <div class="wave wave3"></div>
    </section>
    <section id="galeri-page-heading" class="content-heading">
      <h1 class="heading">Galeri Sanggar Sekar Kemuning</h1>
      <p>Jelajahi momen dan karya seni dari berbagai kegiatan kami yang terdokumentasi dengan indah.</p>
    </section>

    <section class="galeri-section">
      <div class="container">
        <?php if ($result_kategori && mysqli_num_rows($result_kategori) > 0): ?>
            <?php while($kategori = mysqli_fetch_assoc($result_kategori)): ?>
                <div class="galeri-kategori">
                    <h2 class="kategori-judul"><?php echo htmlspecialchars($kategori['nama_kategori']); ?></h2>
                    
                    <div class="owl-carousel owl-theme galeri-carousel">
                        <?php
                        $id_kat = (int)$kategori['id_kategori_galeri'];
                        $sql_items = "SELECT tipe_media, judul_item, path_gambar_thumb, path_gambar_full, url_video, alt_text_gambar 
                                      FROM ItemGaleri 
                                      WHERE id_kategori_galeri = ? AND aktif = TRUE 
                                      ORDER BY urutan_tampil ASC, id_item_galeri DESC";
                        
                        $stmt_items = mysqli_prepare($conn, $sql_items);
                        if ($stmt_items) {
                            mysqli_stmt_bind_param($stmt_items, "i", $id_kat);
                            mysqli_stmt_execute($stmt_items);
                            $result_items = mysqli_stmt_get_result($stmt_items);

                            if ($result_items && mysqli_num_rows($result_items) > 0):
                                while($item = mysqli_fetch_assoc($result_items)):
                                    $path_thumb = BASE_URL_PUBLIC . '/' . htmlspecialchars($item['path_gambar_thumb']);
                                    $alt_text = htmlspecialchars($item['alt_text_gambar'] ? $item['alt_text_gambar'] : $item['judul_item']);
                                    $judul_lightbox = htmlspecialchars($item['judul_item']);
                        ?>
                                    <div class="galeri-item">
                                        <?php if ($item['tipe_media'] == 'Video'): ?>
                                            <a href="<?php echo htmlspecialchars($item['url_video']); ?>" 
                                               class="glightbox" 
                                               data-gallery="galeri-<?php echo htmlspecialchars($kategori['slug_kategori']); ?>"
                                               data-title="<?php echo $judul_lightbox; ?>">
                                                <img src="<?php echo $path_thumb; ?>" alt="<?php echo $alt_text; ?>" class="img-fluid" />
                                                <div class="play-icon-overlay"><i class="fas fa-play-circle"></i></div>
                                            </a>
                                        <?php else: ?>
                                            <?php $path_full = BASE_URL_PUBLIC . '/' . htmlspecialchars($item['path_gambar_full']); ?>
                                            <a href="<?php echo $path_full; ?>" 
                                               class="glightbox" 
                                               data-gallery="galeri-<?php echo htmlspecialchars($kategori['slug_kategori']); ?>" 
                                               data-title="<?php echo $judul_lightbox; ?>">
                                                <img src="<?php echo $path_thumb; ?>" alt="<?php echo $alt_text; ?>" class="img-fluid" />
                                            </a>
                                        <?php endif; ?>
                                    </div>
                        <?php 
                                endwhile;
                            endif;
                            mysqli_stmt_close($stmt_items);
                        }
                        ?>
                    </div> </div> <?php endwhile; ?>
        <?php endif; ?>
      </div> </section>

    <div class="footer">
      </div>
    
    <a href="#" class="back-to-top"><i class="ion-ios-arrow-up"></i></a>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    
    <script src="lib/glightbox/js/glightbox.min.js"></script>
    
    <script>
    $(document).ready(function(){
        // Inisialisasi Owl Carousel (tetap sama)
        $('.galeri-carousel').owlCarousel({
            loop: false,
            margin: 30,
            nav: true,
            dots: true,
            navText: [ '<i class="fas fa-chevron-left"></i>', '<i class="fas fa-chevron-right"></i>' ],
            responsive:{ 0:{ items:1 }, 600:{ items:2 }, 992:{ items:3 } }
        });

        // Inisialisasi GLightbox
        const lightbox = GLightbox({
            selector: '.glightbox', // Menargetkan semua link dengan kelas 'glightbox'
            touchNavigation: true,
            loop: true,          // Bisa navigasi dari video terakhir ke video pertama dalam satu galeri
            autoplayVideos: true // Video langsung diputar saat dibuka
        });
    });
    </script>
</body>
</html>
<?php
if (isset($conn)) { close_connection($conn); }
?>