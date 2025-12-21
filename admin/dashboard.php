<?php
include '../includes/config.php';
include "protect-admin.php";
$page_title = "Admin Dashboard - Toko Baju";

// Ambil statistik real dari database
$total_produk = $conn->query("SELECT COUNT(*) as count FROM produk")->fetch_assoc()['count'];
$total_penjualan = $conn->query("SELECT SUM(total) as sum FROM pesanan WHERE status IN ('paid','shipped','completed')")->fetch_assoc()['sum'] ?? 0;
$pesanan_baru = $conn->query("SELECT COUNT(*) as count FROM pesanan WHERE status='pending'")->fetch_assoc()['count'];
$stok_menipis = $conn->query("SELECT COUNT(*) as count FROM produk WHERE stok <= 5 AND stok > 0")->fetch_assoc()['count'];

// Ambil semua produk untuk ditampilkan di tabel
$query = "SELECT * FROM produk ORDER BY id DESC";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
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
                        <div class="stat-icon">P</div>
                        <div class="stat-label">Total Produk</div>
                        <div class="stat-value"><?php echo $total_produk; ?></div>
                        <div class="stat-change">Produk dalam sistem</div>
                    </div>

                    <div class="stat-card green">
                        <div class="stat-icon">X</div>
                        <div class="stat-label">Total Penjualan</div>
                        <div class="stat-value">Rp <?php echo number_format($total_penjualan / 1000000, 1); ?>M</div>
                        <div class="stat-change">Total pendapatan</div>
                    </div>

                    <div class="stat-card orange">
                        <div class="stat-icon">Z</div>
                        <div class="stat-label">Pesanan Pending</div>
                        <div class="stat-value"><?php echo $pesanan_baru; ?></div>
                        <div class="stat-change">Menunggu konfirmasi</div>
                    </div>

                    <div class="stat-card red">
                        <div class="stat-icon">T</div>
                        <div class="stat-label">Stok Menipis</div>
                        <div class="stat-value"><?php echo $stok_menipis; ?></div>
                        <div class="stat-change negative">Perlu restock</div>
                    </div>
                </div>

                <!-- Products Table -->
                <div class="table-container">
                    <div class="table-header">
                        <h2>Daftar Produk</h2>
                        <div class="search-box">
                            <input type="text" placeholder="Cari produk..." onkeyup="searchProducts(this.value)">
                        </div>
                    </div>

                    <?php if($result->num_rows > 0): ?>
                    <table class="admin-table" id="productsTable">
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
                                            <img src="../images/produk/<?php echo $row['gambar']; ?>" alt="<?php echo htmlspecialchars($row['nama']); ?>" class="product-image">
                                        <?php else: ?>
                                            <div class="product-image" style="background: #e2e8f0;"></div>
                                        <?php endif; ?>
                                        <span class="product-name"><?php echo htmlspecialchars($row['nama']); ?></span>
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
                    <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">📦</div>
                        <h3>Belum Ada Produk</h3>
                        <p>Mulai tambahkan produk baru ke toko Anda</p>
                        <a href="tambah-produk.php" class="btn-primary" style="margin-top: 20px;">+ Tambah Produk</a>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <script>
        // Toggle Sidebar untuk Mobile
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        // Search Products
        function searchProducts(query) {
            query = query.toLowerCase();
            const rows = document.querySelectorAll('#productsTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if(text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('sidebar');
            const toggle = document.querySelector('.mobile-toggle');
            
            if (window.innerWidth <= 1024) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('active');
                }
            }
        });
    </script>
</body>
</html>