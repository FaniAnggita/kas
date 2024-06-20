<?php $menu = 'pembelian';
include '../lib/komponen/wrap-top.php';

// Handle form submission for adding pembelian
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_pembelian'])) {
    $id_supplier = $_POST['id_supplier'];
    $keterangan = $_POST['keterangan_pembelian'];
    $tanggal_beli = $_POST['tanggal_beli'];
    $harga = $_POST['harga'];
    $quantity = $_POST['quantity'];

    // Insert into pembelian table
    $sql = "INSERT INTO pembelian (id_supplier, keterangan_pembelian, tanggal_beli, harga, quantity) VALUES ('$id_supplier', '$keterangan', '$tanggal_beli', '$harga', '$quantity')";
    if ($conn->query($sql) === TRUE) {
        // Get the last inserted id
        $last_id = $conn->insert_id;

        // Insert into kas table
        $keterangan_transaksi = "Pembelian: $keterangan";
        $jenis_kas = 'keluar';
        $tgl_transaksi = $tanggal_beli;

        $sql_kas = "INSERT INTO kas (id_pembelian, keterangan_transaksi, jenis_kas, tgl_tranksasi) VALUES ('$last_id', '$keterangan_transaksi', '$jenis_kas', '$tgl_transaksi')";
        if ($conn->query($sql_kas) === TRUE) {
            echo "<script>alert('Data berhasil ditambahkan!')</script>";
        } else {
            echo "Error: " . $sql_kas . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all pembelian
$pembelian = $conn->query("SELECT * FROM pembelian JOIN supplier USING(id_supplier)");
$suppliers = $conn->query("SELECT * FROM supplier");
?>


<h1 class="h3 mb-3">Pembelian</h1>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tabel Pembelian</h5>
            </div>
            <div class="card-body">
                <?php if ($_SESSION['jabatan'] != 'owner') { ?>
                    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Tambah Pembelian
                    </button>
                <?php } ?>
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
                <div style="overflow: scroll;">
                    <table class="table table-striped table-bordered mt-4" id="table2">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tanggal Beli</th>
                                <th>Supplier</th>
                                <th>Keterangan</th>
                                <th>Harga</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $pembelian->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo $row['id_pembelian']; ?></td>
                                    <td><?php echo $row['tanggal_beli']; ?></td>
                                    <td><?php echo $row['nama_supplier']; ?></td>
                                    <td><?php echo $row['keterangan_pembelian']; ?></td>
                                    <td><?php echo $row['harga']; ?></td>
                                    <td><?php echo $row['quantity']; ?></td>
                                    <td><?php echo $row['quantity'] * $row['harga']; ?></td>
                                    <td>
                                        <a href="update_pembelian.php?id=<?php echo $row['id_pembelian']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                        <a href="delete_pembelian.php?id=<?php echo $row['id_pembelian']; ?>" class="btn btn-danger btn-sm">Delete</a>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>


                    </table>
                </div>


                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Form Tambah Pembelian</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="post" action="" class="form-horizontal">
                                    <div class="form-group">
                                        <label for="id_supplier" class="col-form-label">Supplier:</label>
                                        <select name="id_supplier" id="id_supplier" class="form-control" required>
                                            <?php while ($row = $suppliers->fetch_assoc()) : ?>
                                                <option value="<?php echo $row['id_supplier']; ?>"><?php echo $row['nama_supplier']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan_pembelian" class="col-form-label">Keterangan Pembelian:</label>
                                        <input type="text" name="keterangan_pembelian" id="keterangan_pembelian" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_beli" class="col-form-label">Tanggal Beli:</label>
                                        <input type="date" name="tanggal_beli" id="tanggal_beli" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="harga" class="col-form-label">Harga:</label>
                                        <input type="number" name="harga" id="harga" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="quantity" class="col-form-label">Quantity:</label>
                                        <input type="number" name="quantity" id="quantity" class="form-control" min="1" value="1" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <input type="submit" name="add_pembelian" class="btn btn-primary" value="Tambah">
                                        </div>
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



<?php include '../lib/komponen/wrap-bottom.php'; ?>