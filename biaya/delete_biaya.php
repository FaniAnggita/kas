<?php
include '../config.php';

$id = $_GET['id'];

// Delete biaya
$sql = "DELETE FROM biaya WHERE id_biaya = $id";
if ($conn->query($sql) === TRUE) {
    // Delete from kas
    $sql_kas = "DELETE FROM kas WHERE id_biaya = $id";
    if ($conn->query($sql_kas) === TRUE) {
        echo "Biaya deleted successfully";
        header('Location: index.php');
    } else {
        echo "Error: " . $sql_kas . "<br>" . $conn->error;
    }
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}
