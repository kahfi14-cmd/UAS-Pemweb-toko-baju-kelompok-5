<?php
include 'includes/config.php';
$page_title = "Edit Profil - Toko Baju Online";

// Jika belum login
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = $user_id";
$result = $conn->query($query);
$user = $result->fetch_assoc();

$success = '';
$error = '';

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_lengkap = $conn->real_escape_string($_POST['nama_lengkap']);
    $no_hp = $conn->real_escape_string($_POST['no_hp']);
    $alamat = $conn->real_escape_string($_POST['alamat']);
    
    $update_query = "UPDATE users SET nama_lengkap='$nama_lengkap', no_hp='$no_hp', alamat='$alamat' WHERE id=$user_id";
    
    if($conn->query($update_query)) {
        $_SESSION['nama_lengkap'] = $nama_lengkap;
        $success = "Profil berhasil diupdate!";
    } else {
        $error = "Error: " . $conn->error;
    }
}

include 'includes/header.php';
?>

    <section class="auth-section">
        <div class="container">
            <div class="auth-container">
                <div class="auth-box">
                    <h1>Edit Profil</h1>
                    
                    <?php if($error): ?>
                        <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <?php if($success): ?>
                        <div class="alert alert-success"><?php echo $success; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="form-group">
                            <label>Nama Lengkap:</label>
                            <input type="text" name="nama_lengkap" value="<?php echo $user['nama_lengkap']; ?>" required>
                        </div>
                        
                        <div class="form-group">
                            <label>No. HP:</label>
                            <input type="text" name="no_hp" value="<?php echo $user['no_hp'] ?? ''; ?>">
                        </div>
                        
                        <div class="form-group">
                            <label>Alamat:</label>
                            <textarea name="alamat" rows="4"><?php echo $user['alamat'] ?? ''; ?></textarea>
                        </div>
                        
                        <button type="submit" class="btn">Simpan Perubahan</button>
                        <a href="profil.php" class="btn-secondary">Batal</a>
                    </form>
                </div>
            </div>
        </div>
    </section>

<?php include 'includes/footer.php'; ?>