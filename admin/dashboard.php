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
    <title><?php echo $page_title; ?></title>
    <link rel="stylesheet" href="style.css">
    <style>
        .admin-nav {
            background-color: #34495e;
            padding: 20px;
            margin-bottom: 50px;
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
        .admin-nav a:hover {
            color: #3498db;
        }
        .admin-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            margin-top: 20px;
        }
        .admin-table th {
            background-color: #2c3e50;
            color: white;
            padding: 15px;
            text-align: left;
        }
        .admin-table td {
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
        }
        .admin-table tr:hover {
            background-color: #f8f9fa;
        }
        .action-btn {
            padding: 8px 15px;
            margin-right: 5px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
        }
        .btn-edit {
            background-color: #3498db;
            color: white;
        }
        .btn-edit:hover {
            background-color: #2980b9;
        }
        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }
        .btn-delete:hover {
            background-color: #c0392b;
        }
    </style>
</head>
<body>
    <div class="admin-nav">
        <h2>Admin Panel - Toko Baju</h2>
        <a href="dashboard.php">Dashboard</a>
        <a href="tambah-produk.php">Tambah Produk</a>
        <a href="../index.php">Kembali ke Website</a>
        <a href="logout.php">Logout</a>
    </div>

    <div class="container">
        <h1>Daftar Produk</h1>
        
        <table class="admin-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Produk</th>
                    <th>Kategori</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?php echo $row['id']; ?></td>
                        <td><?php echo $row['nama']; ?></td>
                        <td><?php echo ucfirst($row['kategori']); ?></td>
                        <td>Rp <?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                        <td><?php echo $row['stok']; ?></td>
                        <td>
                            <a href="edit-produk.php?id=<?php echo $row['id']; ?>" class="action-btn btn-edit">Edit</a>
                            <form method="POST" action="proses.php" style="display:inline;">
                                <input type="hidden" name="action" value="hapus">
                                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                <button type="submit" class="action-btn btn-delete" onclick="return confirm('Yakin hapus?')">Hapus</button>
                            </form>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>