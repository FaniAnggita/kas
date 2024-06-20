<?php $menu = 'laba_rugi';
include '../lib/komponen/wrap-top.php'; ?>
<?php
$tanggalAwal = '';
$tanggalAkhir = '';
$balance = 0;
$total_pengeluaran = 0;
$total_laba_rugi = 0;
$keterangan = "";

// Cek apakah form telah disubmit
if (isset($_POST['submit'])) {
    // Ambil nilai dari form
    $totalGaji = $_POST['totalGaji'];
    $totalListrikAir = $_POST['totalListrikAir'];
    $totalOngkosBarang = $_POST['totalOngkosBarang'];
    $tanggalAwal = $_POST['tanggalAwal'];
    $tanggalAkhir = $_POST['tanggalAkhir'];

    $total_pengeluaran = $totalGaji +  $totalListrikAir +  $totalOngkosBarang;
    // Fetch total kas masuk
    $sql_masuk = "SELECT SUM(p.harga * p.quantity) AS total_masuk 
    FROM kas k
    LEFT JOIN penjualan p ON k.id_penjualan = p.id_penjualan
    WHERE k.jenis_kas = 'masuk'";
    $result_masuk = $conn->query($sql_masuk);
    $row_masuk = $result_masuk->fetch_assoc();
    $total_masuk = $row_masuk['total_masuk'] ?? 0;

    // Fetch total kas keluar from pembelian and biaya
    $sql_keluar_pembelian = "SELECT SUM(pe.harga * pe.quantity) AS total_keluar_pembelian
           FROM kas k
           LEFT JOIN pembelian pe ON k.id_pembelian = pe.id_pembelian
           WHERE k.jenis_kas = 'keluar' AND k.id_pembelian IS NOT NULL AND
           k.tgl_tranksasi BETWEEN '$tanggalAwal' AND '$tanggalAkhir' OR k.tgl_tranksasi BETWEEN '$tanggalAwal' AND '$tanggalAkhir'";
    $result_keluar_pembelian = $conn->query($sql_keluar_pembelian);
    $row_keluar_pembelian = $result_keluar_pembelian->fetch_assoc();
    $total_keluar_pembelian = $row_keluar_pembelian['total_keluar_pembelian'] ?? 0;

    $sql_keluar_biaya = "SELECT SUM(b.nominal_biaya * b.quantity) AS total_keluar_biaya
       FROM kas k
       LEFT JOIN biaya b ON k.id_biaya = b.id_biaya
       WHERE k.jenis_kas = 'keluar' AND k.id_biaya IS NOT NULL AND
           k.tgl_tranksasi BETWEEN '$tanggalAwal' AND '$tanggalAkhir' OR k.tgl_tranksasi BETWEEN '$tanggalAwal' AND '$tanggalAkhir'";
    $result_keluar_biaya = $conn->query($sql_keluar_biaya);
    $row_keluar_biaya = $result_keluar_biaya->fetch_assoc();
    $total_keluar_biaya = $row_keluar_biaya['total_keluar_biaya'] ?? 0;

    $total_keluar = $total_keluar_pembelian + $total_keluar_biaya;

    // Calculate the balance
    $balance = $total_masuk - $total_keluar;

    // Hitung total laba/rugi
    $total_laba_rugi =  $balance - $total_pengeluaran;

    // Tentukan keterangan Laba atau Rugi
    if ($total_laba_rugi >= 0) {
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
            <div class="card-header">

            </div>
            <div class="card-body">

                <form action="" method="POST">
                    <div class="form-group">
                        <label for="tanggalAwal">Tanggal Awal:</label>
                        <input type="date" class="form-control" id="tanggalAwal" name="tanggalAwal" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggalAkhir">Tanggal Akhir:</label>
                        <input type="date" class="form-control" id="tanggalAkhir" name="tanggalAkhir" required>
                    </div>
                    <div class="form-group">
                        <label for="totalGaji">Total Gaji:</label>
                        <input type="text" class="form-control" id="totalGaji" name="totalGaji" required>
                    </div>
                    <div class="form-group">
                        <label for="totalListrikAir">Total Listrik & Air:</label>
                        <input type="text" class="form-control" id="totalListrikAir" name="totalListrikAir" required>
                    </div>
                    <div class="form-group">
                        <label for="totalOngkosBarang">Total Ongkos Barang:</label>
                        <input type="text" class="form-control" id="totalOngkosBarang" name="totalOngkosBarang" required>
                    </div>

                    <button type="submit" name="submit" class="btn btn-primary">Buat Laporan</button>
                </form>



            </div>
            <div class="card-body">
                <?php if (isset($_POST['submit'])) : ?>
                    <?php echo "<h3 class='h3'>Laporan Laba Rugi dari tanggal <strong> $tanggalAwal - $tanggalAkhir:</strong></h3>"; ?>
                    <table class="table table-bordered">
                        <tr>
                            <th>Total Pendapatan</th>
                            <td>Rp <?php echo number_format($balance, 2); ?></td>
                        </tr>
                        <tr>
                            <th>Total Biaya Pengeluaran</th>
                            <td>Rp <?php echo number_format($total_pengeluaran, 2); ?></td>
                        </tr>
                        <tr>
                            <th>Total Laba/Rugi</th>
                            <td>Rp <?php echo number_format($total_laba_rugi, 2); ?></td>
                        </tr>
                        <tr>
                            <th>Keterangan</th>
                            <td><?php echo "<b>" . $keterangan . "</b>";  ?></td>
                        </tr>
                    </table>
                <?php endif; ?>
            </div>

        </div>
    </div>
</div>


<?php include '../lib/komponen/wrap-bottom.php'; ?>