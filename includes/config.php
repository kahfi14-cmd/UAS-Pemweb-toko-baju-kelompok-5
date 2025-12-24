<?php
// Konfigurasi Database
$host = "localhost";
$user = "root";
$password = ""; 
$database = "toko_baju";

// Koneksi ke database
$conn = new mysqli($host, $user, $password, $database);

// Check koneksi
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Set charset
$conn->set_charset("utf8");

// Mulai session hanya jika belum aktif
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
?>