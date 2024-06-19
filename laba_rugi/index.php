<?php $menu = 'laba_rugi';
include '../lib/komponen/wrap-top.php'; ?>
<?php
$tanggalAwal = '';
$tanggalAkhir = '';
$total_pendapatan = 0;
$total_biaya = 0;
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

    // Query untuk menghitung total pendapatan dari tabel penjualan berdasarkan rentang tanggal
    $sql_total_pendapatan = "SELECT SUM(p.harga * p.quantity) AS total_pendapatan 
                             FROM kas k
                             LEFT JOIN penjualan p ON k.id_penjualan = p.id_penjualan
                             WHERE k.jenis_kas = 'masuk' AND p.tgl_jual BETWEEN '$tanggalAwal' AND '$tanggalAkhir'";
    $result_total_pendapatan = $conn->query($sql_total_pendapatan);
    $row_total_pendapatan = $result_total_pendapatan->fetch_assoc();
    $total_pendapatan = $row_total_pendapatan['total_pendapatan'] ?? 0;

    // Query untuk menghitung total biaya (total keluar)
    $sql_total_biaya = "SELECT SUM(COALESCE(pe.harga * pe.quantity, 0) + COALESCE(b.nominal_biaya * b.quantity, 0)) AS total_biaya
                        FROM kas k
                        LEFT JOIN pembelian pe ON k.id_pembelian = pe.id_pembelian AND k.jenis_kas = 'keluar'
                        LEFT JOIN biaya b ON k.id_biaya = b.id_biaya AND k.jenis_kas = 'keluar'
                        WHERE (pe.tanggal_beli BETWEEN '$tanggalAwal' AND '$tanggalAkhir' OR b.tgl_biaya BETWEEN '$tanggalAwal' AND '$tanggalAkhir')";
    $result_total_biaya = $conn->query($sql_total_biaya);
    $row_total_biaya = $result_total_biaya->fetch_assoc();
    $total_biaya = $row_total_biaya['total_biaya'] ?? 0;

    // Hitung total laba/rugi
    $total_laba_rugi = $total_pendapatan - $total_biaya;

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
                    <?php echo "<h3 class='h3'>Total Pendapatan dari tanggal <strong> $tanggalAwal - $tanggalAkhir:</strong></h3>"; ?>
                    <table class="table table-bordered">
                        <tr>
                            <th>Total Pendapatan</th>
                            <td>Rp <?php echo number_format($total_pendapatan, 2); ?></td>
                        </tr>
                        <tr>
                            <th>Total Biaya</th>
                            <td>Rp <?php echo number_format($total_biaya, 2); ?></td>
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