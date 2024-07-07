<?php $menu = 'supplier';
include '../lib/komponen/wrap-top.php';;

// Handle form submission for adding supplier
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_supplier'])) {
    $nama = $_POST['nama_supplier'];
    $telp = $_POST['no_telfon'];
    $alamat = $_POST['alamat'];

    $sql = "INSERT INTO supplier (nama_supplier, no_telfon, alamat) VALUES ('$nama', '$telp', '$alamat')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>Swal.fire({
            title: 'Data Berhasil Ditambahkan!',
            icon: 'success',
            confirmButtonText: 'Tutup'
            })</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all suppliers
$suppliers = $conn->query("SELECT * FROM supplier");
?>

<h1 class="h3 mb-3">Supplier</h1>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tabel Supplier</h5>
            </div>
            <div class="card-body">
                <?php if ($_SESSION['jabatan'] != 'owner') { ?>
                    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Tambah Supplier
                    </button>
                <?php } ?>
                <div style="overflow-x: scroll;">
                    <table class="table table-striped table-bordered mt-4">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>No Telfon</th>
                                <th>Alamat</th>
                                <?php if ($_SESSION['jabatan'] != 'owner') { ?>
                                    <th>Aksi</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $suppliers->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo $row['id_supplier']; ?></td>
                                    <td><?php echo $row['nama_supplier']; ?></td>
                                    <td><?php echo $row['no_telfon']; ?></td>
                                    <td><?php echo $row['alamat']; ?></td>

                                    <?php if ($_SESSION['jabatan'] != 'owner') { ?>
                                        <td>
                                            <a href="update_supplier.php?id=<?php echo $row['id_supplier']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                            <!-- <a href="delete_supplier.php?id=<?php echo $row['id_supplier']; ?>" class="btn btn-danger btn-sm">Delete</a> -->
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>

                    </table>
                </div>



                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Form Tambah Supplier</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container">

                                    <form method="post" action="">
                                        <div class="mb-3">
                                            <label for="nama_supplier" class="form-label">Nama Supplier:</label>
                                            <input type="text" name="nama_supplier" id="nama_supplier" class="form-control" required>

                                        </div>
                                        <div class="mb-3">
                                            <label for="no_telfon" class="form-label">No Telfon:</label>
                                            <input type="text" name="no_telfon" id="no_telfon" class="form-control" required>

                                        </div>
                                        <div class="mb-3">
                                            <label for="alamat" class="form-label">Alamat:</label>
                                            <textarea name="alamat" id="alamat" class="form-control" required></textarea>

                                        </div>
                                        <div class="text-end">
                                            <button type="submit" name="add_supplier" class="btn btn-primary">Tambah</button>
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

</html>