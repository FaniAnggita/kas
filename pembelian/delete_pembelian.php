<?php
include '../config.php';

$id = $_GET['id'];

// Delete pembelian
$sql = "DELETE FROM pembelian WHERE id_pembelian = $id";
if ($conn->query($sql) === TRUE) {
    // Delete from kas
    $sql_kas = "DELETE FROM kas WHERE id_pembelian = $id";
    if ($conn->query($sql_kas) === TRUE) {
        echo "Pembelian deleted successfully";
        header('Location: index.php');
    } else {
        echo "Error: " . $sql_kas . "<br>" . $conn->error;
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
