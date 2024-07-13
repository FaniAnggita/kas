<?php $menu = 'barang';
include '../lib/komponen/wrap-top.php'; ?>
<?php
$id = $_GET['id'];

// Fetch barang data
$barang = $conn->query("SELECT * FROM barang WHERE kode_barang = '$id'")->fetch_assoc();

// Handle form submission for updating barang
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_barang'])) {

    $nama_barang = $_POST['nama_barang'];
    $stok = $_POST['stok'];


    // Update barang table
    $sql = "UPDATE barang SET nama_barang = '$nama_barang', stok = '$stok' WHERE kode_barang = '$id'";
    if ($conn->query($sql) === TRUE) {

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
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
?>



<h1 class="h3 mb-3 text-white">Edit Barang</h1>
<div class="row">
    <div class="col-12">
        <div class="card">

            <div class="card-body">
                <form method="post" action="" class="form-horizontal">

                    <div class="mb-3">
                        <label for="nama_barang" class="form-label">Nama Barang:</label>
                        <input type="text" name="nama_barang" id="nama_barang" value="<?php echo $barang['nama_barang']; ?>" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="stok" class="form-label">Stok:</label>
                        <input type="int" name="stok" id="stok" value="<?php echo $barang['stok']; ?>" class="form-control" required>
                    </div>
                    <div class="d-flex justify-content-between">
                        <button type="submit" name="update_barang" class="btn btn-primary">Update</button>
                        <button type="button" class="btn btn-secondary" onclick="window.location.href='index.php';">Batal</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include '../lib/komponen/wrap-bottom.php'; ?>