<?php
include '../config.php';

// Handle form submission for adding supplier
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_supplier'])) {
    $nama = $_POST['nama_supplier'];
    $telp = $_POST['no_telfon'];
    $alamat = $_POST['alamat'];

    $sql = "INSERT INTO supplier (nama_supplier, no_telfon, alamat) VALUES ('$nama', '$telp', '$alamat')";
    if ($conn->query($sql) === TRUE) {
        echo "Supplier added successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all suppliers
$suppliers = $conn->query("SELECT * FROM supplier");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Supplier</title>
</head>

<body>
    <h1>Data Supplier</h1>
    <a href="index.php">Kembali ke Halaman Utama</a>
    <h2>Tambah Supplier</h2>
    <form method="post" action="">
        Nama Supplier: <input type="text" name="nama_supplier" required><br>
        No Telfon: <input type="text" name="no_telfon" required><br>
        Alamat: <textarea name="alamat" required></textarea><br>
        <input type="submit" name="add_supplier" value="Tambah">
    </form>

    <h2>Daftar Supplier</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>No Telfon</th>
            <th>Alamat</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $suppliers->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $row['id_supplier']; ?></td>
                <td><?php echo $row['nama_supplier']; ?></td>
                <td><?php echo $row['no_telfon']; ?></td>
                <td><?php echo $row['alamat']; ?></td>
                <td>
                    <a href="update_supplier.php?id=<?php echo $row['id_supplier']; ?>">Edit</a> |
                    <a href="delete_supplier.php?id=<?php echo $row['id_supplier']; ?>">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>