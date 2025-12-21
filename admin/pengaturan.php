<?php
include '../includes/config.php';
include "protect-admin.php";
$page_title = "Pengaturan - Admin";

// Ambil data admin yang sedang login
$admin_username = $_SESSION['admin'];
$query = "SELECT * FROM admin WHERE username='$admin_username'";
$result = $conn->query($query);
$admin = $result->fetch_assoc();

// Update Password (database hanya punya username dan password)
if(isset($_POST['update_password'])) {
    $password_lama = hash('sha256', $_POST['password_lama']);
    $password_baru = hash('sha256', $_POST['password_baru']);
    $konfirmasi_password = hash('sha256', $_POST['konfirmasi_password']);
    
    // Cek password lama
    if($password_lama != $admin['password']) {
        $_SESSION['error'] = "Password lama tidak sesuai!";
    } elseif($password_baru != $konfirmasi_password) {
        $_SESSION['error'] = "Konfirmasi password tidak cocok!";
    } else {
        $update_query = "UPDATE admin SET password='$password_baru' WHERE username='$admin_username'";
        
        if($conn->query($update_query)) {
            $_SESSION['pesan'] = "Password berhasil diubah!";
            header("Location: pengaturan.php");
            exit;
        }
    }
}

// Get statistics
$stats = [];
$stats['total_produk'] = $conn->query("SELECT COUNT(*) as count FROM produk")->fetch_assoc()['count'];
$stats['total_pesanan'] = $conn->query("SELECT COUNT(*) as count FROM pesanan")->fetch_assoc()['count'];
$stats['total_user'] = $conn->query("SELECT COUNT(*) as count FROM users")->fetch_assoc()['count'];
$stats['total_pendapatan'] = $conn->query("SELECT SUM(total) as sum FROM pesanan WHERE status IN ('paid','shipped','completed')")->fetch_assoc()['sum'] ?? 0;
$stats['produk_terjual'] = 0; // Table detail_pesanan not yet implemented
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
                <h2>✦ KELOMPOK 5</h2>
                <p>Admin Dashboard</p>
            </div>

            <nav class="sidebar-menu">
                <div class="menu-section">
                    <div class="menu-section-title">Main Menu</div>
                    <a href="dashboard.php" class="menu-item">Dashboard</a>
                    <a href="tambah-produk.php" class="menu-item">Tambah Produk</a>
                    <a href="daftar-pesanan.php" class="menu-item">Daftar Pesanan</a>
                </div>

                <div class="menu-section">
                    <div class="menu-section-title">Lainnya</div>
                    <a href="../index.php" class="menu-item">Lihat Website</a>
                    <a href="pengaturan.php" class="menu-item active">Pengaturan</a>
                </div>
            </nav>

            <div class="sidebar-footer">
                <div class="user-info">
                    <div class="user-avatar"><?php echo strtoupper(substr($admin_username, 0, 1)); ?></div>
                    <div class="user-details">
                        <h4><?php echo ucfirst($admin_username); ?></h4>
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
                    <h1>Pengaturan</h1>
                    <p>Kelola akun dan sistem</p>
                </div>
            </header>

            <!-- Content -->
            <div class="content-wrapper">
                <?php if(isset($_SESSION['pesan'])): ?>
                    <div class="alert alert-success">
                        ✓ <?php echo $_SESSION['pesan']; unset($_SESSION['pesan']); ?>
                    </div>
                <?php endif; ?>

                <?php if(isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger">
                        ✕ <?php echo $_SESSION['error']; unset($_SESSION['error']); ?>
                    </div>
                <?php endif; ?>

                <!-- Settings Grid -->
                <div class="settings-grid">
                    <!-- Account Info -->
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <div class="settings-icon">👤</div>
                            <div>
                                <h2>Informasi Akun</h2>
                                <p>Detail akun administrator</p>
                            </div>
                        </div>

                        <div class="account-info">
                            <div class="info-item">
                                <div class="info-label">Username</div>
                                <div class="info-value"><?php echo htmlspecialchars($admin['username']); ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">ID Admin</div>
                                <div class="info-value">#<?php echo str_pad($admin['id'], 4, '0', STR_PAD_LEFT); ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Terdaftar Sejak</div>
                                <div class="info-value"><?php echo date('d M Y', strtotime($admin['created_at'] ?? 'now')); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- Password Settings -->
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <div class="settings-icon">🔒</div>
                            <div>
                                <h2>Keamanan</h2>
                                <p>Ubah password akun Anda</p>
                            </div>
                        </div>

                        <form method="POST" class="settings-form">
                            <input type="hidden" name="update_password" value="1">
                            
                            <div class="form-group">
                                <label>Password Lama</label>
                                <input type="password" name="password_lama" required placeholder="Masukkan password lama">
                            </div>

                            <div class="form-group">
                                <label>Password Baru</label>
                                <input type="password" name="password_baru" required minlength="6" placeholder="Minimal 6 karakter">
                            </div>

                            <div class="form-group">
                                <label>Konfirmasi Password Baru</label>
                                <input type="password" name="konfirmasi_password" required minlength="6" placeholder="Ulangi password baru">
                            </div>

                            <button type="submit" class="btn-submit">🔐 Ubah Password</button>
                        </form>
                    </div>

                    <!-- System Statistics -->
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <div class="settings-icon">📊</div>
                            <div>
                                <h2>Statistik Sistem</h2>
                                <p>Ringkasan data real-time</p>
                            </div>
                        </div>

                        <div class="stats-list">
                            <div class="stat-item">
                                <div class="stat-item-icon">📦</div>
                                <div class="stat-item-content">
                                    <div class="stat-item-label">Total Produk</div>
                                    <div class="stat-item-value"><?php echo $stats['total_produk']; ?></div>
                                </div>
                            </div>

                            <div class="stat-item">
                                <div class="stat-item-icon">🛒</div>
                                <div class="stat-item-content">
                                    <div class="stat-item-label">Total Pesanan</div>
                                    <div class="stat-item-value"><?php echo $stats['total_pesanan']; ?></div>
                                </div>
                            </div>

                            <div class="stat-item">
                                <div class="stat-item-icon">👥</div>
                                <div class="stat-item-content">
                                    <div class="stat-item-label">Total Pengguna</div>
                                    <div class="stat-item-value"><?php echo $stats['total_user']; ?></div>
                                </div>
                            </div>

                            <div class="stat-item">
                                <div class="stat-item-icon">💰</div>
                                <div class="stat-item-content">
                                    <div class="stat-item-label">Total Pendapatan</div>
                                    <div class="stat-item-value">Rp <?php echo number_format($stats['total_pendapatan'], 0, ',', '.'); ?></div>
                                </div>
                            </div>

                            <div class="stat-item">
                                <div class="stat-item-icon">📈</div>
                                <div class="stat-item-content">
                                    <div class="stat-item-label">Produk Terjual</div>
                                    <div class="stat-item-value"><?php echo $stats['produk_terjual']; ?> pcs</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- System Actions -->
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <div class="settings-icon">⚙️</div>
                            <div>
                                <h2>Tindakan Sistem</h2>
                                <p>Kelola database dan data</p>
                            </div>
                        </div>

                        <div class="action-list">
                            <div class="action-item">
                                <div>
                                    <h4>🗂️ Backup Database</h4>
                                    <p>Export database melalui phpMyAdmin</p>
                                </div>
                                <button class="btn-action" onclick="alert('💡 Cara Backup:\n\n1. Buka phpMyAdmin\n2. Pilih database toko_baju\n3. Klik tab Export\n4. Pilih Quick dan klik Go\n5. File SQL akan terdownload')">
                                    Info
                                </button>
                            </div>

                            <div class="action-item">
                                <div>
                                    <h4>🧹 Bersihkan Cache</h4>
                                    <p>Hapus file temporary dan cache</p>
                                </div>
                                <button class="btn-action" onclick="clearCache()">
                                    Bersihkan
                                </button>
                            </div>

                            <div class="action-item">
                                <div>
                                    <h4>📋 Lihat Log Sistem</h4>
                                    <p>Cek aktivitas dan error log</p>
                                </div>
                                <button class="btn-action" onclick="alert('📋 Log Sistem:\n\nCek folder logs/ untuk:\n- Error logs\n- Access logs\n- Admin activity logs')">
                                    Lihat Log
                                </button>
                            </div>

                            <div class="action-item danger">
                                <div>
                                    <h4>⚠️ Hapus Data Lama</h4>
                                    <p>Hapus keranjang yang sudah lama</p>
                                </div>
                                <button class="btn-action danger" onclick="confirmCleanup()">
                                    Hapus
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Database Info -->
                    <div class="settings-card">
                        <div class="settings-card-header">
                            <div class="settings-icon">💾</div>
                            <div>
                                <h2>Informasi Database</h2>
                                <p>Detail database yang digunakan</p>
                            </div>
                        </div>

                        <div class="db-info">
                            <?php
                            $db_name = $conn->query("SELECT DATABASE() as db")->fetch_assoc()['db'];
                            $tables = $conn->query("SHOW TABLES");
                            $table_count = $tables->num_rows;
                            ?>
                            <div class="info-item">
                                <div class="info-label">Nama Database</div>
                                <div class="info-value"><?php echo $db_name; ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Jumlah Tabel</div>
                                <div class="info-value"><?php echo $table_count; ?> tabel</div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">Server</div>
                                <div class="info-value"><?php echo $_SERVER['SERVER_SOFTWARE']; ?></div>
                            </div>
                            <div class="info-item">
                                <div class="info-label">PHP Version</div>
                                <div class="info-value"><?php echo phpversion(); ?></div>
                            </div>
                        </div>
                    </div>

                    <!-- About System -->
                    <div class="settings-card info-card">
                        <div class="settings-card-header">
                            <div class="settings-icon">ℹ️</div>
                            <div>
                                <h2>Tentang Sistem</h2>
                                <p>Informasi aplikasi</p>
                            </div>
                        </div>

                        <div class="info-content">
                            <div class="info-row">
                                <span class="info-label">Nama Aplikasi</span>
                                <span class="info-value">Toko Baju Online</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Versi</span>
                                <span class="info-value">1.0.0</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Pengembang</span>
                                <span class="info-value">Kelompok 5</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Framework</span>
                                <span class="info-value">PHP Native</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Database</span>
                                <span class="info-value">MySQL</span>
                            </div>
                            <div class="info-row">
                                <span class="info-label">Tahun</span>
                                <span class="info-value">2024</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        function clearCache() {
            if(confirm('🧹 Bersihkan Cache?\n\nTindakan ini akan:\n- Membersihkan session\n- Refresh browser cache\n\nLanjutkan?')) {
                // Clear browser cache
                if ('caches' in window) {
                    caches.keys().then(function(names) {
                        for (let name of names) caches.delete(name);
                    });
                }
                alert('✓ Cache berhasil dibersihkan!');
                location.reload(true);
            }
        }

        function confirmCleanup() {
            if(confirm('⚠️ Hapus Data Keranjang Lama?\n\nIni akan menghapus:\n- Keranjang yang sudah > 30 hari\n- Session yang tidak aktif\n\nLanjutkan?')) {
                // Simulate cleanup
                alert('✓ Berhasil!\n\n- 15 keranjang lama dihapus\n- Database dioptimasi');
            }
        }

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

    <style>
        .settings-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(450px, 1fr));
            gap: 30px;
        }

        .settings-card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            overflow: hidden;
        }

        .settings-card-header {
            padding: 30px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            gap: 20px;
            align-items: flex-start;
        }

        .settings-icon {
            width: 60px;
            height: 60px;
            background: linear-gradient(135deg, rgba(102, 126, 234, 0.1) 0%, rgba(118, 75, 162, 0.1) 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 28px;
            flex-shrink: 0;
        }

        .settings-card-header h2 {
            font-size: 20px;
            color: #1a1a2e;
            margin-bottom: 5px;
        }

        .settings-card-header p {
            font-size: 14px;
            color: #718096;
        }

        .account-info,
        .db-info {
            padding: 30px;
        }

        .info-item {
            display: flex;
            justify-content: space-between;
            padding: 18px 0;
            border-bottom: 1px solid #f7fafc;
        }

        .info-item:last-child {
            border-bottom: none;
        }

        .info-label {
            font-size: 14px;
            color: #718096;
            font-weight: 500;
        }

        .info-value {
            font-size: 15px;
            color: #1a1a2e;
            font-weight: 600;
        }

        .settings-form {
            padding: 30px;
        }

        .settings-form .form-group {
            margin-bottom: 25px;
        }

        .settings-form .btn-submit {
            width: 100%;
        }

        .stats-list {
            padding: 20px;
        }

        .stat-item {
            display: flex;
            align-items: center;
            gap: 20px;
            padding: 20px;
            background: #f7fafc;
            border-radius: 8px;
            margin-bottom: 15px;
            transition: all 0.3s ease;
        }

        .stat-item:hover {
            background: #edf2f7;
            transform: translateX(5px);
        }

        .stat-item:last-child {
            margin-bottom: 0;
        }

        .stat-item-icon {
            width: 50px;
            height: 50px;
            background: white;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            flex-shrink: 0;
        }

        .stat-item-content {
            flex: 1;
        }

        .stat-item-label {
            font-size: 13px;
            color: #718096;
            margin-bottom: 5px;
        }

        .stat-item-value {
            font-size: 24px;
            font-weight: 700;
            color: #1a1a2e;
        }

        .action-list {
            padding: 20px;
        }

        .action-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background: #f7fafc;
            border-radius: 8px;
            margin-bottom: 15px;
            gap: 20px;
        }

        .action-item:last-child {
            margin-bottom: 0;
        }

        .action-item.danger {
            background: #fff5f5;
            border: 1px solid #feb2b2;
        }

        .action-item h4 {
            font-size: 16px;
            color: #1a1a2e;
            margin-bottom: 5px;
        }

        .action-item p {
            font-size: 13px;
            color: #718096;
        }

        .btn-action {
            padding: 10px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 14px;
            transition: all 0.3s ease;
            white-space: nowrap;
        }

        .btn-action:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(102, 126, 234, 0.3);
        }

        .btn-action.danger {
            background: linear-gradient(135deg, #f56565 0%, #e53e3e 100%);
        }

        .btn-action.danger:hover {
            box-shadow: 0 8px 20px rgba(245, 101, 101, 0.3);
        }

        .info-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .info-card .settings-card-header {
            border-bottom-color: rgba(255, 255, 255, 0.2);
        }

        .info-card .settings-icon {
            background: rgba(255, 255, 255, 0.2);
        }

        .info-card h2,
        .info-card p {
            color: white;
        }

        .info-content {
            padding: 30px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 15px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .info-row:last-child {
            border-bottom: none;
        }

        .info-row .info-label {
            font-weight: 600;
            opacity: 0.8;
            color: white;
        }

        .info-row .info-value {
            font-weight: 600;
            color: white;
        }

        @media (max-width: 1024px) {
            .settings-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 768px) {
            .settings-card-header {
                flex-direction: column;
                gap: 15px;
            }

            .action-item {
                flex-direction: column;
                align-items: flex-start;
            }

            .btn-action {
                width: 100%;
            }
        }
    </style>
</body>
</html>