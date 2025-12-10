<?php
require_once '../config/database.php'; // Sesuaikan path

// Cek jenis form yang disubmit
if (isset($_POST['jenis_form'])) {
    if ($_POST['jenis_form'] === 'murid_baru') {
        // --- PROSES FORM PENDAFTARAN MURID BARU ---
        
        // Ambil data (tambahkan validasi & sanitasi yang lebih kuat di produksi)
        $nama_lengkap = $_POST['nama_lengkap'] ?? null;
        $nama_panggilan = $_POST['nama_panggilan'] ?? null;
        $jenis_kelamin = $_POST['jenis_kelamin'] ?? null;
        $tempat_lahir = $_POST['tempat_lahir'] ?? null;
        $tanggal_lahir = !empty($_POST['tanggal_lahir']) ? $_POST['tanggal_lahir'] : null;
        $alamat_lengkap = $_POST['alamat_lengkap'] ?? null;
        $nomor_telepon = $_POST['nomor_telepon'] ?? null;
        $email = $_POST['email'] ?? null;
        $nama_wali = $_POST['nama_wali'] ?? null;
        $telepon_wali = $_POST['telepon_wali'] ?? null;
        $pilihan_kelas = $_POST['pilihan_kelas'] ?? null;

        // Validasi dasar
        if (empty($nama_lengkap) || empty($alamat_lengkap) || empty($nomor_telepon) || empty($pilihan_kelas)) {
            $_SESSION['pesan_registrasi'] = ['status' => 'error', 'pesan' => 'Harap isi semua field yang wajib diisi.'];
            header("Location: regist.php");
            exit();
        }

        // Simpan ke tabel PendaftarMurid
        $sql = "INSERT INTO PendaftarMurid (nama_lengkap, nama_panggilan, jenis_kelamin, tempat_lahir, tanggal_lahir, alamat_lengkap, nomor_telepon, email, nama_wali, telepon_wali, pilihan_kelas) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssssssss", 
            $nama_lengkap, $nama_panggilan, $jenis_kelamin, $tempat_lahir, $tanggal_lahir, 
            $alamat_lengkap, $nomor_telepon, $email, $nama_wali, $telepon_wali, $pilihan_kelas);

    } elseif ($_POST['jenis_form'] === 'pesan_jasa') {
        // --- PROSES FORM PEMESANAN JASA ---
        
        // Ambil data
        $nama_pemesan = $_POST['nama_pemesan'] ?? null;
        $nomor_telepon = $_POST['nomor_telepon'] ?? null;
        $email = $_POST['email'] ?? null;
        $jenis_jasa = $_POST['jenis_jasa'] ?? null;
        $tanggal_acara = !empty($_POST['tanggal_acara']) ? $_POST['tanggal_acara'] : null;
        $lokasi_acara = $_POST['lokasi_acara'] ?? null;
        $deskripsi_kebutuhan = $_POST['deskripsi_kebutuhan'] ?? null;

        // Validasi dasar
        if (empty($nama_pemesan) || empty($nomor_telepon) || empty($jenis_jasa) || empty($tanggal_acara) || empty($deskripsi_kebutuhan)) {
            $_SESSION['pesan_registrasi'] = ['status' => 'error', 'pesan' => 'Harap isi semua field yang wajib diisi.'];
            header("Location: regist.php#PesanJasa"); // Redirect kembali ke tab pemesanan jasa
            exit();
        }

        // Simpan ke tabel PemesananJasa
        $sql = "INSERT INTO PemesananJasa (nama_pemesan, nomor_telepon, email, jenis_jasa, tanggal_acara, lokasi_acara, deskripsi_kebutuhan) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, "sssssss", 
            $nama_pemesan, $nomor_telepon, $email, $jenis_jasa, 
            $tanggal_acara, $lokasi_acara, $deskripsi_kebutuhan);
            
    } else {
        // Jenis form tidak dikenal
        $_SESSION['pesan_registrasi'] = ['status' => 'error', 'pesan' => 'Terjadi kesalahan, formulir tidak dikenal.'];
        header("Location: regist.php");
        exit();
    }

    // Eksekusi query dan redirect
    if (mysqli_stmt_execute($stmt)) {
        $_SESSION['pesan_registrasi'] = ['status' => 'sukses', 'pesan' => 'Terima kasih! Data Anda telah berhasil dikirim. Kami akan segera menghubungi Anda.'];
    } else {
        $_SESSION['pesan_registrasi'] = ['status' => 'error', 'pesan' => 'Terjadi kesalahan saat mengirim data. Silakan coba lagi. Error: ' . mysqli_stmt_error($stmt)];
    }
    mysqli_stmt_close($stmt);

    if ($conn) close_connection($conn);

    if ($_POST['jenis_form'] === 'murid_baru') {
        header("Location: regist.php");
    } else {
        header("Location: regist.php#PesanJasa"); // Redirect kembali ke tab pemesanan jasa
    }
    exit();

} else {
    // Jika diakses langsung
    header("Location: regist.php");
    exit();
}
?>