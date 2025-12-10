
<?php
// public/regist.php
require_once '../config/database.php'; // Sesuaikan path

$page_title_public = "Pendaftaran";
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title><?php echo htmlspecialchars($page_title_public); ?> - Sanggar Sekar Kemuning</title>
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/regist.css" /> <link rel="icon" href="images/logo.png" />
    <style>
        /* CSS untuk Tab dan Form */
        .tabs-container { margin-bottom: 30px; border-bottom: 2px solid #eee; }
        .tab-link {
            padding: 10px 20px;
            cursor: pointer;
            display: inline-block;
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            border-bottom: none;
            margin-bottom: -2px;
            font-size: 1.1em;
            font-weight: 500;
        }
        .tab-link.active {
            background-color: #fff;
            border-color: #dee2e6 #dee2e6 #fff;
            color: var(--admin-primary-color, #F5C000);
        }
        .form-content { display: none; }
        .form-content.active { display: block; }
        .form-container-custom {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        }
    </style>
</head>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Sanggar Sekar Kemuning</title>
    <link rel="stylesheet" href="./css/regist.css" />
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
      <h2>Beranda / Pendaftaran</h2>
      <div class="wave wave1"></div>
      <div class="wave wave2"></div>
      <div class="wave wave3"></div>
    </section>

    <section id="career-heading" class="career-heading">
      <h1 class="heading">Formulir Pendaftaran & Pemesanan</h1>
      <p>Silakan pilih jenis formulir yang sesuai dengan kebutuhan Anda di bawah ini.</p>
    </section>
    
    <div class="container my-5">
       <div id="notifikasi-pendaftaran"> <?php
        if (isset($_SESSION['pesan_registrasi'])) {
        $status_registrasi = $_SESSION['pesan_registrasi']['status']; // 'sukses' atau 'error'
        $pesan_registrasi = $_SESSION['pesan_registrasi']['pesan'];
        
        if ($status_registrasi == 'sukses') {
            $ikon_class = 'fas fa-check-circle';
            $judul_notif = 'Berhasil Terkirim!';
        } else {
            $ikon_class = 'fas fa-exclamation-triangle';
            $judul_notif = 'Terjadi Kesalahan';
        }

        echo '<div class="notif-toast ' . htmlspecialchars($status_registrasi) . '" id="notifikasi-toast">' .
                '<div class="ikon"><i class="' . $ikon_class . '"></i></div>' .
                '<div class="konten">' .
                    '<p class="judul-notif">' . $judul_notif . '</p>' .
                    '<p class="pesan-notif">' . htmlspecialchars($pesan_registrasi) . '</p>' .
                '</div>' .
                '<button type="button" class="close-btn" onclick="this.parentElement.remove();">&times;</button>' .
             '</div>';
        
        unset($_SESSION['pesan_registrasi']); // Hapus pesan setelah ditampilkan
    }
    ?>
        </div>
        <div class="tabs-container">
            <div class="tab-link active" onclick="openForm(event, 'MuridBaru')">Daftar Murid Baru</div>
            <div class="tab-link" onclick="openForm(event, 'PesanJasa')">Pesan Jasa</div>
        </div>

        <div id="MuridBaru" class="form-content active">
            <div class="form-container-custom">
                <h3>Formulir Pendaftaran Murid Baru</h3>
                <p>Isi formulir di bawah ini untuk mendaftar sebagai anggota baru sanggar.</p>
                <hr>
                <form action="proses_regist.php" method="POST">
                    <input type="hidden" name="jenis_form" value="murid_baru">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nama_lengkap">Nama Lengkap</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nama_panggilan">Nama Panggilan</label>
                            <input type="text" class="form-control" id="nama_panggilan" name="nama_panggilan">
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Jenis Kelamin</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="laki_laki" value="Laki-laki">
                            <label class="form-check-label" for="laki_laki">Laki-laki</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="jenis_kelamin" id="perempuan" value="Perempuan">
                            <label class="form-check-label" for="perempuan">Perempuan</label>
                        </div>
                    </div>
                     <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tempat_lahir">Tempat Lahir</label>
                            <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir">
                        </div>
                        <div class="form-group col-md-6">
                            <label for="tanggal_lahir">Tanggal Lahir</label>
                            <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="alamat_lengkap">Alamat Lengkap</label>
                        <textarea class="form-control" id="alamat_lengkap" name="alamat_lengkap" rows="3" required></textarea>
                    </div>
                     <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nomor_telepon_siswa">Nomor Telepon/WA Siswa</label>
                            <input type="tel" class="form-control" id="nomor_telepon_siswa" name="nomor_telepon" required>
                        </div>
                         <div class="form-group col-md-6">
                            <label for="email_siswa">Email Siswa (Opsional)</label>
                            <input type="email" class="form-control" id="email_siswa" name="email">
                        </div>
                    </div>
                     <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nama_wali">Nama Orang Tua/Wali</label>
                            <input type="text" class="form-control" id="nama_wali" name="nama_wali">
                        </div>
                         <div class="form-group col-md-6">
                            <label for="telepon_wali">Nomor Telepon/WA Orang Tua/Wali</label>
                            <input type="tel" class="form-control" id="telepon_wali" name="telepon_wali">
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="pilihan_kelas">Pilihan Kelas yang Diinginkan</label>
                        <select class="form-control" id="pilihan_kelas" name="pilihan_kelas" required>
                            <option value="">-- Pilih Kelas --</option>
                            <option value="Tari Tradisional Anak">Tari Tradisional (Anak)</option>
                            <option value="Tari Tradisional Remaja/Dewasa">Tari Tradisional (Remaja/Dewasa)</option>
                            <option value="Karawitan">Karawitan</option>
                            <option value="Fotografi">Fotografi</option>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Pendaftaran</button>
                </form>
            </div>
        </div>

        <div id="PesanJasa" class="form-content">
            <div class="form-container-custom">
                <h3>Formulir Pemesanan Jasa</h3>
                <p>Gunakan formulir ini untuk menyewa kostum, rias, atau jasa dokumentasi kami.</p>
                <hr>
                <form action="proses_regist.php" method="POST">
                    <input type="hidden" name="jenis_form" value="pesan_jasa">
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="nama_pemesan">Nama Pemesan/Penanggung Jawab</label>
                            <input type="text" class="form-control" id="nama_pemesan" name="nama_pemesan" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="nomor_telepon_pemesan">Nomor Telepon/WA</label>
                            <input type="tel" class="form-control" id="nomor_telepon_pemesan" name="nomor_telepon" required>
                        </div>
                    </div>
                     <div class="form-group">
                        <label for="email_pemesan">Email (Opsional)</label>
                        <input type="email" class="form-control" id="email_pemesan" name="email">
                    </div>
                    <div class="form-group">
                        <label for="jenis_jasa">Jenis Jasa yang Dipesan</label>
                        <select class="form-control" id="jenis_jasa" name="jenis_jasa" required>
                            <option value="">-- Pilih Jasa --</option>
                            <option value="Rias Panggung / Acara Khusus">Rias Panggung / Acara Khusus</option>
                            <option value="Sewa Kostum Tari">Sewa Kostum Tari</option>
                            <option value="Dokumentasi Fotografi">Dokumentasi Fotografi</option>
                            <option value="Dokumentasi Videografi">Dokumentasi Videografi</option>
                            <option value="Lainnya">Lainnya (Jelaskan di bawah)</option>
                        </select>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="tanggal_acara">Tanggal Acara</label>
                            <input type="date" class="form-control" id="tanggal_acara" name="tanggal_acara" required>
                        </div>
                         <div class="form-group col-md-6">
                            <label for="lokasi_acara">Lokasi Acara</label>
                            <input type="text" class="form-control" id="lokasi_acara" name="lokasi_acara">
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="deskripsi_kebutuhan">Deskripsi Kebutuhan</label>
                        <textarea class="form-control" id="deskripsi_kebutuhan" name="deskripsi_kebutuhan" rows="4" required placeholder="Jelaskan secara singkat kebutuhan Anda, misal: 'Sewa 5 kostum tari remo untuk acara kantor' atau 'Jasa rias untuk 3 penari acara pernikahan'."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Kirim Pemesanan</button>
                </form>
            </div>
        </div>
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

    <script src="lib/jquery/jquery.min.js"></script>
    <script src="lib/jquery/jquery-migrate.min.js"></script>
    <script src="lib/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/counterup/counterup.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/lightbox/js/lightbox.min.js"></script>

    <script>
    // JavaScript untuk fungsi Tab
    function openForm(evt, formName) {
        var i, formcontent, tablinks;
        formcontent = document.getElementsByClassName("form-content");
        for (i = 0; i < formcontent.length; i++) {
            formcontent[i].style.display = "none";
        }
        tablinks = document.getElementsByClassName("tab-link");
        for (i = 0; i < tablinks.length; i++) {
            tablinks[i].className = tablinks[i].className.replace(" active", "");
        }
        document.getElementById(formName).style.display = "block";
        evt.currentTarget.className += " active";
    }
    </script>
  </body>
</html>
