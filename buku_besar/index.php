<?php
include '../config.php';

// Fetch data from kas table with JOINs
$sql = "SELECT k.id_kas_masuk, 
               k.keterangan_transaksi, 
               k.jenis_kas, 
               k.tgl_tranksasi, 
               COALESCE(p.harga, pe.harga, b.nominal_biaya) AS harga,
               COALESCE(p.quantity, pe.quantity, b.quantity) AS quantity,
               COALESCE(p.harga * p.quantity, pe.harga * pe.quantity, b.nominal_biaya * b.quantity) AS total
        FROM kas k
        LEFT JOIN penjualan p ON k.id_penjualan = p.id_penjualan
        LEFT JOIN pembelian pe ON k.id_pembelian = pe.id_pembelian
        LEFT JOIN biaya b ON k.id_biaya = b.id_biaya
        ORDER BY k.tgl_tranksasi ASC";

$result = $conn->query($sql);

// Initialize the balance
$saldo = 0;
?>

<!DOCTYPE html>
<html>

<head>
    <title>Laporan Buku Besar</title>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        table,
        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <h1>Laporan Buku Besar</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Keterangan Transaksi</th>
                <th>Debit</th>
                <th>Kredit</th>
                <th>Saldo</th>
            </tr>
        </thead>
        <tbody>
            <?php $no = 1; ?>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $no++; ?></td>
                    <td><?php echo $row['tgl_tranksasi']; ?></td>
                    <td><?php echo $row['keterangan_transaksi']; ?></td>
                    <td>
                        <?php
                        if ($row['jenis_kas'] == 'masuk') {
                            $debit = $row['total'] ?? 0;
                            $kredit = 0;
                            $saldo += $debit;
                            echo number_format($debit, 2);
                        } else {
                            echo "0.00";
                        }
                        ?>
                    </td>
                    <td>
                        <?php
                        if ($row['jenis_kas'] == 'keluar') {
                            $kredit = $row['total'] ?? 0;
                            $debit = 0;
                            $saldo -= $kredit;
                            echo number_format($kredit, 2);
                        } else {
                            echo "0.00";
                        }
                        ?>
                    </td>
                    <td><?php echo number_format($saldo, 2); ?></td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</body>

</html>