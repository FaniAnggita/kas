<?php
session_start();
include '../config.php'; // Sambungkan ke file konfigurasi database
$message = '';
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
            $message = 'Password salah!';
        }
    } else {
        $message = 'Pengguna tidak ditemukan!';
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
    <style>
        #set-bg {
            background-image: url('../lib/komponen/bg.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }
    </style>
</head>

<body id="set-bg">
    <main class="d-flex w-100">
        <div class="container d-flex flex-column">
            <div class="row vh-100">
                <div class="col-sm-10 col-md-8 col-lg-6 col-xl-5 mx-auto d-table h-100">
                    <div class="d-table-cell align-middle">

                        <div class="text-center mt-4 ">
                            <h1 class="h2 text-white">Selamat Datang</h1>
                            <p class="lead text-white">
                                Sistem Informasi Pencatatan Kas
                            </p>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-3">
                                    <form action="" method="POST">

                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input class="form-control form-control-lg" type="email" name="email" placeholder="Masukkan Email!" required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <input class="form-control form-control-lg" type="password" name="password" placeholder="Masukkan Password!" required />
                                        </div>
                                        <div class="d-grid gap-2 mt-3">
                                            <input class="form-control form-control-lg bg-success text-white" type="submit" name="submit" value="Login">
                                        </div>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        <?php if ($message != '') : ?>
            Swal.fire({
                title: '<?= $message ?>',
                icon: 'error',
                confirmButtonText: 'Tutup'
            });
        <?php endif; ?>
    </script>
</body>

</html>