<?php
require('../config.php');
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: ../autentikasi/login.php");
    exit();
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
    <link rel="shortcut icon" href="../lib/img/icons/icon-48x48.png" />

    <!-- <link rel="canonical" href="https://demo-basic.adminkit.io/" /> -->

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">



    <link href="../lib/css/app.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title><?= $menu; ?></title>

    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap4.min.css">
    <!-- jQuery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <!-- Bootstrap JS (jika Anda menggunakan Bootstrap) -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>
    <!-- DataTables CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <!-- Buttons extension CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.3.6/css/buttons.dataTables.min.css">
    <!-- jQuery UI CSS for Datepicker -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <style>
        #set-bg {
            background-image: url('../lib/komponen/bg.jpg');
            background-position: center;
            background-repeat: no-repeat;
            background-size: cover;
        }

        .dt-top-container {
            display: flex-end;
            justify-content: end;
            align-items: end;
        }



        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_length,
        .dataTables_wrapper .dataTables_info {
            display: none;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const deleteButtons = document.querySelectorAll('.delete-btn');

            deleteButtons.forEach(button => {
                button.addEventListener('click', function(event) {
                    event.preventDefault();
                    const url = this.getAttribute('data-url');

                    Swal.fire({
                        title: 'Apakah Anda yakin?',
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#3085d6',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = url;
                        }
                    });
                });
            });
        });
    </script>
</head>

<body>
    <div class="wrapper" style="background-color: red;">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="index.html">
                    <!-- <span class="align-middle">Sistem Informasi Pencatatan Kas</span> -->
                </a>

                <ul class="sidebar-nav">
                    <li class="sidebar-header">
                        Umum
                    </li>

                    <li class="sidebar-item <?php echo $menu === 'dashboard' ? 'active' : ''; ?>">
                        <a class="sidebar-link" href="../dashboard">
                            <i class="align-middle" data-feather="grid"></i> <span class="align-middle">Dashboard</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo $menu === 'barang' ? 'active' : ''; ?>">
                        <a class="sidebar-link" href="../barang">
                            <i class="align-middle" data-feather="archive"></i> <span class="align-middle">Barang</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?php echo $menu === 'pembelian' ? 'active' : ''; ?>">
                        <a class="sidebar-link" href="../pembelian">
                            <i class="align-middle" data-feather="log-in"></i> <span class="align-middle">Pembelian</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo $menu === 'penjualan' ? 'active' : ''; ?>">
                        <a class="sidebar-link" href="../penjualan">
                            <i class="align-middle" data-feather="log-out"></i> <span class="align-middle">Penjualan</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo $menu === 'biaya' ? 'active' : ''; ?>">
                        <a class="sidebar-link" href="../biaya">
                            <i class="align-middle" data-feather="briefcase"></i> <span class="align-middle">Pembiayaan</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?php echo $menu === 'supplier' ? 'active' : ''; ?>">
                        <a class="sidebar-link" href="../supplier">
                            <i class="align-middle" data-feather="truck"></i> <span class="align-middle">Supplier</span>
                        </a>
                    </li>

                    <li class="sidebar-header">
                        Laporan-laporan
                    </li>

                    <li class="sidebar-item <?php echo $menu === 'kas_masuk' ? 'active' : ''; ?>">
                        <a class="sidebar-link" href="../laporan_kas/kas_masuk.php">
                            <i class="align-middle" data-feather="arrow-down-circle"></i> <span class="align-middle">Kas Masuk</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo $menu === 'kas_keluar' ? 'active' : ''; ?>">
                        <a class="sidebar-link" href="../laporan_kas/kas_keluar.php">
                            <i class="align-middle" data-feather="arrow-up-circle"></i> <span class="align-middle">Kas Keluar</span>
                        </a>
                    </li>

                    <li class="sidebar-item <?php echo $menu === 'buku_besar' ? 'active' : ''; ?>">
                        <a class="sidebar-link" href="../buku_besar">
                            <i class="align-middle" data-feather="table"></i> <span class="align-middle">Buku Besar</span>
                        </a>
                    </li>
                    <li class="sidebar-item <?php echo $menu === 'laba_rugi' ? 'active' : ''; ?>">
                        <a class="sidebar-link" href="../laba_rugi">
                            <i class="align-middle" data-feather="bar-chart"></i> <span class="align-middle">Laba/Rugi</span>
                        </a>
                    </li>
                    <li class="sidebar-header">
                        Kelola Pengguna
                    </li>

                    <?php
                    if ($_SESSION['jabatan'] != 'owner') { ?>
                        <li class="sidebar-item <?php echo $menu === 'pengguna' ? 'active' : ''; ?>">
                            <a class="sidebar-link" href="../autentikasi/pengguna.php">
                                <i class="align-middle" data-feather="user-plus"></i> <span class="align-middle">Daftar Pengguna</span>
                            </a>
                        </li>
                    <?php  } ?>

                    <li class="sidebar-item <?php echo $menu === 'sandi' ? 'active' : ''; ?>">
                        <a class="sidebar-link" href="../autentikasi/ganti_password.php">
                            <i class="align-middle" data-feather="key"></i> <span class="align-middle">Profil</span>
                        </a>
                    </li>

                </ul>

            </div>
        </nav>
        <!-- End Sidebar -->

        <div class="main">
            <!-- top bar -->
            <nav class="navbar navbar-expand navbar-light navbar-bg">
                <a class="sidebar-toggle js-sidebar-toggle">
                    <i class="hamburger align-self-center"></i>
                </a>
                <div>
                    <h3 class="text-dark mt-2">Sistem Informasi Pencatatan Kas Amelia</h3>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="navbar-nav navbar-align">

                        <li class="nav-item dropdown">
                            <a class="nav-icon dropdown-toggle d-inline-block d-sm-none" href="#" data-bs-toggle="dropdown">
                                <i class="align-middle" data-feather="settings"></i>
                            </a>

                            <a class="nav-link dropdown-toggle d-none d-sm-inline-block" href="#" data-bs-toggle="dropdown">
                                <span class="text-dark">Halo, <?= $_SESSION['nama'] ?></span>

                            </a>
                            <div class="dropdown-menu dropdown-menu-end">
                                <a class="dropdown-item" href="../autentikasi/ganti_password.php"><i class="align-middle me-1" data-feather="user"></i> Ganti Password</a>
                                <div class="dropdown-divider"></div>
                                <div class="dropdown-divider"></div>
                                <a class="dropdown-item" href="../autentikasi/logout.php">Keluar</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- end top bar -->

            <main class="content" id="set-bg">
                <div class="container-fluid p-0">