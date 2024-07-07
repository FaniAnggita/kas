<?php $menu = 'pembelian';
include '../lib/komponen/wrap-top.php'; ?>
<?php
$id = $_GET['id'];

// Fetch pembelian data
$pembelian = $conn->query("SELECT * FROM pembelian WHERE id_pembelian = $id")->fetch_assoc();
$suppliers = $conn->query("SELECT * FROM supplier");

// Handle form submission for updating pembelian
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_pembelian'])) {
    $id_supplier = $_POST['id_supplier'];
    $keterangan = $_POST['keterangan_pembelian'];
    $tanggal_beli = $_POST['tanggal_beli'];
    $harga = $_POST['harga'];
    $quantity = $_POST['quantity'];

    // Update pembelian table
    $sql = "UPDATE pembelian SET id_supplier = '$id_supplier', keterangan_pembelian = '$keterangan', tanggal_beli = '$tanggal_beli', harga = '$harga', quantity = '$quantity' WHERE id_pembelian = $id";
    if ($conn->query($sql) === TRUE) {
        // Update kas table
        $keterangan_transaksi = "Pembelian: $keterangan";
        $jenis_kas = 'keluar';
        $tgl_transaksi = $tanggal_beli;

        $sql_kas = "UPDATE kas SET keterangan_transaksi = '$keterangan_transaksi', jenis_kas = '$jenis_kas', tgl_tranksasi = '$tgl_transaksi' WHERE id_pembelian = $id";
        if ($conn->query($sql_kas) === TRUE) {
            echo "<script>
            Swal.fire({
                title: 'Data Berhasil Diperbarui!',
                icon: 'success',
                confirmButtonText: 'Tutup'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'index.php';
                }
            });
        </script>";
        } else {
            echo "Error: " . $sql_kas . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>



<h1 class="h3 mb-3">Edit Pembelian</h1>
<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-body">
                <form method="post" action="" class="form-horizontal">
                    <div class="mb-3">
                        <label for="id_supplier" class="form-label">Supplier:</label>
                        <select name="id_supplier" id="id_supplier" class="form-select" required>
                            <?php while ($row = $suppliers->fetch_assoc()) : ?>
                                <option value="<?php echo $row['id_supplier']; ?>" <?php if ($row['id_supplier'] == $pembelian['id_supplier']) echo 'selected'; ?>>
                                    <?php echo $row['nama_supplier']; ?>
                                </option>
                            <?php endwhile; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="keterangan_pembelian" class="form-label">Keterangan Pembelian:</label>
                        <input type="text" name="keterangan_pembelian" id="keterangan_pembelian" value="<?php echo $pembelian['keterangan_pembelian']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_beli" class="form-label">Tanggal Beli:</label>
                        <input type="date" name="tanggal_beli" id="tanggal_beli" value="<?php echo $pembelian['tanggal_beli']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga:</label>
                        <input type="number" name="harga" id="harga" value="<?php echo $pembelian['harga']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" value="<?php echo $pembelian['quantity']; ?>" min="1" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" name="update_pembelian" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php';">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../lib/komponen/wrap-bottom.php'; ?>