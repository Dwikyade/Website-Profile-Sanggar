// Sudah ditambahkan di contoh footer.php di atas
function toggleSidebar() {
  var sidebar = document.getElementById("adminSidebar");
  var mainContent = document.querySelector(".admin-main-content"); // Mungkin perlu selector yang lebih spesifik jika ada banyak
  sidebar.classList.toggle("collapsed");
  // Jika Anda ingin konten utama bergeser saat sidebar muncul (bukan overlay):
  // mainContent.classList.toggle('expanded');
}
// admin/js/admin_scripts.js

// Pastikan semua kode dijalankan setelah dokumen siap (ready)
// Pastikan dokumen siap (ready)
$(document).ready(function() {
    
    // Membuat notifikasi sukses hilang otomatis setelah 5 detik
    if ($('.notif-sukses').length) {
        setTimeout(function() {
            // Animasi fadeOut yang lembut
            $('.notif-sukses').fadeOut('slow', function() {
                // Hapus elemen dari DOM setelah animasi selesai
                $(this).remove();
            });
        }, 5000); // 5000 milidetik = 5 detik
    }

    // Fungsi untuk tombol close manual (jika sudah ada, pastikan targetnya benar)
    $('body').on('click', '.notif-sukses .close-btn', function() {
        $(this).closest('.notif-sukses').fadeOut('fast', function() {
            $(this).remove();
        });
    });

    // ... (kode JS Anda yang lain, seperti toggle sidebar) ...

});

// ... (kode yang sudah ada) ...

// Fungsi untuk menampilkan nama file yang diupload di form
function displayFileName(input, displayElementId) {
    const displayElement = document.getElementById(displayElementId);
    if (input.files && input.files[0]) {
        displayElement.textContent = input.files[0].name;
    } else {
        displayElement.textContent = 'Tidak ada file dipilih';
    }
}

// admin/js/admin_scripts.js

// Fungsi untuk menampilkan nama file yang diupload
function displayFileName(input, displayElementId) {
    const displayElement = document.getElementById(displayElementId);
    if (input.files && input.files[0]) {
        displayElement.textContent = input.files[0].name;
    } else {
        displayElement.textContent = 'Tidak ada file dipilih';
    }
}

function toggleMediaType(type) {
    const fieldVideo = document.getElementById('field-video');
    const fieldGambarFull = document.getElementById('field-gambar-full');
    const inputGambarFull = document.getElementById('path_gambar_full');
    const inputUrlVideo = document.getElementById('url_video');

    if (type === 'Video') {
        fieldVideo.style.display = 'block';
        fieldGambarFull.style.display = 'none';
        inputGambarFull.required = false; // Gambar full tidak wajib untuk video
        inputUrlVideo.required = true;   // URL video wajib untuk video
    } else { // Jika 'Gambar'
        fieldVideo.style.display = 'none';
        fieldGambarFull.style.display = 'block';
        inputGambarFull.required = true;  // Gambar full wajib untuk gambar
        inputUrlVideo.required = false;
    }
}
// Panggil sekali di awal untuk memastikan state form benar saat halaman dimuat
document.addEventListener('DOMContentLoaded', function() {
    toggleMediaType(document.getElementById('tipe_media').value);
});
// ... (kode lain yang sudah ada) ...