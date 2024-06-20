<?php $menu = 'biaya';
include '../lib/komponen/wrap-top.php'; ?>
<?php

// Handle form submission for adding biaya
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_biaya'])) {
    $keterangan_biaya = $_POST['keterangan_biaya'];
    $tgl_biaya = $_POST['tgl_biaya'];
    $nominal_biaya = $_POST['nominal_biaya'];

    // Insert into biaya table
    $sql = "INSERT INTO biaya (keterangan_biaya, tgl_biaya, nominal_biaya) VALUES ('$keterangan_biaya', '$tgl_biaya', '$nominal_biaya')";
    if ($conn->query($sql) === TRUE) {
        // Get the last inserted id
        $last_id = $conn->insert_id;

        // Insert into kas table
        $keterangan_transaksi = "Biaya: $keterangan_biaya";
        $jenis_kas = 'keluar';
        $tgl_transaksi = $tgl_biaya;

        $sql_kas = "INSERT INTO kas (id_biaya, keterangan_transaksi, jenis_kas, tgl_tranksasi) VALUES ('$last_id', '$keterangan_transaksi', '$jenis_kas', '$tgl_transaksi')";
        if ($conn->query($sql_kas) === TRUE) {
            echo "Biaya added successfully";
        } else {
            echo "Error: " . $sql_kas . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all biaya
$biaya = $conn->query("SELECT * FROM biaya");
?>



<h1 class="h3 mb-3">Pembiayaan</h1>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tabel Pembiayaan</h5>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Tambah Pembiayaan
                </button>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-inline">
                            <label class="mr-2" for="min-date">Tanggal Awal:</label>
                            <input type="text" id="min-date" class="form-control date-range-filter mr-2 mb-2">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-inline">
                            <label class="mr-2" for="max-date">Tanggal Akhir:</label>
                            <input type="text" id="max-date" class="form-control date-range-filter">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-inline">
                            <label class="mr-2" for="search-bar">Pencarian:</label>
                            <input type="text" id="search-bar" class="form-control search-bar mr-2 mb-2">
                        </div>
                    </div>
                    <div class="col-md-12 text-md-end">
                        <div class="grid">
                            <button class="btn btn-secondary btn-sm" id="btn-export"><i class="align-middle" data-feather="printer"></i> Cetak</button>
                        </div>
                    </div>
                </div>

                <table class="table table-striped table-bordered mt-4" id="table1">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Nominal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $biaya->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $row['id_biaya']; ?></td>
                                <td><?php echo $row['tgl_biaya']; ?></td>
                                <td><?php echo $row['keterangan_biaya']; ?></td>
                                <td><?php echo $row['nominal_biaya']; ?></td>
                                <td>
                                    <a href="update_biaya.php?id=<?php echo $row['id_biaya']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="delete_biaya.php?id=<?php echo $row['id_biaya']; ?>" class="btn btn-danger btn-sm">Delete</a>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>

                </table>

                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Form Tambah Pembiayaan</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <form method="post" action="" class="form-horizontal">
                                        <div class="mb-3">
                                            <label for="keterangan_biaya" class="form-label">Keterangan Biaya:</label>
                                            <input type="text" name="keterangan_biaya" id="keterangan_biaya" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tgl_biaya" class="form-label">Tanggal Biaya:</label>
                                            <input type="date" name="tgl_biaya" id="tgl_biaya" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="nominal_biaya" class="form-label">Nominal Biaya:</label>
                                            <input type="number" name="nominal_biaya" id="nominal_biaya" class="form-control" required>
                                        </div>
                                        <div class="d-flex justify-content-end">
                                            <button type="submit" name="add_biaya" class="btn btn-primary">Tambah</button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
</div>

<?php include '../lib/komponen/wrap-bottom.php'; ?>