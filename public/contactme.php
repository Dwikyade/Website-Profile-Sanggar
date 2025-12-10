<?php
// PROJECT-WEB-2025/contactme.php

// Panggil file koneksi database dan mailfunction Anda
require_once '../config/database.php';       // Sesuaikan path jika perlu
require_once 'mailing/mailfunction.php';  // Sesuaikan path jika perlu

// Ambil data dari form dan lakukan validasi dasar
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $name = trim($_POST["name"]);
    $phone = trim($_POST['phone']);
    $email = trim($_POST["email"]);
    $message = trim($_POST["message"]);

    // Validasi dasar
    if (empty($name) || empty($phone) || empty($email) || empty($message) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        // Jika validasi gagal, simpan pesan error dan kembali
        $_SESSION['pesan_kontak'] = ['status' => 'error', 'pesan' => 'Harap isi semua field dengan data yang valid.'];
        header('Location: index.php#contact'); // Kembali ke bagian kontak di beranda
        exit();
    }

    // 1. Simpan pesan ke database menggunakan Prepared Statements
    $sql = "INSERT INTO PesanKontak (nama_pengirim, telepon_pengirim, email_pengirim, isi_pesan) VALUES (?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);

    if ($stmt) {
        mysqli_stmt_bind_param($stmt, "ssss", $name, $phone, $email, $message);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
    } else {
        // Jika gagal menyimpan ke DB, catat error tapi mungkin tetap coba kirim email
        error_log("Gagal menyimpan pesan kontak ke DB: " . mysqli_error($conn));
    }

    // 2. Tetap kirim email (seperti kode Anda sebelumnya)
    // GANTI "email_tujuan_anda@domain.com" dengan alamat email Anda yang sebenarnya
    $email_penerima = "dwiky.fat@gmail.com"; 
    $subjek_email = "Pesan Baru dari Website Sanggar Sekar Kemuning";
    $body = "<ul><li>Nama: ".$name."</li><li>Telepon: ".$phone."</li><li>Email: ".$email."</li><li>Pesan: ".$message."</li></ul>";

    // Panggil fungsi email Anda
    $status_email = mailfunction($email_penerima, $subjek_email, $body);

    // 3. Siapkan pesan notifikasi dan redirect
    if ($status_email) {
        $_SESSION['pesan_kontak'] = ['status' => 'sukses', 'pesan' => 'Terima kasih! Pesan Anda telah terkirim dan kami akan segera menghubungi Anda.'];
    } else {
        $_SESSION['pesan_kontak'] = ['status' => 'error', 'pesan' => 'Pesan Anda berhasil kami rekam, namun gagal dikirim via email. Kami akan tetap menindaklanjutinya.'];
    }

    if ($conn) close_connection($conn);
    header('Location: index.php#contact'); // Kembali ke bagian kontak di beranda
    exit();

} else {
    // Jika diakses langsung
    header('Location: index.php');
    exit();
}
?>