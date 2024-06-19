<?php
include '../config.php';

$id = $_GET['id'];

// Delete supplier
$sql = "DELETE FROM supplier WHERE id_supplier = $id";
if ($conn->query($sql) === TRUE) {
    echo "Supplier deleted successfully";
    header('Location: index.php');
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
