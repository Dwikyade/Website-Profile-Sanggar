# 🎭 Sistem Informasi & Website Dinamis Sanggar Seni Sekar Kemuning

![Tech Stack](https://img.shields.io/badge/PHP-7.4%2B-777BB4?style=flat&logo=php&logoColor=white)
![Tech Stack](https://img.shields.io/badge/MySQL-Database-4479A1?style=flat&logo=mysql&logoColor=white)
![Tech Stack](https://img.shields.io/badge/Bootstrap-4-563D7C?style=flat&logo=bootstrap&logoColor=white)

Sebuah platform web dinamis yang dirancang untuk **Sanggar Seni Sekar Kemuning**. Proyek ini mentransformasi profil sanggar yang sebelumnya statis menjadi sistem manajemen konten (CMS) yang hidup, memungkinkan pengelolaan berita, galeri multimedia, dan pendaftaran anggota secara mandiri dan *real-time*.

---

## 📸 Screenshot

*(Tampilan Beranda, Galeri, dan Dashboard Admin)*

| Halaman Beranda | Dashboard Admin |
| :---: | :---: |
| ![Home](screenshot/home_preview.png) | ![Admin](screenshot/admin_preview.png) |

---

## ✨ Fitur Utama

### 🌍 Halaman Publik (Frontend)
* **Beranda Dinamis:** Menampilkan slider berita terbaru, galeri terkini, dan statistik sanggar secara otomatis.
* **Galeri Multimedia:** Mendukung **Foto & Video** (YouTube/Vimeo) menggunakan *GLightbox*, dikelompokkan berdasarkan kategori album.
* **Berita & Artikel:** Blog kegiatan sanggar dengan fitur *Sticky Post* untuk pengumuman penting.
* **Pendaftaran Online:**
    * Formulir Pendaftaran Murid Baru.
    * Formulir Pemesanan Jasa (Sewa Kostum/Rias).
* **Profil & Layanan:** Halaman informatif tentang sejarah, visi-misi, dan layanan sanggar.

### 🔐 Dashboard Admin (Backend)
* **Keamanan:** Sistem login aman dengan *Password Hashing* dan proteksi sesi.
* **CRUD Lengkap:** Kelola data Berita, Galeri, Kategori, Layanan, Prestasi, dan Tim.
* **Manajemen Galeri Canggih:** Upload foto thumbnail dan integrasi link video eksternal dalam satu form.
* **Manajemen Pendaftar:** Melihat dan mengelola data masuk dari calon murid dan pemesan jasa.
* **Pengaturan Halaman:** Edit teks Halaman Beranda dan Tentang Kami langsung dari admin tanpa coding.

---

## 🛠️ Teknologi yang Digunakan

* **Backend:** PHP (Native), MySQL (Prepared Statements untuk keamanan).
* **Frontend:** HTML5, CSS3, JavaScript (jQuery).
* **Styling Framework:** Bootstrap 4.
* **Libraries:**
    * *GLightbox* (Untuk tampilan popup Galeri Foto & Video).
    * *OwlCarousel* (Untuk slider berita dan testimoni).
    * *FontAwesome* (Ikon).
* **Server Environment:** XAMPP (Apache/MySQL).

---

## ⚙️ Instalasi & Cara Menjalankan

Ikuti langkah-langkah ini untuk menjalankan proyek di komputer lokal Anda:

1.  **Clone Repositori**
    ```bash
    git clone [https://github.com/username-anda/sanggar-sekar-kemuning.git](https://github.com/username-anda/sanggar-sekar-kemuning.git)
    ```
2.  **Pindahkan Folder**
    Pindahkan folder proyek ke dalam direktori server lokal Anda (misal: `C:\xampp\htdocs\PROJECT-WEB-2025`).

3.  **Import Database**
    * Buka phpMyAdmin (`http://localhost/phpmyadmin`).
    * Buat database baru dengan nama `sanggar_sekar_kemuning_db`.
    * Import file `database/sanggar_sekar_kemuning_db.sql` yang ada di dalam folder proyek ini.

4.  **Konfigurasi Database**
    * Buka file `config/database.php`.
    * Sesuaikan pengaturan berikut jika berbeda dengan setup lokal Anda:
        ```php
        define('DB_HOST', 'localhost');
        define('DB_USER', 'root');
        define('DB_PASS', ''); // Kosongkan jika default XAMPP
        define('DB_NAME', 'sanggar_sekar_kemuning_db');
        define('SITE_ROOT_URL', '/PROJECT-WEB-2025'); // Sesuaikan dengan nama folder di htdocs
        ```

5.  **Jalankan**
    * Buka browser dan akses: `http://localhost/PROJECT-WEB-2025/public/`

---

└── database/ # File SQL Database

## 📁 Struktur Folder 

```bash
PROJECT-WEB-2025/ 
├── admin/ # Halaman Dashboard & Modul Manajemen (CRUD) 
├── config/ # Koneksi Database & Konstanta 
├── public/ # Halaman Publik (Frontend) 
│ ├── css/ # Stylesheet 
│ ├── images/ # Aset Gambar & Upload User 
│ ├── js/ # Script JavaScript 
│ └── lib/ # Library (GLightbox, OwlCarousel)
└── database/ # File SQL Database
```

---

## 👤 Akun Demo Admin

Untuk masuk ke dashboard admin, gunakan kredensial berikut (jika menggunakan database bawaan):

* **URL Login:** `http://localhost/PROJECT-WEB-2025/admin/login.php`
* **Username:** `admin`
* **Password:** `admin123` *(Atau sesuaikan dengan yang Anda buat)*

---

## 🤝 Kontribusi & Kredit

Proyek ini dikembangkan sebagai bagian dari **Tugas Matakuliah Sekaligus Sebagai Portofolio Saya**.
Dikembangkan oleh **Dwiky Ade**.

Terima kasih khusus kepada pengurus **Sanggar Sekar Kemuning** atas kerjasama dan masukannya dalam pengembangan sistem ini.

---







