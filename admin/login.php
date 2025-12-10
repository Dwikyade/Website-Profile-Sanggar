<?php
// PROJECT-WEB-2025/admin/login.php
// Halaman ini HANYA untuk menampilkan form login.

// Include config untuk BASE_URL_ADMIN dan session_start()
// Path dari admin/login.php ke config/database.php adalah ../config/database.php
require_once '../config/database.php'; 

// Jika pengguna sudah login, arahkan ke dashboard admin
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header("Location: " . BASE_URL_ADMIN . "/index.php");
    exit;
}

$error_message = '';
if (isset($_SESSION['login_error'])) {
    $error_message = $_SESSION['login_error'];
    unset($_SESSION['login_error']); 
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Sanggar Sekar Kemuning</title>
    <link rel="stylesheet" href="css/admin_style.css"> <link rel="icon" href="<?php echo BASE_URL_PUBLIC; ?>/images/logo.png" />
</head>
<body>
    <div class="login-container">
        <div class="login-box">
            <img src="<?php echo BASE_URL_PUBLIC; ?>/images/logo.png" alt="Logo Sanggar" style="max-width: 100px; margin-bottom: 15px;">
            <h1>Admin Login</h1>
            <?php if ($error_message): ?>
                <p class="error-message"><?php echo htmlspecialchars($error_message); ?></p>
            <?php endif; ?>
            <form action="proses_login.php" method="POST">
                <div>
                    <label for="username">Username:</label>
                    <input type="text" id="username" name="username" required>
                </div>
                <div>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                <div>
                    <button type="submit">Login</button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>