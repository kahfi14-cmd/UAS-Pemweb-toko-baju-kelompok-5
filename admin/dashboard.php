<?php
include '../includes/config.php';
include "protect-admin.php";
$page_title = "Admin Dashboard - Toko Baju";

// Ambil semua produk
$query = "SELECT * FROM produk ORDER BY id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - Kelompok 5 Store</title>
    <link rel="stylesheet" href="stylesd.css">
</head>
<body>
    <!-- Mobile Toggle Button -->
    <button class="mobile-toggle" onclick="toggleSidebar()">☰</button>

    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="sidebar">
            <div class="sidebar-header">
                <h2>KELOMPOK 5</h2>
                <p>Admin Dashboard</p>
            </div>

            <nav class="sidebar-menu">
                <div class="menu-section">
                    <div class="menu-section-title">Main Menu</div>
                    <a href="dashboard.php" class="menu-item active">Dashboard</a>
                    <a href="tambah-produk.php" class="menu-item">Tambah Produk</a>
                    <a href="daftar-pesanan.php" class="menu-item">Daftar Pesanan</a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Lainnya</div>
                    <a href="../index.php" class="menu-item">Lihat Website</a>
                    <a href="pengaturan.php" class="menu-item">Pengaturan</a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar">A</div>
                    <div class="user-details">
                        <h4>Admin</h4>
                        <p>Administrator</p>
                    </div>
                </div>
                <a href="logout.php" class="logout-btn">Logout</a>
            </div>
        </aside>

        <!-- Main Content -->
        <main class="admin-content">
            <!-- Header -->
            <header class="admin-header">
                <div class="header-title">
                    <h1>Dashboard</h1>
                    <p>Selamat datang kembali, Admin!</p>
                </div>
                <div class="header-actions">
                    <a href="tambah-produk.php" class="btn-primary">+ Tambah Produk</a>
                </div>
            </header>

            <!-- Content -->
            <div class="content-wrapper">
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">📦</div>
                        <div class="stat-label">Total Produk</div>
                        <div class="stat-value">156</div>
                        <div class="stat-change">+12% dari bulan lalu</div>
                    </div>

                    <div class="stat-card green">
                        <div class="stat-icon">💰</div>
                        <div class="stat-label">Total Penjualan</div>
                        <div class="stat-value">Rp 45.2M</div>
                        <div class="stat-change">+23% dari bulan lalu</div>
                    </div>

                    <div class="stat-card orange">
                        <div class="stat-icon">🛒</div>
                        <div class="stat-label">Pesanan Baru</div>
                        <div class="stat-value">28</div>
                        <div class="stat-change">+5 hari ini</div>
                    </div>

                    <div class="stat-card red">
                        <div class="stat-icon">⚠️</div>
                        <div class="stat-label">Stok Menipis</div>
                        <div class="stat-value">12</div>
                        <div class="stat-change negative">Perlu restock</div>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="table-container">
                    <div class="table-header">
                        <h2>Daftar Produk</h2>
                        <div class="search-box">
                            <input type="text" placeholder="Cari produk...">
                        </div>
                    </div>

                    <table class="admin-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Stok</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>
                                    <div class="product-info">
                                        <?php if($row['gambar']): ?>
                                            <img src="../images/produk/<?php echo $row['gambar']; ?>" alt="<?php echo $row['nama']; ?>" class="product-image">
                                        <?php else: ?>
                                            <div class="product-image"></div>
                                        <?php endif; ?>
                                        <span class="product-name"><?php echo $row['nama']; ?></span>
                                    </div>
                                </td>
                                <td>
                                    <span class="category-badge <?php echo $row['kategori']; ?>">
                                        <?php echo ucfirst($row['kategori']); ?>
                                    </span>
                                </td>
                                <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                                <td><?php echo $row['stok']; ?></td>
                                <td>
                                    <?php 
                                    $stok = $row['stok'];
                                    if($stok > 10) {
                                        echo '<span class="stock-badge available">Tersedia</span>';
                                    } elseif($stok > 0) {
                                        echo '<span class="stock-badge low">Stok Menipis</span>';
                                    } else {
                                        echo '<span class="stock-badge empty">Habis</span>';
                                    }
                                    ?>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <a href="edit-produk.php?id=<?php echo $row['id']; ?>" class="action-btn btn-edit">
                                            ✏️ Edit
                                        </a>
                                        <form method="POST" action="proses.php" style="display:inline;">
                                            <input type="hidden" name="action" value="hapus">
                                            <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" class="action-btn btn-delete" onclick="return confirm('Yakin hapus produk ini?')">
                                                🗑️ Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>