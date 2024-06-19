<?php
include '../config.php';

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
            echo "Penjualan added successfully";
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

<!DOCTYPE html>
<html>

<head>
    <title>Data Penjualan</title>
</head>

<body>
    <h1>Data Penjualan</h1>
    <a href="index.php">Kembali ke Halaman Utama</a>
    <h2>Tambah Penjualan</h2>
    <form method="post" action="">
        Keterangan Penjualan: <input type="text" name="keterangan_penjualan" required><br>
        Tanggal Jual: <input type="date" name="tgl_jual" required><br>
        Harga: <input type="number" name="harga" required><br>
        Quantity: <input type="number" name="quantity" required><br>
        <input type="submit" name="add_penjualan" value="Tambah">
    </form>

    <h2>Daftar Penjualan</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Keterangan</th>
            <th>Tanggal Jual</th>
            <th>Harga</th>
            <th>Quantity</th>
            <th>Total</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $penjualan->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $row['id_penjualan']; ?></td>
                <td><?php echo $row['keterangan_penjualan']; ?></td>
                <td><?php echo $row['tgl_jual']; ?></td>
                <td><?php echo $row['harga']; ?></td>
                <td><?php echo $row['quantity']; ?></td>
                <td><?php echo $row['quantity'] * $row['harga']; ?></td>
                <td>
                    <a href="update_penjualan.php?id=<?php echo $row['id_penjualan']; ?>">Edit</a> |
                    <a href="delete_penjualan.php?id=<?php echo $row['id_penjualan']; ?>">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>