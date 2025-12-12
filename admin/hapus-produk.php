<?php
include '../includes/config.php';
include "protect-admin.php";

$action = $_POST['action'];

if($action == 'tambah') {
    $nama = $conn->real_escape_string($_POST['nama']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori = $conn->real_escape_string($_POST['kategori']);
    $gambar = $conn->real_escape_string($_POST['gambar']);

    $query = "INSERT INTO produk (nama, deskripsi, harga, stok, kategori, gambar) 
              VALUES ('$nama', '$deskripsi', $harga, $stok, '$kategori', '$gambar')";
    
    if($conn->query($query)) {
        $_SESSION['pesan'] = "Produk berhasil ditambahkan!";
        header("Location: dashboard.php");
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: tambah-produk.php");
    }
}

else if($action == 'edit') {
    $id = $_POST['id'];
    $nama = $conn->real_escape_string($_POST['nama']);
    $deskripsi = $conn->real_escape_string($_POST['deskripsi']);
    $harga = $_POST['harga'];
    $stok = $_POST['stok'];
    $kategori = $conn->real_escape_string($_POST['kategori']);
    $gambar = $conn->real_escape_string($_POST['gambar']);

    $query = "UPDATE produk SET nama='$nama', deskripsi='$deskripsi', harga=$harga, 
              stok=$stok, kategori='$kategori', gambar='$gambar' WHERE id=$id";
    
    if($conn->query($query)) {
        $_SESSION['pesan'] = "Produk berhasil diupdate!";
        header("Location: dashboard.php");
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: edit-produk.php?id=$id");
    }
}

else if($action == 'hapus') {
    $id = $_POST['id'];
    
    $query = "DELETE FROM produk WHERE id=$id";
    
    if($conn->query($query)) {
        $_SESSION['pesan'] = "Produk berhasil dihapus!";
        header("Location: dashboard.php");
    } else {
        $_SESSION['error'] = "Error: " . $conn->error;
        header("Location: dashboard.php");
    }
}
?>