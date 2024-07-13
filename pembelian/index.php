<?php
$menu = 'pembelian';
include '../lib/komponen/wrap-top.php';

// Handle form submission for adding pembelian
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_pembelian'])) {
    $id_supplier = $_POST['id_supplier'];
    $kode_beli = $_POST['kode_beli'];
    $tanggal_beli = $_POST['tanggal_beli'];
    $kode_barang = $_POST['kode_barang'];
    $harga = $_POST['harga'];
    $quantity = $_POST['quantity'];

    // Loop through each item
    foreach ($kode_barang as $index => $kode) {
        $qty = $quantity[$index];
        $price = $harga[$index];

        // Insert into pembelian table
        $sql = "INSERT INTO pembelian (id_supplier, kode_beli, kode_barang, tanggal_beli, harga, quantity) VALUES ('$id_supplier', '$kode_beli', '$kode', '$tanggal_beli', '$price', '$qty')";
        if ($conn->query($sql) === TRUE) {
            // Get the last inserted id
            $last_id = $conn->insert_id;

            // Update barang table
            $sql_barang = "UPDATE barang SET stok = stok + $qty WHERE kode_barang = '$kode'";
            if ($conn->query($sql_barang) === TRUE) {
                // Insert into kas table
                $keterangan_transaksi = $kode;
                $jenis_kas = 'keluar';
                $tgl_transaksi = $tanggal_beli;

                $sql_kas = "INSERT INTO kas (id_pembelian, keterangan_transaksi, jenis_kas, tgl_tranksasi) VALUES ('$last_id', '$keterangan_transaksi', '$jenis_kas', '$tgl_transaksi')";
                if ($conn->query($sql_kas) === TRUE) {
                    echo "<script>Swal.fire({
                        title: 'Data Berhasil Ditambahkan!',
                        icon: 'success',
                        confirmButtonText: 'Tutup'
                    })</script>";
                } else {
                    echo "Error: " . $sql_kas . "<br>" . $conn->error;
                }
            } else {
                echo "Error: " . $sql_barang . "<br>" . $conn->error;
            }
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

// Fetch all pembelian
$suppliers = $conn->query("SELECT * FROM supplier");
$barang = $conn->query("SELECT * FROM barang");

$pembelian = $conn->query("SELECT * FROM pembelian JOIN supplier USING(id_supplier) JOIN barang USING(kode_barang)");

if (isset($_POST['btn-pilih-barang'])) {
    $kode_barang = $_POST['pilih-barang'];
    if ($kode_barang == 'semua') {
        $pembelian = $conn->query("SELECT * FROM pembelian JOIN supplier USING(id_supplier) JOIN barang USING(kode_barang)");
    } else {
        $pembelian = $conn->query("SELECT * FROM pembelian JOIN supplier USING(id_supplier) JOIN barang USING(kode_barang) WHERE kode_barang = '$kode_barang'");
    }
}
?>

<h1 class="h3 mb-3 text-white">Pembelian</h1>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tabel Pembelian</h5>
            </div>
            <div class="card-body">
                <div class="row d-flex align-items-center">
                    <div class="col-md-6">
                        <?php if ($_SESSION['jabatan'] != 'owner') { ?>
                            <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Tambah Pembelian
                            </button>
                        <?php } ?>
                    </div>
                    <div class="col-md-6">
                        <form class="row mb-3 d-flex align-items-end" method="post">
                            <div class="col-md-8">
                                <div class="form-inline">
                                    <label class="mr-2" for="pilih-barang">Pilih Barang:</label>
                                    <select name="pilih-barang" id="pilih-barang" class="form-control" required>
                                        <option value="semua">Semua Barang</option>
                                        <?php
                                        // Reset the barang query result pointer
                                        $barang->data_seek(0);
                                        while ($row = $barang->fetch_assoc()) : ?>
                                            <option value="<?php echo $row['kode_barang']; ?>" <?php echo isset($_POST['pilih-barang']) && $row['kode_barang'] == $_POST['pilih-barang'] ? 'selected' : ''; ?>>
                                                <?php echo $row['kode_barang'] . " - " . $row['nama_barang']; ?>
                                            </option>
                                        <?php endwhile; ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4 mt-2">
                                <button type="submit" name="btn-pilih-barang" class="btn btn-success">Pilih</button>
                            </div>
                        </form>
                    </div>
                </div>
                <hr>
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
                                <th>ID</th>
                                <th>Tanggal Beli</th>
                                <th>Kode Pembelian</th>
                                <th>Supplier</th>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Quantity</th>
                                <th>Harga</th>
                                <th>Total</th>
                                <?php if ($_SESSION['jabatan'] != 'owner') { ?>
                                    <th>Aksi</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = $pembelian->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo $row['id_pembelian']; ?></td>
                                    <td><?php echo $row['tanggal_beli']; ?></td>
                                    <td><?php echo $row['kode_beli']; ?></td>
                                    <td><?php echo $row['nama_supplier']; ?></td>
                                    <td><?php echo $row['kode_barang']; ?></td>
                                    <td><?php echo $row['nama_barang']; ?></td>
                                    <td><?php echo $row['quantity']; ?></td>
                                    <td class="text-end"><?php echo number_format($row['harga'], 2); ?></td>
                                    <td class="text-end"><?php echo number_format($row['quantity'] * $row['harga'], 2); ?></td>
                                    <?php if ($_SESSION['jabatan'] != 'owner') { ?>
                                        <td>
                                            <a href="update_pembelian.php?id=<?php echo $row['id_pembelian']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                            <a href="delete_pembelian.php?id=<?php echo $row['id_pembelian']; ?>" class="btn btn-danger btn-sm delete-btn" data-url="delete_pembelian.php?id=<?php echo $row['id_pembelian']; ?>">Delete</a>
                                        </td>
                                    <?php } ?>
                                </tr>
                            <?php endwhile; ?>
                        </tbody>
                    </table>
                </div>

                <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah Pembelian</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <form method="post">
                                    <div class="form-group mb-3">
                                        <label for="kode_beli">Kode Pembelian</label>
                                        <input type="text" class="form-control" id="kode_beli" name="kode_beli" readonly>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="tanggal_beli">Tanggal Pembelian</label>
                                        <input type="date" class="form-control" id="tanggal_beli" name="tanggal_beli" required>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label for="id_supplier">Supplier</label>
                                        <select class="form-control" id="id_supplier" name="id_supplier" required>
                                            <option value="">Pilih Supplier</option>
                                            <?php while ($row = $suppliers->fetch_assoc()) : ?>
                                                <option value="<?php echo $row['id_supplier']; ?>"><?php echo $row['nama_supplier']; ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <hr>
                                    <div class="mb-3" id="dynamic-inputs">
                                        <div class="mb-3 row">
                                            <div class="col-md-6">
                                                <label for="kode_barang">Kode Barang</label>
                                                <select name="kode_barang[]" class="form-control kode_barang" required>
                                                    <?php
                                                    // Reset the barang query result pointer
                                                    $barang->data_seek(0);
                                                    while ($row = $barang->fetch_assoc()) : ?>
                                                        <option value="<?php echo $row['kode_barang']; ?>"><?php echo $row['kode_barang'] . " - " . $row['nama_barang'] . " (" . $row['stok'] . ")"; ?></option>
                                                    <?php endwhile; ?>
                                                </select>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="quantity">Quantity</label>
                                                <input type="number" name="quantity[]" class="form-control" min="1" value="1" required>
                                            </div>
                                            <div class="col-md-2">
                                                <label for="harga">Harga</label>
                                                <input type="number" name="harga[]" class="form-control" required>
                                            </div>
                                            <div class="col-md-2 d-flex align-items-end">
                                                <button type="button" class="btn btn-danger btn-sm remove-input">Delete</button>
                                            </div>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-warning btn-sm" id="add-input">Tambah Barang</button>
                                    <div class="modal-footer mt-4">
                                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                                        <button type="submit" name="add_pembelian" class="btn btn-primary">Simpan</button>
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



<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#add-input').click(function() {
            var newInput = `
            <div class="mb-3 row">
                <div class="col-md-6">
                    <select name="kode_barang[]" class="form-control kode_barang" required>
                        <?php
                        // Reset barang query
                        $barang->data_seek(0);
                        while ($row = $barang->fetch_assoc()) : ?>
                            <option value="<?php echo $row['kode_barang']; ?>"><?php echo $row['kode_barang'] . " - " . $row['nama_barang'] . " (" . $row['stok'] . ")"; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>
                <div class="col-md-2">
                    <input type="number" name="quantity[]" class="form-control" min="1" value="1" required>
                </div>
                <div class="col-md-2">
                    <input type="number" name="harga[]" class="form-control" required>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="button" class="btn btn-danger btn-sm remove-input">Hapus</button>
                </div>
            </div>`;
            $('#dynamic-inputs').append(newInput);

            // If you're using any plugins, reinitialize them here
            // For example, if using Select2:
            // $('.kode_barang').select2();

            // Reinitialize any other necessary plugins
        });

        $(document).on('click', '.remove-input', function() {
            $(this).closest('.row').remove();

            // Reinitialize or update plugins if necessary
        });

        // Ensure default kode_beli is set
        document.getElementById('kode_beli').value = getFormattedDate();
    });

    function getFormattedDate() {
        const date = new Date();
        const monthNames = ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];

        const month = monthNames[date.getMonth()];
        const day = String(date.getDate()).padStart(2, '0');
        const year = String(date.getFullYear()).slice(-2);
        const hours = String(date.getHours()).padStart(2, '0');
        const minutes = String(date.getMinutes()).padStart(2, '0');
        const seconds = String(date.getSeconds()).padStart(2, '0');

        return `BL-${month}${day}${year}${hours}${minutes}${seconds}`;
    }
</script>

<?php
include '../lib/komponen/wrap-bottom.php';
?>