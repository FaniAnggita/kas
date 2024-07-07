<?php $menu = 'barang';
include '../lib/komponen/wrap-top.php';

// Handle form submission for adding pembelian
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_barang'])) {
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $stok = $_POST['stok'];

    // Insert into pembelian table
    $sql = "INSERT INTO barang (kode_barang, nama_barang, stok) VALUES ('$kode_barang', '$nama_barang', '$stok')";
    if ($conn->query($sql) === TRUE) {
        echo "<script>Swal.fire({
                title: 'Data Berhasil Ditambahkan!',
                icon: 'success',
                confirmButtonText: 'Tutup'
                })</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

// Fetch all pembelian
$barang = $conn->query("SELECT * FROM barang");

?>


<h1 class="h3 mb-3">Barang</h1>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tabel Barang</h5>
            </div>
            <div class="card-body">
                <?php if ($_SESSION['jabatan'] != 'owner') { ?>
                    <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
                        Tambah Barang
                    </button>
                <?php } ?>
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
                    <table class="table table-striped table-bordered mt-4" id="table2">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Tanggal</th>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                                <?php if ($_SESSION['jabatan'] != 'owner') { ?>
                                    <th>Aksi</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $barang->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo $row['kode_barang']; ?></td>
                                    <td><?php echo $row['created_at']; ?></td>
                                    <td><?php echo $row['nama_barang']; ?></td>
                                    <td><?php echo $row['stok']; ?></td>
                                    <?php if ($_SESSION['jabatan'] != 'owner') { ?>
                                        <td>
                                            <a href="update_barang.php?id=<?php echo $row['kode_barang']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                            <a href="delete_barang.php?id=<?php echo $row['kode_barang']; ?>" class="btn btn-danger btn-sm delete-btn" data-url="delete_barang.php?id=<?php echo $row['kode_barang']; ?>">Delete</a>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>


                    </table>
                </div>


                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Form Tambah Barang</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="post" action="" class="form-horizontal">
                                    <div class="form-group">
                                        <label for="kode_barang" class="col-form-label">Kode Barang</label>
                                        <input type="text" name="kode_barang" id="kode_barang" class="form-control" readonly>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_barang" class="col-form-label">Nama Barang</label>
                                        <input type="text" name="nama_barang" id="nama_barang" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="stok" class="col-form-label">Stok</label>
                                        <input type="int" name="stok" id="stok" class="form-control" required>
                                    </div>
                                    <div class="form-group">
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <input type="submit" name="add_barang" class="btn btn-primary" value="Tambah">
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>



            </div>
        </div>
    </div>
</div>

<script>
    // Function to get formatted date string
    function getFormattedDate() {
        const date = new Date();
        const monthNames = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];

        const month = monthNames[date.getMonth()];
        const day = String(date.getDate()).padStart(2, '0');
        const year = String(date.getFullYear()).slice(-2);
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');

        return `${month}${day}${year}${hours}${minutes}${seconds}`;
    }

    // Set default value to the input field
    document.getElementById('kode_barang').value = getFormattedDate();
</script>


<?php include '../lib/komponen/wrap-bottom.php'; ?>