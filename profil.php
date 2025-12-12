<?php
include 'includes/config.php';
$page_title = "Profil - Toko Baju Online";

// Jika belum login, redirect ke login
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Ambil data user
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();

// Ambil riwayat pesanan
$orders_query = "SELECT * FROM pesanan ORDER BY created_at DESC";
$orders_result = $conn->query($orders_query);

include 'includes/header.php';
?>

    <section class="profile-section">
        <div class="container">
            <h1>Profil Pengguna</h1>
            
            <div class="profile-wrapper">
                <!-- Data Profil -->
                <div class="profile-info">
                    <h2>Informasi Profil</h2>
                    <div class="info-card">
                        <p><strong>Nama Lengkap:</strong> <?php echo $user['nama_lengkap']; ?></p>
                        <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
                        <p><strong>Email:</strong> <?php echo $user['email']; ?></p>
                        <p><strong>No. HP:</strong> <?php echo $user['no_hp'] ?? '-'; ?></p>
                        <p><strong>Alamat:</strong> <?php echo $user['alamat'] ?? '-'; ?></p>
                        <a href="edit-profil.php" class="btn">Edit Profil</a>
                    </div>
                </div>

                <!-- Riwayat Pesanan -->
                <div class="order-history">
                    <h2>Riwayat Pesanan</h2>
                    
                    <?php if($orders_result->num_rows > 0): ?>
                        <table class="orders-table">
                            <thead>
                                <tr>
                                    <th>ID Pesanan</th>
                                    <th>Total</th>
                                    <th>Status</th>
                                    <th>Tanggal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($order = $orders_result->fetch_assoc()): ?>
                                    <tr>
                                        <td>#<?php echo $order['id']; ?></td>
                                        <td>Rp <?php echo number_format($order['total'], 0, ',', '.'); ?></td>
                                        <td>
                                            <span class="status-badge status-<?php echo $order['status']; ?>">
                                                <?php echo ucfirst($order['status']); ?>
                                            </span>
                                        </td>
                                        <td><?php echo date('d/m/Y', strtotime($order['created_at'])); ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php else: ?>
                        <p>Anda belum memiliki pesanan.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>