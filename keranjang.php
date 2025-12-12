<?php
include 'includes/config.php';
$page_title = "Keranjang - Toko Baju Online";

// Handle tambah/hapus produk
if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $action = $_POST['action'];
    $produk_id = $_POST['produk_id'];
    
    if(!isset($_SESSION['keranjang'])) {
        $_SESSION['keranjang'] = [];
    }
    
    if($action == 'tambah') {
        $jumlah = $_POST['jumlah'];
        if(isset($_SESSION['keranjang'][$produk_id])) {
            $_SESSION['keranjang'][$produk_id] += $jumlah;
        } else {
            $_SESSION['keranjang'][$produk_id] = $jumlah;
        }
    } elseif($action == 'hapus') {
        unset($_SESSION['keranjang'][$produk_id]);
    }
}

include 'includes/header.php';
?>

    <section class="cart-section">
        <div class="container">
            <h1>Keranjang Belanja</h1>
            
            <?php if(!empty($_SESSION['keranjang'])): ?>
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Produk</th>
                            <th>Harga</th>
                            <th>Jumlah</th>
                            <th>Subtotal</th>
                            <th>Aksi</th>
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
                                <td>Rp <?php echo number_format($produk['harga'], 0, ',', '.'); ?></td>
                                <td><?php echo $jumlah; ?></td>
                                <td>Rp <?php echo number_format($subtotal, 0, ',', '.'); ?></td>
                                <td>
                                    <form method="POST" style="display:inline;">
                                        <input type="hidden" name="action" value="hapus">
                                        <input type="hidden" name="produk_id" value="<?php echo $produk_id; ?>">
                                        <button type="submit" class="btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="cart-summary">
                    <h2>Total: Rp <?php echo number_format($total, 0, ',', '.'); ?></h2>
                    <a href="checkout.php" class="btn">Lanjut ke Checkout</a>
                    <a href="produk.php" class="btn-secondary">Lanjut Belanja</a>
                </div>
            <?php else: ?>
                <p class="empty-message">Keranjang Anda kosong</p>
                <a href="produk.php" class="btn">Mulai Belanja</a>
            <?php endif; ?>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>