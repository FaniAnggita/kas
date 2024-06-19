<?php $menu = 'kas_keluar';
include '../lib/komponen/wrap-top.php'; ?>
<?php

// Fetch data from kas table with JOINs
$sql = "SELECT k.id_kas_masuk, 
               k.keterangan_transaksi, 
               k.jenis_kas, 
               k.tgl_tranksasi, 
               CASE 
                    WHEN k.jenis_kas = 'masuk' THEN p.harga
                    WHEN k.jenis_kas = 'keluar' AND k.id_pembelian IS NOT NULL THEN pe.harga
                    WHEN k.jenis_kas = 'keluar' AND k.id_biaya IS NOT NULL THEN b.nominal_biaya
               END AS harga,
               CASE 
                    WHEN k.jenis_kas = 'masuk' THEN p.quantity
                    WHEN k.jenis_kas = 'keluar' AND k.id_pembelian IS NOT NULL THEN pe.quantity
                    WHEN k.jenis_kas = 'keluar' AND k.id_biaya IS NOT NULL THEN b.quantity
               END AS quantity,
               CASE 
                    WHEN k.jenis_kas = 'masuk' THEN p.harga * p.quantity
                    WHEN k.jenis_kas = 'keluar' AND k.id_pembelian IS NOT NULL THEN pe.harga * pe.quantity
                    WHEN k.jenis_kas = 'keluar' AND k.id_biaya IS NOT NULL THEN b.nominal_biaya * b.quantity
               END AS total
        FROM kas k
        LEFT JOIN penjualan p ON k.id_penjualan = p.id_penjualan
        LEFT JOIN pembelian pe ON k.id_pembelian = pe.id_pembelian
        LEFT JOIN biaya b ON k.id_biaya = b.id_biaya
        WHERE k .jenis_kas = 'keluar'
        ORDER BY k.tgl_tranksasi DESC";

$result = $conn->query($sql);
?>

<h1 class="h3 mb-3">Laporan Kas Keluar</h1>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">

            </div>
            <div class="card-body">

                <table class="table table-striped table-bordered mt-4" id="table1">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Keterangan Transaksi</th>
                            <th>Jenis Kas</th>
                            <th>Harga</th>
                            <!-- <th>Quantity</th> -->
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $no = 1; ?>
                        <?php while ($row = $result->fetch_assoc()) : ?>
                            <tr>
                                <td><?php echo $no++; ?></td>
                                <td><?php echo $row['keterangan_transaksi']; ?></td>
                                <!-- <td><?php echo $row['jenis_kas']; ?></td> -->
                                <td><?php echo isset($row['harga']) ? number_format($row['harga'], 2) : ''; ?></td>
                                <td><?php echo isset($row['quantity']) ? $row['quantity'] : ''; ?></td>
                                <td><?php echo isset($row['total']) ? number_format($row['total'], 2) : ''; ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>



            </div>
        </div>
    </div>
</div>

<?php include '../lib/komponen/wrap-bottom.php'; ?>