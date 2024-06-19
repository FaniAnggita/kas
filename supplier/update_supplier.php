<?php $menu = 'supplier';
include '../lib/komponen/wrap-top.php'; ?>
<?php
$id = $_GET['id'];

// Fetch supplier data
$supplier = $conn->query("SELECT * FROM supplier WHERE id_supplier = $id")->fetch_assoc();

// Handle form submission for updating supplier
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_supplier'])) {
    $nama = $_POST['nama_supplier'];
    $telp = $_POST['no_telfon'];
    $alamat = $_POST['alamat'];

    $sql = "UPDATE supplier SET nama_supplier = '$nama', no_telfon = '$telp', alamat = '$alamat' WHERE id_supplier = $id";
    if ($conn->query($sql) === TRUE) {
        echo "<script>alert('Data berhasil diperbarui')</script>";
        echo "<script>window.location.href='index.php'</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>



<h1 class="h3 mb-3">Edit Supplier</h1>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="" class="needs-validation" novalidate>
                    <div class="mb-3">
                        <label for="nama_supplier" class="form-label">Nama Supplier:</label>
                        <input type="text" name="nama_supplier" id="nama_supplier" class="form-control" value="<?php echo $supplier['nama_supplier']; ?>" required>
                        <div class="invalid-feedback">Nama supplier harus diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label for="no_telfon" class="form-label">No Telfon:</label>
                        <input type="text" name="no_telfon" id="no_telfon" class="form-control" value="<?php echo $supplier['no_telfon']; ?>" required>
                        <div class="invalid-feedback">No telfon harus diisi.</div>
                    </div>
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat:</label>
                        <textarea name="alamat" id="alamat" class="form-control" required><?php echo $supplier['alamat']; ?></textarea>
                        <div class="invalid-feedback">Alamat harus diisi.</div>
                    </div>
                    <div class="mb-3">
                        <button type="submit" name="update_supplier" class="btn btn-primary">Update</button>
                        <a href="index.php" class="btn btn-secondary">Batal</a>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>

<?php include '../lib/komponen/wrap-bottom.php'; ?>