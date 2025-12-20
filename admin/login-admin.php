<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include '../includes/config.php';

// Jika sudah login, langsung masuk dashboard
if (isset($_SESSION['admin'])) {
    header("Location: dashboard.php");
    exit;
}

if (isset($_POST['login'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']); 

    $query = mysqli_query($conn, "SELECT * FROM admin WHERE username='$username' AND password='$password'");
    $cek = mysqli_num_rows($query);

    if ($cek > 0) {
        $_SESSION['admin'] = $username;
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Username atau password salah!";
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Login Admin</title>
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4; 
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        /* Container Login Utama */
        .auth-box {
            background: #fff; 
            padding: 30px;
            border-radius: 5px; 
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); 
            width: 100%;
            max-width: 350px; 
        }

        /* Judul */
        .auth-box h1 {
            text-align: center;
            margin-bottom: 25px;
            color: #333;
            font-size: 24px;
        }

        /* Grup Formulir */
        .form-group {
            margin-bottom: 15px;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #555;
        }

        /* Input Teks/Password */
        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ccc; 
            border-radius: 4px;
            box-sizing: border-box; 
            font-size: 16px;
        }

        .form-group input:focus {
            border-color: #48002bff; 
            outline: none;
        }

        /* Tombol Login */
        .btn {
            width: 100%;
            background-color: #06004eff; 
            color: white;
            padding: 10px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            font-weight: bold;
            margin-top: 10px;
            transition: background-color 0.3s;
        }

        .btn:hover {
            background-color: #b80046ff; 
        }

        /* Pesan Error (Alert) */
        .alert-danger {
            background-color: #f2dede; 
            color: #a94442; 
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ebccd1;
            border-radius: 4px;
            text-align: center;
        }
        </style>
</head>
<body>

<div class="auth-section">
    <div class="auth-container">
        <div class="auth-box">
            <h1>Login Admin</h1>

            <?php if (isset($error)) { ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php } ?>

            <form method="POST">
                <div class="form-group">
                    <label>Username Admin</label>
                    <input type="text" name="username" required>
                </div>

                <div class="form-group">
                    <label>Password Admin</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit" name="login" class="btn">Login</button>
            </form>

        </div>
    </div>
</div>

</body>
</html>
