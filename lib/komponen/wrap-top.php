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
    <title>Sistem Informasi Pencatatan Kas</title>
    <style>
        .chart-container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            text-align: center;
        }

        .dashboard-container {
            width: 80%;
            margin: 0 auto;
            padding: 20px;
            font-family: Arial, sans-serif;
        }

        .stat-box {
            display: inline-block;
            width: 30%;
            margin: 10px;
            padding: 20px;
            background-color: #f2f2f2;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-align: center;
        }

        .stat-box h3 {
            margin: 10px 0;
            font-size: 24px;
        }

        .stat-box p {
            font-size: 18px;
        }
    </style>


</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <nav id="sidebar" class="sidebar js-sidebar">
            <div class="sidebar-content js-simplebar">
                <a class="sidebar-brand" href="index.html">
                    <span class="align-middle">Sistem Informasi Pencatatan Kas</span>
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
                    <li class="sidebar-header">
                        Kelola Pengguna
                    </li>

                    <li class="sidebar-item <?php echo $menu === 'pengguna' ? 'active' : ''; ?>">
                        <a class="sidebar-link" href="../autentikasi/pengguna.php">
                            <i class="align-middle" data-feather="user-plus"></i> <span class="align-middle">Daftar Pengguna</span>
                        </a>
                    </li>
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
                                <a class="dropdown-item" href="../autentikasi/logout.php">Log out</a>
                            </div>
                        </li>
                    </ul>
                </div>
            </nav>
            <!-- end top bar -->

            <main class="content">
                <div class="container-fluid p-0">