
<?php
include '../config.php';

// Fetch data penjualan
$sql = "SELECT tgl_jual, SUM(harga * quantity) AS total_penjualan
        FROM penjualan
        GROUP BY tgl_jual
        ORDER BY tgl_jual ASC";
$result = $conn->query($sql);

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}

echo json_encode($data);
