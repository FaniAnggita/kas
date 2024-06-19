<?php
include '../config.php';

$id = $_GET['id'];

// Delete penjualan
$sql = "DELETE FROM penjualan WHERE id_penjualan = $id";
if ($conn->query($sql) === TRUE) {
    // Delete from kas
    $sql_kas = "DELETE FROM kas WHERE id_penjualan = $id";
    if ($conn->query($sql_kas) === TRUE) {
        echo "Penjualan deleted successfully";
        header('Location: index.php');
    } else {
        echo "Error: " . $sql_kas . "<br>" . $conn->error;
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
