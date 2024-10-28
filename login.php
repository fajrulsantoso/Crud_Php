<?php
// Memeriksa apakah form telah disubmit
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Kredensial hardcoded untuk demonstrasi
    $valid_username = "admin"; 
    $valid_password = "password123"; 

    // Mengambil input dari form
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Memeriksa apakah kredensial yang dimasukkan sesuai
    if ($username === $valid_username && $password === $valid_password) {
        session_start(); // Memulai sesi
        $_SESSION['user'] = $username; // Simpan informasi pengguna
        header("Location: crud.php"); // Redirect ke halaman CRUD
        exit(); // Menghentikan eksekusi script
    }
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="login-container">
    <h2 class="text-center">Login</h2>
    <form method="post"> <!-- Form untuk login -->
        <div class="form-group">
            <label for="username">Username</label>
            <input type="text" class="form-control" id="username" name="username" required>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" class="form-control" id="password" name="password" required>
        </div>
        <button type="submit" class="btn btn-primary btn-block">Login</button> <!-- Tombol login dengan lebar penuh -->
    </form>
</div>
</body>
</html>
