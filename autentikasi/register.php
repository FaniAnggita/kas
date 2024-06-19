<?php
include '../config.php'; // Sambungkan ke file konfigurasi database

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    // Ambil data dari formulir registrasi
    $nama = $_POST['nama'];
    $jabatan = $_POST['jabatan'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Enkripsi password

    // Query untuk menyimpan data pengguna ke dalam tabel
    $sql = "INSERT INTO pengguna (nama, jabatan, email, password) VALUES ('$nama', '$jabatan', '$email', '$password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registrasi berhasil!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}

?>


<!DOCTYPE html>
<html>

<head>
    <title>Registrasi Pengguna</title>
</head>

<body>
    <h2>Registrasi Pengguna</h2>
    <form action="" method="POST">
        <label>Nama:</label><br>
        <input type="text" name="nama" required><br><br>

        <label>Jabatan:</label><br>
        <input type="text" name="jabatan" required><br><br>

        <label>Email:</label><br>
        <input type="email" name="email" required><br><br>

        <label>Password:</label><br>
        <input type="password" name="password" required><br><br>

        <input type="submit" name="submit" value="Daftar">
    </form>
</body>

</html>