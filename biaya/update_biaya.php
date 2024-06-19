<?php
include '../config.php';

$id = $_GET['id'];

// Fetch biaya data
$biaya = $conn->query("SELECT * FROM biaya WHERE id_biaya = $id")->fetch_assoc();

// Handle form submission for updating biaya
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_biaya'])) {
    $keterangan_biaya = $_POST['keterangan_biaya'];
    $tgl_biaya = $_POST['tgl_biaya'];
    $nominal_biaya = $_POST['nominal_biaya'];

    // Update biaya table
    $sql = "UPDATE biaya SET keterangan_biaya = '$keterangan_biaya', tgl_biaya = '$tgl_biaya', nominal_biaya = '$nominal_biaya' WHERE id_biaya = $id";
    if ($conn->query($sql) === TRUE) {
        // Update kas table
        $keterangan_transaksi = "Biaya: $keterangan_biaya";
        $jenis_kas = 'keluar';
        $tgl_transaksi = $tgl_biaya;

        $sql_kas = "UPDATE kas SET keterangan_transaksi = '$keterangan_transaksi', jenis_kas = '$jenis_kas', tgl_tranksasi = '$tgl_transaksi' WHERE id_biaya = $id";
        if ($conn->query($sql_kas) === TRUE) {
            echo "Biaya updated successfully";
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
    <title>Edit Biaya</title>
</head>

<body>
    <h1>Edit Biaya</h1>
    <a href="biaya.php">Kembali ke Data Biaya</a>
    <form method="post" action="">
        Keterangan Biaya: <input type="text" name="keterangan_biaya" value="<?php echo $biaya['keterangan_biaya']; ?>" required><br>
        Tanggal Biaya: <input type="date" name="tgl_biaya" value="<?php echo $biaya['tgl_biaya']; ?>" required><br>
        Nominal Biaya: <input type="number" name="nominal_biaya" value="<?php echo $biaya['nominal_biaya']; ?>" required><br>
        <input type="submit" name="update_biaya" value="Update">
    </form>
</body>

</html>