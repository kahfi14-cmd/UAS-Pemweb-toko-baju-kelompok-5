<?php
include 'includes/config.php';
$page_title = "Beranda - Toko Baju Online";
include 'includes/header.php';

// Ambil 4 produk terbaru
$query = "SELECT * FROM produk LIMIT 4";
$result = $conn->query($query);
?>

    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <h1>Selamat Datang di Kelompok 5 Store</h1>
            <p>Dapatkan koleksi baju terbaik dengan harga terjangkau</p>
            <a href="produk.php" class="btn">Belanja Sekarang</a>
        </div>
    </section>

    <!-- Featured Products -->
    <section class="products-section">
        <div class="container">
            <h2>Produk Unggulan</h2>
            <div class="products-grid">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="images/produk/<?php echo $row['gambar'] ? $row['gambar'] : 'placeholder.jpg'; ?>" alt="<?php echo $row['nama']; ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo $row['nama']; ?></h3>
                            <p class="description"><?php echo substr($row['deskripsi'], 0, 50); ?>...</p>
                            <p class="price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                            <p class="stock">Stok: <?php echo $row['stok']; ?></p>
                            <form method="POST" action="keranjang.php">
                                <input type="hidden" name="action" value="tambah">
                                <input type="hidden" name="produk_id" value="<?php echo $row['id']; ?>">
                                <input type="number" name="jumlah" value="1" min="1">
                                <button type="submit" class="btn">Tambah ke Keranjang</button>
                            </form>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>