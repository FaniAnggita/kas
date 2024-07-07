<?php
include '../config.php';

$id = $_GET['id'];

// Delete pembelian
$sql = "DELETE FROM barang WHERE kode_barang = '$id'";
if ($conn->query($sql) === TRUE) {

    echo "Pembelian deleted successfully";
    header('Location: index.php');
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
