<?php
session_start();
include '../config.php'; // Sambungkan ke file konfigurasi database
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Query untuk mencari pengguna berdasarkan email
    $sql = "SELECT * FROM pengguna WHERE email = '$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        // Verifikasi password
        if (password_verify($password, $row['password'])) {
            // Password benar, buat session dan redirect ke halaman dashboard misalnya
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['nama'] = $row['nama'];
            $_SESSION['jabatan'] = $row['jabatan'];
            $_SESSION['email'] = $row['email'];

            header("Location: ../dashboard/index.php");
            exit();
        } else {
            echo "Password salah!";
        }
    } else {
        echo "Pengguna tidak ditemukan!";
    }
}


$conn->close();
?>


<!DOCTYPE html>
<html>

<head>
    <title>Login Pengguna</title>
</head>

<body>
    <h2>Login Pengguna</h2>
    <form action="" method="POST">
        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <input type="submit" name="submit" value="Login">
    </form>
</body>

</html>