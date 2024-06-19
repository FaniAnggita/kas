<?php include '../lib/komponen/wrap-top.php';

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
            echo "Pembelian added successfully";
        } else {
            echo "Error: " . $sql_kas . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all pembelian
$pembelian = $conn->query("SELECT * FROM pembelian");
$suppliers = $conn->query("SELECT * FROM supplier");
?>


<h1 class="h3 mb-3">Pembelian</h1>
<a href="index.php">Kembali ke Halaman Utama</a>
<h2>Tambah Pembelian</h2>
<form method="post" action="">
    Supplier:
    <select name="id_supplier" required>
        <?php while ($row = $suppliers->fetch_assoc()) : ?>
            <option value="<?php echo $row['id_supplier']; ?>"><?php echo $row['nama_supplier']; ?></option>
        <?php endwhile; ?>
    </select><br>
    Keterangan Pembelian: <input type="text" name="keterangan_pembelian" required><br>
    Tanggal Beli: <input type="date" name="tanggal_beli" required><br>
    Harga: <input type="number" name="harga" required><br>
    Quantity: <input type="number" name="quantity" required><br>
    <input type="submit" name="add_pembelian" value="Tambah">
</form>

<h2>Daftar Pembelian</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Supplier</th>
        <th>Keterangan</th>
        <th>Tanggal Beli</th>
        <th>Harga</th>
        <th>Quantity</th>
        <th>Total</th>
        <th>Aksi</th>
    </tr>
    <?php while ($row = $pembelian->fetch_assoc()) : ?>
        <tr>
            <td><?php echo $row['id_pembelian']; ?></td>
            <td><?php echo $row['id_supplier']; ?></td>
            <td><?php echo $row['keterangan_pembelian']; ?></td>
            <td><?php echo $row['tanggal_beli']; ?></td>
            <td><?php echo $row['harga']; ?></td>
            <td><?php echo $row['quantity']; ?></td>
            <td><?php echo $row['quantity'] * $row['harga']; ?></td>
            <td>
                <a href="update_pembelian.php?id=<?php echo $row['id_pembelian']; ?>">Edit</a> |
                <a href="delete_pembelian.php?id=<?php echo $row['id_pembelian']; ?>">Delete</a>
            </td>
        </tr>
    <?php endwhile; ?>
</table>

<?php include '../lib/komponen/wrap-bottom.php';
