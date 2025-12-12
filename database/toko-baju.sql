-- --------------------------------------------------------
-- DATABASE: toko_baju
-- --------------------------------------------------------

CREATE DATABASE IF NOT EXISTS toko_baju;
USE toko_baju;

-- --------------------------------------------------------
-- TABLE: admin
-- --------------------------------------------------------
CREATE TABLE admin (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

-- Insert admin default
INSERT INTO admin (username, password)
VALUES 
('admin', SHA2('admin123', 256));

-- --------------------------------------------------------
-- TABLE: users
-- --------------------------------------------------------
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL UNIQUE,
    email VARCHAR(150) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(150),
    alamat TEXT,
    no_hp VARCHAR(20),
    role VARCHAR(50) DEFAULT 'customer',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Contoh user
INSERT INTO users (username, email, password, nama_lengkap, alamat, no_hp, role)
VALUES
('kahfi', 'kahfi@example.com', SHA2('password123', 256), 'Kahfi', 'Bandung', '08123456789', 'customer');

-- --------------------------------------------------------
-- TABLE: produk
-- --------------------------------------------------------
CREATE TABLE produk (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nama VARCHAR(100) NOT NULL,
    deskripsi TEXT,
    harga INT NOT NULL,
    stok INT NOT NULL,
    kategori VARCHAR(50),
    gambar VARCHAR(255),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Insert data contoh
INSERT INTO produk (nama, deskripsi, harga, stok, kategori, gambar) VALUES
('T-Shirt Putih', 'T-shirt premium 100% cotton', 50000, 10, 'pria', 'tshirt-putih.jpg'),
('Kemeja Biru', 'Kemeja formal biru dongker', 150000, 5, 'pria', 'kemeja-biru.jpg'),
('Dress Merah', 'Dress pesta warna merah cerah', 200000, 8, 'wanita', 'dress-merah.jpg'),
('Kaos Hitam', 'Kaos kasual hitam polos', 45000, 15, 'unisex', 'kaos-hitam.jpg');

-- --------------------------------------------------------
-- TABLE: keranjang
-- --------------------------------------------------------
CREATE TABLE keranjang (
    id INT PRIMARY KEY AUTO_INCREMENT,
    session_id VARCHAR(255),
    produk_id INT,
    jumlah INT,
    FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
);

-- --------------------------------------------------------
-- TABLE: pesanan
-- --------------------------------------------------------
CREATE TABLE pesanan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    total INT,
    status VARCHAR(50) DEFAULT 'pending',
    alamat_kirim TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
);

-- --------------------------------------------------------
-- TABLE: detail_pesanan
-- --------------------------------------------------------
CREATE TABLE detail_pesanan (
    id INT PRIMARY KEY AUTO_INCREMENT,
    pesanan_id INT,
    produk_id INT,
    jumlah INT,
    subtotal INT,
    FOREIGN KEY (pesanan_id) REFERENCES pesanan(id) ON DELETE CASCADE,
    FOREIGN KEY (produk_id) REFERENCES produk(id) ON DELETE CASCADE
);
