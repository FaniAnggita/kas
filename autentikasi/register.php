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
        header("Location: pengguna.php");
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
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

    <title>Registrasi - Sistem Informasi Pencatatan Kas</title>

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
                            <h1 class="h2">Registrasi</h1>
                            <p class="lead">
                                Sistem Informasi Pencatatan Kas
                            </p>
                        </div>

                        <div class="card">
                            <div class="card-body">
                                <div class="m-sm-3">
                                    <form action="" method="POST">
                                        <div class="mb-3">
                                            <label class="form-label">Nama Lengkap</label>
                                            <input class="form-control form-control-lg" type="text" name="nama" placeholder="Masukkan Nama Lengkap!" required />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Jabatan</label>
                                            <select class="form-control form-control-lg" name="jabatan">
                                                <option value="admin">Admin</option>
                                                <option value="owner">Owner</option>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input class="form-control form-control-lg" type="email" name="email" placeholder="Masukkan Email!" />
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Password</label>
                                            <input class="form-control form-control-lg" type="password" name="password" placeholder="Masukkan Password!" />
                                        </div>
                                        <div class="d-grid gap-2 mt-3">
                                            <input class="form-control form-control-lg bg-danger text-white" type="submit" name="submit" value="Registrasi">
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

</body>

</html>