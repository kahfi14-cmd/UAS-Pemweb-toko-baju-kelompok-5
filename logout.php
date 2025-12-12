<?php
include 'includes/config.php';

// Hapus session
session_destroy();

// Redirect ke halaman utama
header("Location: index.php");
exit;
?>