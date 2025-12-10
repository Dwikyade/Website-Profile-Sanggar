<?php
// PROJECT-WEB-2025/admin/templates_admin/footer.php
if (!defined('BASE_URL_ADMIN')) { define('BASE_URL_ADMIN', '/PROJECT-WEB-2025/admin'); } // Fallback
?>
            </main> </div> </div> <footer class="admin-main-footer">
        <p>&copy; <?php echo date("Y"); ?> Sanggar Sekar Kemuning - Admin Panel</p>
    </footer>

    <script>
        function toggleSidebar() {
            var sidebar = document.getElementById('adminSidebar');
            var mainContentWrapper = document.querySelector('.admin-main-content');
            sidebar.classList.toggle('collapsed'); // 'collapsed' akan menyembunyikan sidebar (atur di CSS)
            // Anda mungkin juga ingin konten utama menyesuaikan margin/lebarnya
            // mainContentWrapper.classList.toggle('expanded'); 
        }
    </script>
    </body>
</html>