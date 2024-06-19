<?php
include '../config.php';

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
        echo "Supplier updated successfully";
        header('Location: index.php');
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Edit Supplier</title>
</head>

<body>
    <h1>Edit Supplier</h1>
    <a href="supplier.php">Kembali ke Data Supplier</a>
    <form method="post" action="">
        Nama Supplier: <input type="text" name="nama_supplier" value="<?php echo $supplier['nama_supplier']; ?>" required><br>
        No Telfon: <input type="text" name="no_telfon" value="<?php echo $supplier['no_telfon']; ?>" required><br>
        Alamat: <textarea name="alamat" required><?php echo $supplier['alamat']; ?></textarea><br>
        <input type="submit" name="update_supplier" value="Update">
    </form>
</body>

</html>