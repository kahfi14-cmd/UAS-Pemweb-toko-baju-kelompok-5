<?php
include '../includes/config.php';
include "protect-admin.php";
$page_title = "Edit Produk - Admin";

$id = $_GET['id'];
$query = "SELECT * FROM produk WHERE id = $id";
$result = $conn->query($query);
$produk = $result->fetch_assoc();

if(!$produk) {
    echo "Produk tidak ditemukan!";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="stylesd.css">
    <style>
        .admin-nav {
            background-color: #34495e;
            padding: 20px;
            margin-bottom: 30px;
            text-align: center;
        }
        .admin-nav h2 {
            color: white;
            margin-bottom: 10px;
        }
        .admin-nav a {
            color: white;
            text-decoration: none;
            margin-right: 20px;
        }
        .form-container {
            background: white;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
            color: #2c3e50;
        }
        input, textarea, select {
            width: 100%;
            padding: 12px;
            border: 1px solid #bdc3c7;
            border-radius: 4px;
            font-size: 16px;
            font-family: inherit;
        }
        textarea {
            resize: vertical;
            min-height: 100px;
        }
        .btn-submit {
            background-color: #27ae60;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        .btn-submit:hover {
            background-color: #229954;
        }
        .btn-cancel {
            background-color: #95a5a6;
            color: white;
            padding: 12px 30px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin-left: 10px;
        }
    </style>
</head>
<body>
    <div class="admin-nav">
        <h2>Admin Panel - Toko Baju</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="tambah-produk.php">Tambah Produk</a>
        <a href="../index.php">Kembali ke Website</a>
    </div>

    <div class="container">
        <div class="form-container">
            <h1>Edit Produk</h1>
            
            <form method="POST" action="proses.php" enctype="multipart/form-data">
                <input type="hidden" name="action" value="edit">
                <input type="hidden" name="id" value="<?php echo $produk['id']; ?>">

                <div class="form-group">
                    <label for="nama">Nama Produk:</label>
                    <input type="text" id="nama" name="nama" value="<?php echo $produk['nama']; ?>" required>
                </div>

                <div class="form-group">
                    <label for="deskripsi">Deskripsi:</label>
                    <textarea id="deskripsi" name="deskripsi" required><?php echo $produk['deskripsi']; ?></textarea>
                </div>

                <div class="form-group">
                    <label for="harga">Harga (Rp):</label>
                    <input type="number" id="harga" name="harga" value="<?php echo $produk['harga']; ?>" required min="0">
                </div>

                <div class="form-group">
                    <label for="stok">Stok:</label>
                    <input type="number" id="stok" name="stok" value="<?php echo $produk['stok']; ?>" required min="0">
                </div>

                <div class="form-group">
                    <label for="kategori">Kategori:</label>
                    <select id="kategori" name="kategori" required>
                        <option value="pria" <?php echo $produk['kategori'] == 'pria' ? 'selected' : ''; ?>>Pria</option>
                        <option value="wanita" <?php echo $produk['kategori'] == 'wanita' ? 'selected' : ''; ?>>Wanita</option>
                        <option value="unisex" <?php echo $produk['kategori'] == 'unisex' ? 'selected' : ''; ?>>Unisex</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="gambar">Gambar Produk:</label>
                    <?php if($produk['gambar']) { ?>
                        <img src="../images/produk/<?php echo $produk['gambar']; ?>" alt="Gambar Produk" style="max-width: 200px; margin-bottom: 10px;"><br>
                    <?php } ?>
                    <input type="file" id="gambar" name="gambar" accept="image/*">
                    <small>Biarkan kosong jika tidak ingin mengubah gambar.</small>
                </div>

                <div>
                    <button type="submit" class="btn-submit">Simpan Perubahan</button>
                    <a href="dashboard.php" class="btn-cancel">Batal</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>