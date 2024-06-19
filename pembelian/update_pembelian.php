<?php
include '../config.php';

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
            echo "Pembelian updated successfully";
            header('Location: index.php');
        } else {
            echo "Error: " . $sql_kas . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Pembelian</title>
</head>

<body>
    <h1>Edit Pembelian</h1>
    <a href="pembelian.php">Kembali ke Data Pembelian</a>
    <form method="post" action="">
        Supplier:
        <select name="id_supplier" required>
            <?php while ($row = $suppliers->fetch_assoc()) : ?>
                <option value="<?php echo $row['id_supplier']; ?>" <?php if ($row['id_supplier'] == $pembelian['id_supplier']) echo 'selected'; ?>>
                    <?php echo $row['nama_supplier']; ?>
                </option>
            <?php endwhile; ?>
        </select><br>
        Keterangan Pembelian: <input type="text" name="keterangan_pembelian" value="<?php echo $pembelian['keterangan_pembelian']; ?>" required><br>
        Tanggal Beli: <input type="date" name="tanggal_beli" value="<?php echo $pembelian['tanggal_beli']; ?>" required><br>
        Harga: <input type="number" name="harga" value="<?php echo $pembelian['harga']; ?>" required><br>
        Quantity: <input type="number" name="quantity" value="<?php echo $pembelian['quantity']; ?>" required><br>
        <input type="submit" name="update_pembelian" value="Update">
    </form>
</body>

</html>