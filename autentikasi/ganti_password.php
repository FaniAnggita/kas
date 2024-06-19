<?php
session_start();
$menu = 'sandi';
include '../config.php'; // Sambungkan ke file konfigurasi database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $currentPassword = $_POST['currentPassword'];
    $newPassword = $_POST['newPassword'];
    $confirmPassword = $_POST['confirmPassword'];

    // Ambil data pengguna dari database
    $userId = $_SESSION['user_id']; // Sesuaikan dengan bagaimana Anda menyimpan session user_id

    // Query untuk mengambil data pengguna berdasarkan user_id
    $sql = "SELECT * FROM pengguna WHERE id = $userId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $hashedPassword = $row['password'];

        // Verifikasi kata sandi saat ini
        if (password_verify($currentPassword, $hashedPassword)) {
            // Validasi kata sandi baru
            if ($newPassword == $confirmPassword) {
                // Enkripsi kata sandi baru
                $hashedNewPassword = password_hash($newPassword, PASSWORD_DEFAULT);

                // Update kata sandi di database
                $updateSql = "UPDATE pengguna SET password = '$hashedNewPassword' WHERE id = $userId";

                if ($conn->query($updateSql) === TRUE) {
                    echo "<script>alert('Password berhasil diperbarui')</script>";
                    echo "<script>window.location.href='../dashboard/index.php'</script>";
                } else {
                    echo "<script>alert('Password gagal diperbarui')</script>";
                }
            } else {
                echo "<script>alert('Password baru dengan password lama tidak cocok!')</script>";
            }
        } else {
            echo "<script>alert('Password baru salah!')</script>";
        }
    } else {
        echo "<script>alert('Pengguna tidak ditemukan!')</script>";
    }

    $conn->close();
}
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Responsive Admin &amp; Dashboard Template based on Bootstrap 5">
    <meta name="author" content="AdminKit">
    <meta name="keywords" content="adminkit, bootstrap, bootstrap 5, admin, dashboard, template, responsive, css, sass, html, theme, front-end, ui kit, web">

    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link rel="shortcut icon" href="img/icons/icon-48x48.png" />

    <link rel="canonical" href="https://demo-basic.adminkit.io/pages-sign-up.html" />

    <title>Masuk - Sistem Informasi Pencatatan Kas</title>

    <link href="../lib/css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">

                        <div class="text-center mt-4">
                            <h1 class="h2">Ganti Password</h1>

                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-3">
                                    <form action="" method="POST">
                                        <div class="form-group">
                                            <label for="currentPassword">Current Password:</label>
                                            <input type="password" class="form-control" id="currentPassword" name="currentPassword" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="newPassword">New Password:</label>
                                            <input type="password" class="form-control" id="newPassword" name="newPassword" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="confirmPassword">Confirm New Password:</label>
                                            <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" required>
                                        </div>
                                        <button type="submit" class="btn btn-primary">Change Password</button>
                                    </form>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </main>

    <script src="../lib/js/app.js"></script>

</body>

</html>