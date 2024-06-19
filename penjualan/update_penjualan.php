<?php
include '../config.php';

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
            echo "Penjualan updated successfully";
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
    <title>Edit Penjualan</title>
</head>

<body>
    <h1>Edit Penjualan</h1>
    <a href="penjualan.php">Kembali ke Data Penjualan</a>
    <form method="post" action="">
        Keterangan Penjualan: <input type="text" name="keterangan_penjualan" value="<?php echo $penjualan['keterangan_penjualan']; ?>" required><br>
        Tanggal Jual: <input type="date" name="tgl_jual" value="<?php echo $penjualan['tgl_jual']; ?>" required><br>
        Harga: <input type="number" name="harga" value="<?php echo $penjualan['harga']; ?>" required><br>
        Quantity: <input type="number" name="quantity" value="<?php echo $penjualan['quantity']; ?>" required><br>
        <input type="submit" name="update_penjualan" value="Update">
    </form>
</body>

</html>