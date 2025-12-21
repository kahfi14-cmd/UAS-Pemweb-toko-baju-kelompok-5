<?php
include '../includes/config.php';
include "protect-admin.php";
$page_title = "Daftar Pesanan - Admin";

// Ambil semua pesanan
$query = "SELECT * FROM pesanan ORDER BY created_at DESC";
$result = $conn->query($query);

// Update status pesanan jika ada request
if(isset($_POST['update_status'])) {
    $pesanan_id = $_POST['pesanan_id'];
    $status_baru = $conn->real_escape_string($_POST['status']);
    
    $update_query = "UPDATE pesanan SET status='$status_baru' WHERE id=$pesanan_id";
    if($conn->query($update_query)) {
        $_SESSION['pesan'] = "Status pesanan berhasil diupdate!";
        header("Location: daftar-pesanan.php");
        exit;
    }
}

// Hitung statistik
$stats_pending = $conn->query("SELECT COUNT(*) as count FROM pesanan WHERE status='pending'")->fetch_assoc()['count'];
$stats_paid = $conn->query("SELECT COUNT(*) as count FROM pesanan WHERE status='paid'")->fetch_assoc()['count'];
$stats_shipped = $conn->query("SELECT COUNT(*) as count FROM pesanan WHERE status='shipped'")->fetch_assoc()['count'];
$stats_completed = $conn->query("SELECT COUNT(*) as count FROM pesanan WHERE status='completed'")->fetch_assoc()['count'];
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
                    <a href="daftar-pesanan.php" class="menu-item active">Daftar Pesanan</a>
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
                    <h1>Daftar Pesanan</h1>
                    <p>Kelola semua pesanan pelanggan</p>
                </div>
                <div class="header-actions">
                    <button class="btn-primary" onclick="window.print()">🖨️ Cetak Laporan</button>
                </div>
            </header>

            <!-- Content -->
            <div class="content-wrapper">
                <?php if(isset($_SESSION['pesan'])): ?>
                    <div class="alert alert-success">
                        ✓ <?php echo $_SESSION['pesan']; unset($_SESSION['pesan']); ?>
                    </div>
                <?php endif; ?>

                <!-- Stats Cards -->
                <div class="order-stats">
                    <div class="order-stat-card pending">
                        <div class="stat-number"><?php echo $stats_pending; ?></div>
                        <div class="stat-label">Pending</div>
                    </div>
                    <div class="order-stat-card paid">
                        <div class="stat-number"><?php echo $stats_paid; ?></div>
                        <div class="stat-label">Dibayar</div>
                    </div>
                    <div class="order-stat-card shipped">
                        <div class="stat-number"><?php echo $stats_shipped; ?></div>
                        <div class="stat-label">Dikirim</div>
                    </div>
                    <div class="order-stat-card completed">
                        <div class="stat-number"><?php echo $stats_completed; ?></div>
                        <div class="stat-label">✓ Selesai</div>
                    </div>
                </div>

                <!-- Filter Tabs -->
                <div class="filter-tabs">
                    <button class="filter-tab active" onclick="filterOrders('all')">Semua Pesanan</button>
                    <button class="filter-tab" onclick="filterOrders('pending')">Pending</button>
                    <button class="filter-tab" onclick="filterOrders('paid')">Dibayar</button>
                    <button class="filter-tab" onclick="filterOrders('shipped')">Dikirim</button>
                    <button class="filter-tab" onclick="filterOrders('completed')">Selesai</button>
                </div>

                <!-- Orders Table -->
                <div class="table-container">
                    <div class="table-header">
                        <h2>Semua Pesanan</h2>
                        <div class="search-box">
                            <input type="text" placeholder="Cari pesanan..." onkeyup="searchOrders(this.value)">
                        </div>
                    </div>

                    <?php if($result->num_rows > 0): ?>
                    <table class="admin-table" id="ordersTable">
                        <thead>
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Pelanggan</th>
                                <th>Kontak</th>
                                <th>Alamat Kirim</th>
                                <th>Total</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while($row = $result->fetch_assoc()): ?>
                            <tr data-status="<?php echo $row['status']; ?>">
                                <td>
                                    <strong>#<?php echo str_pad($row['id'], 5, '0', STR_PAD_LEFT); ?></strong>
                                </td>
                                <td>
                                    <div class="customer-name">
                                        <?php echo htmlspecialchars($row['nama_pembeli'] ?? 'Guest'); ?>
                                    </div>
                                </td>
                                <td>
                                    <div class="customer-contact">
                                        <div><?php echo htmlspecialchars($row['email'] ?? '-'); ?></div>
                                        <small><?php echo htmlspecialchars($row['alamat'] ?? '-'); ?></small>
                                    </div>
                                </td>
                                <td>
                                    <small><?php echo htmlspecialchars(substr($row['alamat'] ?? '-', 0, 50)); ?><?php echo strlen($row['alamat']) > 50 ? '...' : ''; ?></small>
                                </td>
                                <td>
                                    <strong>Rp <?php echo number_format($row['total'], 0, ',', '.'); ?></strong>
                                </td>
                                <td>
                                    <?php echo date('d M Y', strtotime($row['created_at'])); ?><br>
                                    <small><?php echo date('H:i', strtotime($row['created_at'])); ?></small>
                                </td>
                                <td>
                                    <?php
                                    $status_class = '';
                                    $status_text = '';
                                    switch($row['status']) {
                                        case 'pending':
                                            $status_class = 'status-pending';
                                            $status_text = 'Pending';
                                            break;
                                        case 'paid':
                                            $status_class = 'status-paid';
                                            $status_text = 'Dibayar';
                                            break;
                                        case 'shipped':
                                            $status_class = 'status-shipped';
                                            $status_text = 'Dikirim';
                                            break;
                                        case 'completed':
                                            $status_class = 'status-completed';
                                            $status_text = 'Selesai';
                                            break;
                                        default:
                                            $status_class = 'status-pending';
                                            $status_text = $row['status'];
                                    }
                                    ?>
                                    <span class="order-status-badge <?php echo $status_class; ?>">
                                        <?php echo $status_text; ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="action-btn btn-view" onclick="viewOrder(<?php echo $row['id']; ?>)">
                                            Detail
                                        </button>
                                        <button class="action-btn btn-edit" onclick="updateStatus(<?php echo $row['id']; ?>, '<?php echo $row['status']; ?>')">
                                            Update
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">Z</div>
                        <h3>Belum Ada Pesanan</h3>
                        <p>Pesanan pelanggan akan muncul di sini</p>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </main>
    </div>

    <!-- Modal Update Status -->
    <div id="statusModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2>Update Status Pesanan</h2>
                <span class="modal-close" onclick="closeModal()">&times;</span>
            </div>
            <form method="POST" id="statusForm">
                <input type="hidden" name="update_status" value="1">
                <input type="hidden" name="pesanan_id" id="modalPesananId">
                
                <div class="form-group">
                    <label>Status Baru:</label>
                    <select name="status" id="modalStatus" required>
                        <option value="pending">Pending</option>
                        <option value="paid">Dibayar</option>
                        <option value="shipped">Dikirim</option>
                        <option value="completed">Selesai</option>
                    </select>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-submit">Update Status</button>
                    <button type="button" class="btn-cancel" onclick="closeModal()">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            sidebar.classList.toggle('active');
        }

        function filterOrders(status) {
            const rows = document.querySelectorAll('#ordersTable tbody tr');
            const tabs = document.querySelectorAll('.filter-tab');
            
            tabs.forEach(tab => tab.classList.remove('active'));
            event.target.classList.add('active');
            
            rows.forEach(row => {
                if(status === 'all' || row.dataset.status === status) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function searchOrders(query) {
            query = query.toLowerCase();
            const rows = document.querySelectorAll('#ordersTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                if(text.includes(query)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        }

        function updateStatus(id, currentStatus) {
            document.getElementById('modalPesananId').value = id;
            document.getElementById('modalStatus').value = currentStatus;
            document.getElementById('statusModal').style.display = 'flex';
        }

        function closeModal() {
            document.getElementById('statusModal').style.display = 'none';
        }

        function viewOrder(id) {
            // Show order details
            alert('Detail Pesanan #' + id + '\n\nFitur ini akan menampilkan:\n- Daftar produk yang dibeli\n- Detail pembayaran\n- Alamat pengiriman lengkap\n- Riwayat status');
        }

        window.onclick = function(event) {
            const modal = document.getElementById('statusModal');
            if (event.target == modal) {
                closeModal();
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
        .order-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .order-stat-card {
            background: white;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
            text-align: center;
            border-left: 4px solid;
            transition: all 0.3s ease;
        }

        .order-stat-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
        }

        .order-stat-card.pending {
            border-color: #f59e0b;
        }

        .order-stat-card.paid {
            border-color: #10b981;
        }

        .order-stat-card.shipped {
            border-color: #3b82f6;
        }

        .order-stat-card.completed {
            border-color: #8b5cf6;
        }

        .stat-number {
            font-size: 36px;
            font-weight: 700;
            color: #1a1a2e;
            margin-bottom: 8px;
        }

        .stat-label {
            font-size: 14px;
            color: #718096;
            font-weight: 600;
        }

        .filter-tabs {
            display: flex;
            gap: 10px;
            margin-bottom: 30px;
            flex-wrap: wrap;
        }

        .filter-tab {
            padding: 12px 24px;
            background: white;
            border: 2px solid #e2e8f0;
            border-radius: 8px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 600;
            color: #4a5568;
            transition: all 0.3s ease;
        }

        .filter-tab:hover {
            border-color: #667eea;
            color: #667eea;
        }

        .filter-tab.active {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border-color: transparent;
        }

        .customer-contact {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .customer-contact small {
            color: #718096;
            font-size: 12px;
        }

        .customer-name {
            font-weight: 600;
            color: #1a1a2e;
        }

        .order-status-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-paid {
            background: #d4edda;
            color: #155724;
        }

        .status-shipped {
            background: #cfe2ff;
            color: #084298;
        }

        .status-completed {
            background: #d1e7dd;
            color: #0f5132;
        }

        .btn-view {
            background: #e6f2ff;
            color: #0066cc;
        }

        .btn-view:hover {
            background: #0066cc;
            color: white;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.6);
            backdrop-filter: blur(5px);
            align-items: center;
            justify-content: center;
        }

        .modal-content {
            background: white;
            border-radius: 12px;
            width: 90%;
            max-width: 500px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
            animation: modalSlideIn 0.3s ease;
        }

        @keyframes modalSlideIn {
            from {
                opacity: 0;
                transform: translateY(-50px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .modal-header {
            padding: 25px 30px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .modal-header h2 {
            font-size: 22px;
            color: #1a1a2e;
        }

        .modal-close {
            font-size: 32px;
            color: #718096;
            cursor: pointer;
            transition: color 0.3s ease;
        }

        .modal-close:hover {
            color: #1a1a2e;
        }

        .modal-content form {
            padding: 30px;
        }

        @media (max-width: 768px) {
            .admin-table {
                font-size: 12px;
            }
            
            .admin-table td {
                padding: 10px 8px;
            }
        }
    </style>
</body>
</html>