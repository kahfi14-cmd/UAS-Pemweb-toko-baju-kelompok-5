<?php
include 'includes/config.php';
$page_title = "Login - Toko Baju Online";

// Jika sudah login, redirect ke halaman utama
if(isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    
    // Query cari user
    $query = "SELECT * FROM users WHERE username = '$username' OR email = '$username'";
    $result = $conn->query($query);
    
    if($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        // Verify password
        if(password_verify($password, $user['password'])) {
            // Login berhasil
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['nama_lengkap'] = $user['nama_lengkap'];
            $_SESSION['role'] = $user['role'];
            
            // Redirect sesuai role
            if($user['role'] == 'admin') {
                header("Location: admin/dashboard.php");
            } else {
                header("Location: index.php");
            }
            exit;
        } else {
            $error = "Password salah!";
        }
    } else {
        $error = "Username atau email tidak ditemukan!";
    }
}

include 'includes/header.php';
?>

    <section class="auth-section">
        <div class="container">
            <div class="auth-container">
                <div class="auth-box">
                    <h1>Login</h1>
                    
                    <?php if($error): ?>
                        <div class="alert alert-danger">
                            <?php echo $error; ?>
                        </div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label for="username">Username atau Email:</label>
                            <input type="text" id="username" name="username" required autofocus>
                        </div>
                        
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" required>
                        </div>
                        
                        <button type="submit" class="btn">Login</button>
                    </form>
                    
                    <div class="auth-link">
                        <p>Belum punya akun? <a href="register.php">Daftar di sini</a></p>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>