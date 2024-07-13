<?php $menu = 'buku_besar';
include '../lib/komponen/wrap-top.php'; ?>
<?php

// Fetch data from kas table with JOINs
$sql = "SELECT k.id_kas_masuk, 
               k.keterangan_transaksi, 
               k.jenis_kas, 
                 br.kode_barang,
                 br.nama_barang,
               k.tgl_tranksasi, 
               COALESCE(p.harga, pe.harga, b.nominal_biaya) AS harga,
               COALESCE(p.quantity, pe.quantity, b.quantity) AS quantity,
               COALESCE(p.harga * p.quantity, pe.harga * pe.quantity, b.nominal_biaya * b.quantity) AS total
        FROM kas k
        LEFT JOIN penjualan p ON k.id_penjualan = p.id_penjualan
        LEFT JOIN pembelian pe ON k.id_pembelian = pe.id_pembelian
        LEFT JOIN biaya b ON k.id_biaya = b.id_biaya
          LEFT JOIN barang br ON br.kode_barang = k.keterangan_transaksi
        ORDER BY k.tgl_tranksasi ASC";

$result = $conn->query($sql);

// Initialize the balance
$saldo = 0;
?>

<h1 class="h3 mb-3 text-white">Laporan Buku Besar</h1>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">

            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-4">
                        <div class="form-inline">
                            <label class="mr-2" for="min-date">Tanggal Awal:</label>
                            <input type="text" id="min-date" class="form-control date-range-filter mr-2 mb-2">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-inline">
                            <label class="mr-2" for="max-date">Tanggal Akhir:</label>
                            <input type="text" id="max-date" class="form-control date-range-filter">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-inline">
                            <label class="mr-2" for="search-bar">Pencarian:</label>
                            <input type="text" id="search-bar" class="form-control search-bar mr-2 mb-2">
                        </div>
                    </div>
                    <div class="col-md-12 text-md-end">
                        <div class="grid">
                            <button class="btn btn-secondary btn-sm" id="btn-export"><i class="align-middle" data-feather="printer"></i> Cetak</button>
                        </div>
                    </div>
                </div>
                <div style="overflow-x: scroll;">
                    <table class="table table-striped table-bordered mt-4" id="table1">
                        <thead>
                            <tr class="text-center">
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
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
                                    <td><?php echo $row['kode_barang']; ?></td>
                                    <td><?php echo $row['nama_barang'] == '' ? $row['keterangan_transaksi'] : $row['nama_barang']; ?></td>
                                    <td class="text-end">
                                        <?php
                                        if ($row['jenis_kas'] == 'masuk') {
                                            $debit = $row['total'] ?? 0;
                                            $kredit = 0;
                                            $saldo += $debit;
                                            echo number_format($debit, 2);
                                        } else {
                                            echo "";
                                        }
                                        ?>
                                    </td>
                                    <td class="text-end">
                                        <?php
                                        if ($row['jenis_kas'] == 'keluar') {
                                            $kredit = $row['total'] ?? 0;
                                            $debit = 0;
                                            $saldo -= $kredit;
                                            echo number_format($kredit, 2);
                                        } else {
                                            echo "";
                                        }
                                        ?>
                                    </td>
                                    <td class="text-end"><?php echo number_format($saldo, 2); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>

                </div>



            </div>
        </div>
    </div>
</div>

<?php include '../lib/komponen/wrap-bottom.php'; ?>