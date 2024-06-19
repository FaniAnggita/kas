<?php $menu = 'penjualan';
include '../lib/komponen/wrap-top.php'; ?>
<?php
$id = $_GET['id'];

// Fetch penjualan data
$penjualan = $conn->query("SELECT * FROM penjualan WHERE id_penjualan = $id")->fetch_assoc();

// Handle form submission for updating penjualan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_penjualan'])) {
    $keterangan_penjualan = $_POST['keterangan_penjualan'];
    $tgl_jual = $_POST['tgl_jual'];
    $harga = $_POST['harga'];
    $quantity = $_POST['quantity'];

    // Update penjualan table
    $sql = "UPDATE penjualan SET keterangan_penjualan = '$keterangan_penjualan', tgl_jual = '$tgl_jual', harga = '$harga', quantity = '$quantity' WHERE id_penjualan = $id";
    if ($conn->query($sql) === TRUE) {
        // Update kas table
        $keterangan_transaksi = "Penjualan: $keterangan_penjualan";
        $jenis_kas = 'masuk';
        $tgl_transaksi = $tgl_jual;

        $sql_kas = "UPDATE kas SET keterangan_transaksi = '$keterangan_transaksi', jenis_kas = '$jenis_kas', tgl_tranksasi = '$tgl_transaksi' WHERE id_penjualan = $id";
        if ($conn->query($sql_kas) === TRUE) {
            echo "<script>alert('Data berhasil diperbarui')</script>";
            echo "<script>window.location.href='index.php'</script>";
        } else {
            echo "Error: " . $sql_kas . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>



<h1 class="h3 mb-3">Edit Penjualan</h1>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <form method="post" action="" class="form-horizontal">
                    <div class="mb-3">
                        <label for="keterangan_penjualan" class="form-label">Keterangan Penjualan:</label>
                        <input type="text" name="keterangan_penjualan" id="keterangan_penjualan" value="<?php echo $penjualan['keterangan_penjualan']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_jual" class="form-label">Tanggal Jual:</label>
                        <input type="date" name="tgl_jual" id="tgl_jual" value="<?php echo $penjualan['tgl_jual']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="harga" class="form-label">Harga:</label>
                        <input type="number" name="harga" id="harga" value="<?php echo $penjualan['harga']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="quantity" class="form-label">Quantity:</label>
                        <input type="number" name="quantity" id="quantity" value="<?php echo $penjualan['quantity']; ?>" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" name="update_penjualan" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php';">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../lib/komponen/wrap-bottom.php'; ?>