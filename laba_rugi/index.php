<?php $menu = 'laba_rugi';
include '../lib/komponen/wrap-top.php'; ?>
<?php
$tanggalAwal = '';
$tanggalAkhir = '';
$balance = 0;
$total_kas_keluar = 0;
$total_kas_masuk = 0;
$keterangan = "";

// Arrays untuk menyimpan detail kas masuk dan keluar
$kas_masuk_list = [];
$kas_keluar_list = [];

if (isset($_POST['submit'])) {
    $tanggalAwal = $_POST['tanggalAwal'];
    $tanggalAkhir = $_POST['tanggalAkhir'];

    // Fetch total kas keluar dari biaya saja
    $sql_keluar = "SELECT 
                CONCAT('Biaya - ', bi.keterangan_biaya) AS nama, 
                bi.nominal_biaya * bi.quantity AS harga
               FROM biaya bi
               WHERE bi.tgl_biaya BETWEEN '$tanggalAwal' AND '$tanggalAkhir'";

    $result_keluar = $conn->query($sql_keluar);
    while ($row_keluar = $result_keluar->fetch_assoc()) {
        $kas_keluar_list[] = $row_keluar;
        $total_kas_keluar += $row_keluar['harga'];
    }

    // Fetch total kas masuk dari penjualan
    $sql_masuk = "SELECT 
                CONCAT('Penjualan - ', b.nama_barang) AS nama, 
                p.harga * p.quantity AS harga
              FROM kas k
              LEFT JOIN penjualan p ON k.id_penjualan = p.id_penjualan
              LEFT JOIN barang b ON p.kode_barang = b.kode_barang
              WHERE k.jenis_kas = 'masuk' 
              AND k.tgl_tranksasi BETWEEN '$tanggalAwal' AND '$tanggalAkhir'";

    $result_masuk = $conn->query($sql_masuk);
    while ($row_masuk = $result_masuk->fetch_assoc()) {
        $kas_masuk_list[] = $row_masuk;
        $total_kas_masuk += $row_masuk['harga'];
    }

    // Hitung balance
    $balance = $total_kas_masuk - $total_kas_keluar;

    // Tentukan keterangan Laba atau Rugi
    if ($balance >= 0) {
        $keterangan = "Laba";
    } else {
        $keterangan = "Rugi";
    }
}
?>


<h1 class="h3 mb-3">Laporan Laba Rugi</h1>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header"></div>
            <div class="card-body">
                <form action="" method="POST">
                    <div class="form-group">
                        <label for="tanggalAwal">Tanggal Awal:</label>
                        <input type="date" class="form-control mb-3" id="tanggalAwal" name="tanggalAwal" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggalAkhir">Tanggal Akhir:</label>
                        <input type="date" class="form-control mb-3" id="tanggalAkhir" name="tanggalAkhir" required>
                    </div>
                    <button type="submit" name="submit" class="btn btn-primary mt-4">Buat Laporan</button>
                </form>
            </div>
            <div class="card-body">
                <?php if (isset($_POST['submit'])) : ?>
                    <?php echo "<h3 class='h3'>Laporan Laba Rugi dari tanggal <strong>$tanggalAwal - $tanggalAkhir:</strong></h3>"; ?>

                    <h4>Pemasukan:</h4>
                    <table class="table table-bordered">
                        <!-- <thead>
                            <tr>
                                <th>Keterangan</th>
                                <th>Harga</th>
                            </tr>
                        </thead> -->
                        <tbody>
                            <!-- <?php foreach ($kas_masuk_list as $item) : ?>
                                <tr>
                                    <td><?php echo $item['nama']; ?></td>
                                    <td>Rp <?php echo number_format($item['harga'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?> -->
                            <tr>
                                <th>Total Pendapatan</th>
                                <td>Rp <?php echo number_format($total_kas_masuk, 2); ?></td>
                            </tr>
                        </tbody>
                    </table>

                    <h4>Pengeluaran:</h4>
                    <table class="table table-bordered">
                        <!-- <thead>
                            <tr>
                                <th>Keterangan</th>
                                <th>Harga</th>
                            </tr>
                        </thead> -->
                        <tbody>
                            <?php foreach ($kas_keluar_list as $item) : ?>
                                <tr>
                                    <td><?php echo $item['nama']; ?></td>
                                    <td>Rp <?php echo number_format($item['harga'], 2); ?></td>
                                </tr>
                            <?php endforeach; ?>
                            <!-- <tr>
                                <th>Total Biaya Pengeluaran</th>
                                <td>Rp <?php echo number_format($total_kas_keluar, 2); ?></td>
                            </tr> -->
                        </tbody>
                    </table>

                    <!-- <h4>Laba/Rugi</h4> -->
                    <table class="table table-bordered">
                        <tr>
                            <th>Laba/Rugi</th>
                            <td>Rp <?php echo number_format($balance, 2); ?></td>
                        </tr>
                        <!-- <tr>
                            <th>Keterangan</th>
                            <td><?php echo "<b>" . $keterangan . "</b>";  ?></td>
                        </tr> -->
                    </table>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>


<?php include '../lib/komponen/wrap-bottom.php'; ?>