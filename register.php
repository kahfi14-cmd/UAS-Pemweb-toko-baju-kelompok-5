<?php
include 'includes/config.php';
$page_title = "Register - Toko Baju Online";

// Jika sudah login, redirect ke halaman utama
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $nama_lengkap = $conn->real_escape_string($_POST['nama_lengkap']);
    
    // Validasi
    if(strlen($username) < 3) {
        $error = "Username minimal 3 karakter!";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Email tidak valid!";
    } elseif(strlen($password) < 6) {
        $error = "Password minimal 6 karakter!";
    } elseif($password !== $confirm_password) {
        $error = "Password tidak cocok!";
    } else {
        // Cek apakah username sudah ada
        $check_query = "SELECT id FROM users WHERE username = '$username' OR email = '$email'";
        $check_result = $conn->query($check_query);
        
        if($check_result->num_rows > 0) {
            $error = "Username atau email sudah terdaftar!";
        } else {
            // Hash password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
            // Insert ke database
            $insert_query = "INSERT INTO users (username, email, password, nama_lengkap, role) 
                           VALUES ('$username', '$email', '$hashed_password', '$nama_lengkap', 'user')";
            
            if($conn->query($insert_query)) {
                $success = "Pendaftaran berhasil! Silakan login.";
            } else {
                $error = "Error: " . $conn->error;
            }
        }
    }
}

include 'includes/header.php';
?>

    <section class="auth-section">
        <div class="container">
            <div class="auth-container">
                <div class="auth-box">
                    <h1>Daftar Akun Baru</h1>
                    
                    <?php if($error): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <?php if($success): ?>
                        <div class="alert alert-success">
                            <?php echo $success; ?>
                            <a href="login.php" class="btn" style="display:block; margin-top:15px;">Login Sekarang</a>
                        </div>
                    <?php else: ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap:</label>
                            <input type="text" id="nama_lengkap" name="nama_lengkap" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" id="username" name="username" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="confirm_password">Konfirmasi Password:</label>
                            <input type="password" id="confirm_password" name="confirm_password" required>
                        </div>
                        
                        <button type="submit" class="btn">Daftar</button>
                    </form>
                    
                    <div class="auth-link">
                        <p>Sudah punya akun? <a href="login.php">Login di sini</a></p>
                    </div>
                    
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>