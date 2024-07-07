<?php $menu = 'biaya';
include '../lib/komponen/wrap-top.php'; ?>
<?php

$id = $_GET['id'];

// Fetch biaya data
$biaya = $conn->query("SELECT * FROM biaya WHERE id_biaya = $id")->fetch_assoc();

// Handle form submission for updating biaya
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_biaya'])) {
    $keterangan_biaya = $_POST['keterangan_biaya'];
    $tgl_biaya = $_POST['tgl_biaya'];
    $nominal_biaya = $_POST['nominal_biaya'];

    // Update biaya table
    $sql = "UPDATE biaya SET keterangan_biaya = '$keterangan_biaya', tgl_biaya = '$tgl_biaya', nominal_biaya = '$nominal_biaya' WHERE id_biaya = $id";
    if ($conn->query($sql) === TRUE) {
        // Update kas table
        $keterangan_transaksi = "Biaya: $keterangan_biaya";
        $jenis_kas = 'keluar';
        $tgl_transaksi = $tgl_biaya;

        $sql_kas = "UPDATE kas SET keterangan_transaksi = '$keterangan_transaksi', jenis_kas = '$jenis_kas', tgl_tranksasi = '$tgl_transaksi' WHERE id_biaya = $id";
        if ($conn->query($sql_kas) === TRUE) {
            echo "<script>
            Swal.fire({
                title: 'Data Berhasil Diperbarui!',
                icon: 'success',
                confirmButtonText: 'Tutup'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = 'index.php';
                }
            });
        </script>";
        } else {
            echo "Error: " . $sql_kas . "<br>" . $conn->error;
        }
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>



<h1 class="h3 mb-3">Edit Pembiayaan</h1>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title mb-4">Form Update Biaya</h2>
                <form method="post" action="" class="form-horizontal">
                    <div class="mb-3">
                        <label for="keterangan_biaya" class="form-label">Keterangan Biaya:</label>
                        <input type="text" name="keterangan_biaya" id="keterangan_biaya" value="<?php echo $biaya['keterangan_biaya']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_biaya" class="form-label">Tanggal Biaya:</label>
                        <input type="date" name="tgl_biaya" id="tgl_biaya" value="<?php echo $biaya['tgl_biaya']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="nominal_biaya" class="form-label">Nominal Biaya:</label>
                        <input type="number" name="nominal_biaya" id="nominal_biaya" value="<?php echo $biaya['nominal_biaya']; ?>" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" name="update_biaya" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php';">Batal</button>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>

<?php include '../lib/komponen/wrap-bottom.php'; ?>