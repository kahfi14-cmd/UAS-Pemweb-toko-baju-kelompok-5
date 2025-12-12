<?php
include 'includes/config.php';
$page_title = "Checkout - Toko Baju Online";
include 'includes/header.php';

// Ambil total dari keranjang
$total = 0;
if(!empty($_SESSION['keranjang'])) {
    foreach($_SESSION['keranjang'] as $produk_id => $jumlah) {
        $query = "SELECT harga FROM produk WHERE id = $produk_id";
        $result = $conn->query($query);
        $produk = $result->fetch_assoc();
        $total += $produk['harga'] * $jumlah;
    }
}

// Handle submit form
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pembeli = $conn->real_escape_string($_POST['nama_pembeli']);
    $email = $conn->real_escape_string($_POST['email']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    $no_hp = $conn->real_escape_string($_POST['no_hp']);
    
    // Insert ke tabel pesanan
    $query = "INSERT INTO pesanan (nama_pembeli, email, alamat, total, status) 
              VALUES ('$nama_pembeli', '$email', '$alamat', $total, 'pending')";
    
    if($conn->query($query)) {
        // Hapus keranjang setelah checkout
        unset($_SESSION['keranjang']);
        
        echo "<div class='checkout-success'>";
        echo "<h2>Pesanan Berhasil!</h2>";
        echo "<p>Terima kasih telah berbelanja, <strong>$nama_pembeli</strong></p>";
        echo "<p>Total pembayaran: <strong>Rp " . number_format($total, 0, ',', '.') . "</strong></p>";
        echo "<p>Konfirmasi pesanan telah dikirim ke email Anda: <strong>$email</strong></p>";
        echo "<p>Status pesanan: <strong>Menunggu Pembayaran</strong></p>";
        echo "<a href='index.php' class='btn'>Kembali ke Beranda</a>";
        echo "</div>";
        include 'includes/footer.php';
        exit;
    }
}

// Jika keranjang kosong
if(empty($_SESSION['keranjang'])) {
    echo "<div class='container'>";
    echo "<p>Keranjang Anda kosong!</p>";
    echo "<a href='produk.php' class='btn'>Belanja Sekarang</a>";
    echo "</div>";
    include 'includes/footer.php';
    exit;
}
?>

    <section class="checkout-section">
        <div class="container">
            <h1>Checkout</h1>
            
            <div class="checkout-wrapper">
                <!-- Form -->
                <div class="checkout-form">
                    <h2>Data Pengiriman</h2>
                    <form method="POST">
                        <div class="form-group">
                            <label>Nama Lengkap:</label>
                            <input type="text" name="nama_pembeli" required>
                        </div>

                        <div class="form-group">
                            <label>Email:</label>
                            <input type="email" name="email" required>
                        </div>

                        <div class="form-group">
                            <label>Nomor HP:</label>
                            <input type="text" name="no_hp" required>
                        </div>

                        <div class="form-group">
                            <label>Alamat Pengiriman:</label>
                            <textarea name="alamat" rows="5" required></textarea>
                        </div>

                        <button type="submit" class="btn">Selesaikan Pesanan</button>
                        <a href="keranjang.php" class="btn-secondary">Kembali ke Keranjang</a>
                    </form>
                </div>

                <!-- Summary Pesanan -->
                <div class="checkout-summary">
                    <h2>Ringkasan Pesanan</h2>
                    <table class="summary-table">
                        <thead>
                            <tr>
                                <th>Produk</th>
                                <th>Qty</th>
                                <th>Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php 
                            $total = 0;
                            foreach($_SESSION['keranjang'] as $produk_id => $jumlah):
                                $query = "SELECT * FROM produk WHERE id = $produk_id";
                                $result = $conn->query($query);
                                $produk = $result->fetch_assoc();
                                $subtotal = $produk['harga'] * $jumlah;
                                $total += $subtotal;
                            ?>
                                <tr>
                                    <td><?php echo $produk['nama']; ?></td>
                                    <td><?php echo $jumlah; ?></td>
                                    <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="total-section">
                        <h3>Total: Rp <?php echo number_format($total, 0, ',', '.'); ?></h3>
                        <p class="info">Anda akan menerima instruksi pembayaran via email</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>