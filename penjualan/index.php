<?php $menu = 'penjualan';
include '../lib/komponen/wrap-top.php'; ?>
<?php
// Handle form submission for adding penjualan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_penjualan'])) {
    $keterangan_penjualan = $_POST['keterangan_penjualan'];
    $tgl_jual = $_POST['tgl_jual'];
    $harga = $_POST['harga'];
    $quantity = $_POST['quantity'];

    // Insert into penjualan table
    $sql = "INSERT INTO penjualan (keterangan_penjualan, tgl_jual, harga, quantity) VALUES ('$keterangan_penjualan', '$tgl_jual', '$harga', '$quantity')";
    if ($conn->query($sql) === TRUE) {
        // Get the last inserted id
        $last_id = $conn->insert_id;

        // Insert into kas table
        $keterangan_transaksi = "Penjualan: $keterangan_penjualan";
        $jenis_kas = 'masuk';
        $tgl_transaksi = $tgl_jual;

        $sql_kas = "INSERT INTO kas (id_penjualan, keterangan_transaksi, jenis_kas, tgl_tranksasi) VALUES ('$last_id', '$keterangan_transaksi', '$jenis_kas', '$tgl_transaksi')";
        if ($conn->query($sql_kas) === TRUE) {
            echo "<script>alert('Data berhasil ditambahkan!')</script>";
        } else {
            echo "Error: " . $sql_kas . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all penjualan
$penjualan = $conn->query("SELECT * FROM penjualan");
?>





<h1 class="h3 mb-3">Penjualan</h1>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tabel Penjualan</h5>
            </div>
            <div class="card-body">
                <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
                    Tambah Penjualan
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

                <table class="table table-striped table-bordered mt-4" id="table2">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tanggal Jual</th>
                            <th>Keterangan</th>

                            <th>Harga</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $penjualan->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $row['id_penjualan']; ?></td>
                                <td><?php echo $row['tgl_jual']; ?></td>
                                <td><?php echo $row['keterangan_penjualan']; ?></td>

                                <td><?php echo $row['harga']; ?></td>
                                <td><?php echo $row['quantity']; ?></td>
                                <td><?php echo $row['quantity'] * $row['harga']; ?></td>
                                <td>
                                    <a href="update_penjualan.php?id=<?php echo $row['id_penjualan']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                    <a href="delete_penjualan.php?id=<?php echo $row['id_penjualan']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>


                </table>

                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Form Tambah Penjualan</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <form method="post" action="" class="form-horizontal">
                                        <div class="mb-3">
                                            <label for="keterangan_penjualan" class="form-label">Keterangan Penjualan:</label>
                                            <input type="text" name="keterangan_penjualan" id="keterangan_penjualan" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tgl_jual" class="form-label">Tanggal Jual:</label>
                                            <input type="date" name="tgl_jual" id="tgl_jual" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="harga" class="form-label">Harga:</label>
                                            <input type="number" name="harga" id="harga" class="form-control" required>
                                        </div>
                                        <div class="mb-3">
                                            <label for="quantity" class="form-label">Quantity:</label>
                                            <input type="number" name="quantity" id="quantity" class="form-control" required>
                                        </div>
                                        <div class="d-flex justify-content-between">
                                            <button type="submit" name="add_penjualan" class="btn btn-primary">Tambah</button>
                                            <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php';">Batal</button>
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