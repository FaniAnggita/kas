<?php $menu = 'penjualan';
include '../lib/komponen/wrap-top.php'; ?>
<?php
// Assuming you have a database connection in $conn
// Fetch penjualan data
$id = $_GET['id']; // or whatever way you're getting the id_penjualan
$penjualan = $conn->query("SELECT * FROM penjualan WHERE id_penjualan = $id")->fetch_assoc();
$barang = $conn->query("SELECT * FROM barang");

// Handle form submission for updating penjualan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_penjualan'])) {
    $kode_barang = $_POST['kode_barang'];
    $tgl_jual = $_POST['tgl_jual'];
    $harga = $_POST['harga'];
    $new_quantity = $_POST['quantity'];

    // Fetch current stock and original quantity of the selected item
    $barang_data = $conn->query("SELECT stok FROM barang WHERE kode_barang = '$kode_barang'")->fetch_assoc();
    $current_stok = $barang_data['stok'];
    $original_quantity = $penjualan['quantity'];

    // Calculate the difference between the new quantity and the original quantity
    $quantity_diff = $new_quantity - $original_quantity;

    // Validate if the stock is sufficient
    if ($current_stok >= $quantity_diff) {
        // Update penjualan table
        $sql = "UPDATE penjualan SET kode_barang = '$kode_barang', tgl_jual = '$tgl_jual', harga = '$harga', quantity = '$new_quantity' WHERE id_penjualan = $id";
        if ($conn->query($sql) === TRUE) {
            // Update the stock
            $new_stok = $current_stok - $quantity_diff;
            $sql_barang = "UPDATE barang SET stok = $new_stok WHERE kode_barang = '$kode_barang'";
            $conn->query($sql_barang);

            // Update kas table
            $keterangan_transaksi = $kode_barang;
            $jenis_kas = 'masuk';
            $tgl_transaksi = $tgl_jual;

            $sql_kas = "UPDATE kas SET keterangan_transaksi = '$keterangan_transaksi', jenis_kas = '$jenis_kas', tgl_tranksasi = '$tgl_transaksi' WHERE id_penjualan = $id";
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
    } else {
        echo "<script>
            Swal.fire({
                title: 'Stok tidak mencukupi!',
                icon: 'error',
                confirmButtonText: 'Tutup'
            });
        </script>";
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
                        <label for="kode_barang" class="form-label">Kode Barang:</label>
                        <select name="kode_barang" id="kode_barang" class="form-select" required>
                            <?php while ($row = $barang->fetch_assoc()) : ?>
                                <option value="<?php echo $row['kode_barang']; ?>" <?php if ($row['kode_barang'] == $penjualan['kode_barang']) echo 'selected'; ?>>
                                    <?php echo $row['kode_barang'] . " - " . $row['nama_barang'] . " (<b>" .  $row['stok'] . "</b>)"; ?>

                                </option>
                            <?php endwhile; ?>
                        </select>
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