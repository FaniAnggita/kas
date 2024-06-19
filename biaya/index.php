<?php
include '../config.php';

// Handle form submission for adding biaya
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_biaya'])) {
    $keterangan_biaya = $_POST['keterangan_biaya'];
    $tgl_biaya = $_POST['tgl_biaya'];
    $nominal_biaya = $_POST['nominal_biaya'];

    // Insert into biaya table
    $sql = "INSERT INTO biaya (keterangan_biaya, tgl_biaya, nominal_biaya) VALUES ('$keterangan_biaya', '$tgl_biaya', '$nominal_biaya')";
    if ($conn->query($sql) === TRUE) {
        // Get the last inserted id
        $last_id = $conn->insert_id;

        // Insert into kas table
        $keterangan_transaksi = "Biaya: $keterangan_biaya";
        $jenis_kas = 'keluar';
        $tgl_transaksi = $tgl_biaya;

        $sql_kas = "INSERT INTO kas (id_biaya, keterangan_transaksi, jenis_kas, tgl_tranksasi) VALUES ('$last_id', '$keterangan_transaksi', '$jenis_kas', '$tgl_transaksi')";
        if ($conn->query($sql_kas) === TRUE) {
            echo "Biaya added successfully";
        } else {
            echo "Error: " . $sql_kas . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all biaya
$biaya = $conn->query("SELECT * FROM biaya");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Biaya</title>
</head>

<body>
    <h1>Data Biaya</h1>
    <a href="index.php">Kembali ke Halaman Utama</a>
    <h2>Tambah Biaya</h2>
    <form method="post" action="">
        Keterangan Biaya: <input type="text" name="keterangan_biaya" required><br>
        Tanggal Biaya: <input type="date" name="tgl_biaya" required><br>
        Nominal Biaya: <input type="number" name="nominal_biaya" required><br>
        <input type="submit" name="add_biaya" value="Tambah">
    </form>

    <h2>Daftar Biaya</h2>
    <table border="1">
        <tr>
            <th>ID</th>
            <th>Keterangan</th>
            <th>Tanggal</th>
            <th>Nominal</th>
            <th>Aksi</th>
        </tr>
        <?php while ($row = $biaya->fetch_assoc()) : ?>
            <tr>
                <td><?php echo $row['id_biaya']; ?></td>
                <td><?php echo $row['keterangan_biaya']; ?></td>
                <td><?php echo $row['tgl_biaya']; ?></td>
                <td><?php echo $row['nominal_biaya']; ?></td>
                <td>
                    <a href="update_biaya.php?id=<?php echo $row['id_biaya']; ?>">Edit</a> |
                    <a href="delete_biaya.php?id=<?php echo $row['id_biaya']; ?>">Delete</a>
                </td>
            </tr>
        <?php endwhile; ?>
    </table>
</body>

</html>