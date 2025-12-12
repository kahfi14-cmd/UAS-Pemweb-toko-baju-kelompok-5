<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($page_title) ? $page_title : "Toko Baju Online"; ?></title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <nav class="navbar">
        <div class="container">
            <div class="logo">
                <a href="index.php">KELOMPOK 5 STORE</a>
            </div>
            <ul class="nav-menu">
                <li><a href="index.php">Beranda</a></li>
                <li><a href="produk.php">Produk</a></li>
                <li><a href="about.php">Tentang</a></li>
                <li><a href="keranjang.php">Keranjang (<?php echo isset($_SESSION['keranjang']) ? count($_SESSION['keranjang']) : 0; ?>)</a></li>
                
                <?php if(isset($_SESSION['admin'])) { ?>
                    <!-- TOMBOL HANYA MUNCUL UNTUK ADMIN -->
                    <li><a href="admin/dashboard.php">Dashboard Admin</a></li>
                    <li><a href="admin/logout.php">Logout</a></li>
                <?php } ?>

                <?php if(isset($_SESSION['user_id'])): ?>
                    <!-- User sudah login -->
                    <li class="user-dropdown">
                        <a href="#">👤 <?php echo $_SESSION['username']; ?></a>
                        <ul class="dropdown-menu">
                            <li><a href="profil.php">Profil</a></li>
                            <?php if($_SESSION['role'] == 'admin'): ?>
                                <li><a href="admin/dashboard.php">Admin Panel</a></li>
                            <?php endif; ?>
                            <li><a href="logout.php">Logout</a></li>
                        </ul>
                    </li>
                <?php else: ?>
                    <!-- User belum login -->
                    <li><a href="login.php">Login</a></li>
                    <li><a href="register.php">Daftar</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </nav>
    <?php 
// ... existing code ...
    ?>

    <script src="js/script.js"></script>
</body>
</html>
