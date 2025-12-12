<?php
include 'includes/config.php';
$page_title = "Produk - Toko Baju Online";
include 'includes/header.php';

// Filter kategori
$kategori = isset($_GET['kategori']) ? $_GET['kategori'] : '';
$query = "SELECT * FROM produk";
if($kategori) {
    $query .= " WHERE kategori = '" . $conn->real_escape_string($kategori) . "'";
}
$result = $conn->query($query);
?>

    <section class="products-page">
        <div class="container">
            <h1>Katalog Produk</h1>
            
            <div class="filter">
                <a href="produk.php" class="filter-btn">Semua</a>
                <a href="produk.php?kategori=pria" class="filter-btn">Pria</a>
                <a href="produk.php?kategori=wanita" class="filter-btn">Wanita</a>
                <a href="produk.php?kategori=unisex" class="filter-btn">Unisex</a>
            </div>

            <div class="products-grid">
                <?php while($row = $result->fetch_assoc()): ?>
                    <div class="product-card">
                        <div class="product-image">
                            <img src="images/produk/<?php echo $row['gambar'] ? $row['gambar'] : 'placeholder.jpg'; ?>" alt="<?php echo $row['nama']; ?>">
                        </div>
                        <div class="product-info">
                            <h3><?php echo $row['nama']; ?></h3>
                            <p class="category"><?php echo ucfirst($row['kategori']); ?></p>
                            <p class="description"><?php echo $row['deskripsi']; ?></p>
                            <p class="price">Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></p>
                            <p class="stock <?php echo $row['stok'] > 0 ? 'available' : 'empty'; ?>">
                                <?php echo $row['stok'] > 0 ? 'Stok: ' . $row['stok'] : 'Stok Habis'; ?>
                            </p>
                            <?php if($row['stok'] > 0): ?>
                                <form method="POST" action="keranjang.php">
                                    <input type="hidden" name="action" value="tambah">
                                    <input type="hidden" name="produk_id" value="<?php echo $row['id']; ?>">
                                    <input type="number" name="jumlah" value="1" min="1" max="<?php echo $row['stok']; ?>">
                                    <button type="submit" class="btn">Tambah ke Keranjang</button>
                                </form>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>