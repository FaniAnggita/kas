<?php $menu = 'pengguna';
include '../lib/komponen/wrap-top.php'; ?>

<h1 class="h3 mb-3">Laporan Kas Masuk</h1>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <a href="register.php" class="btn btn-primary">
                    Registrasi Pengguna
                </a>
            </div>
            <div class="card-body">

                <table class="table table-striped table-bordered mt-4" id="table1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nama</th>
                            <th>Jabatan</th>
                            <th>Email</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql = "SELECT * FROM pengguna"; // Query untuk mengambil data pengguna
                        $result = $conn->query($sql);

                        if ($result->num_rows > 0) {
                            while ($row = $result->fetch_assoc()) {
                                echo "<tr>";
                                echo "<td>" . $row['id'] . "</td>";
                                echo "<td>" . $row['nama'] . "</td>";
                                echo "<td>" . $row['jabatan'] . "</td>";
                                echo "<td>" . $row['email'] . "</td>";
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='4'>Tidak ada data pengguna</td></tr>";
                        }

                        $conn->close();
                        ?>
                    </tbody>
                </table>



            </div>
        </div>
    </div>
</div>

<?php include '../lib/komponen/wrap-bottom.php'; ?>