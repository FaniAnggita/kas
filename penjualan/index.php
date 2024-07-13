<?php
$menu = 'penjualan';
include '../lib/komponen/wrap-top.php';


// Handle form submission for adding penjualan
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_penjualan'])) {
    $kode_barang = $_POST['kode_barang'];
    $kode_jual = $_POST['kode_jual'];
    $tgl_jual = $_POST['tgl_jual'];
    $quantities = $_POST['quantity'];
    $hargas = $_POST['harga'];

    foreach ($quantities as $index => $quantity) {
        $harga = $hargas[$index];
        $sql_cek_stok = $conn->query("SELECT stok FROM barang WHERE kode_barang = '$kode_barang'");
        $stok = $sql_cek_stok->fetch_assoc()['stok'];
        if ($stok < $quantity) {
            echo "<script>Swal.fire({
                title: 'Stok barang tidak cukup!',
                icon: 'error',
                confirmButtonText: 'Tutup'
            }) </script>";
        } else {
            // Insert into penjualan table
            $sql = "INSERT INTO penjualan (kode_barang, kode_jual, tgl_jual, harga, quantity) VALUES ('$kode_barang', '$kode_jual', '$tgl_jual', '$harga', '$quantity')";
            if ($conn->query($sql) === TRUE) {
                // Get the last inserted id
                $last_id = $conn->insert_id;

                // Update barang table
                $sql_barang = "UPDATE barang SET stok = stok - $quantity WHERE kode_barang = '$kode_barang'";
                if ($conn->query($sql_barang) === TRUE) {
                    // Insert into kas table
                    $keterangan_transaksi = $kode_barang;
                    $jenis_kas = 'masuk';
                    $tgl_transaksi = $tgl_jual;

                    $sql_kas = "INSERT INTO kas (id_penjualan, keterangan_transaksi, jenis_kas, tgl_tranksasi) VALUES ('$last_id', '$keterangan_transaksi', '$jenis_kas', '$tgl_transaksi')";
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
}

// Fetch all penjualan

$barang = $conn->query("SELECT * FROM barang");
$barang2 = $conn->query("SELECT * FROM barang");



$penjualan = $conn->query("SELECT * FROM penjualan JOIN barang USING(kode_barang)");
if (isset($_POST['btn-pilih-barang'])) {
    $kode_barang = $_POST['pilih-barang'];
    if ($kode_barang == 'semua') {
        $penjualan = $conn->query("SELECT * FROM penjualan JOIN barang USING(kode_barang)");
    } else {
        $penjualan = $conn->query("SELECT * FROM penjualan JOIN barang USING(kode_barang) WHERE kode_barang = '$kode_barang'");
    }
}
?>

<h1 class="h3 mb-3 text-white">Penjualan</h1>
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Tabel Penjualan</h5>
            </div>
            <div class="card-body">

                <div class="row d-flex align-items-center">
                    <div class="col-md-6">
                        <?php if ($_SESSION['jabatan'] != 'owner') { ?>
                            <button type="button" class="btn btn-primary mb-4" data-bs-toggle="modal" data-bs-target="#exampleModal">
                                Tambah Penjualan
                            </button>
                        <?php  } ?>
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
                                        $barang2->data_seek(0);
                                        while ($row = $barang2->fetch_assoc()) : ?>
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
                                <th>Tanggal Jual</th>
                                <th>Kode Penjualan</th>
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
                            <?php while ($row = $penjualan->fetch_assoc()) : ?>
                                <tr>
                                    <td><?php echo $row['id_penjualan']; ?></td>
                                    <td><?php echo $row['tgl_jual']; ?></td>
                                    <td><?php echo $row['kode_jual']; ?></td>
                                    <td><?php echo $row['kode_barang']; ?></td>
                                    <td><?php echo $row['nama_barang']; ?></td>
                                    <td><?php echo $row['quantity']; ?></td>
                                    <td class="text-end"><?php echo number_format($row['harga'], 2); ?></td>
                                    <td class="text-end"><?php echo number_format($row['quantity'] * $row['harga'], 2); ?></td>

                                    <?php if ($_SESSION['jabatan'] != 'owner') { ?>
                                        <td class="text-end">
                                            <a href="update_penjualan.php?id=<?php echo $row['id_penjualan']; ?>" class="btn btn-primary btn-sm">Edit</a>
                                            <a href="delete_penjualan.php?id=<?php echo $row['id_penjualan']; ?>" class="btn btn-danger btn-sm delete-btn" data-url="delete_penjualan.php?id=<?php echo $row['id_penjualan']; ?>">Delete</a>
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
                                <h1 class="modal-title fs-5" id="exampleModalLabel">Form Tambah Penjualan</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <div class="container">
                                    <form method="post" action="" class="form-horizontal">
                                        <div class="mb-3">
                                            <label for="kode_jual" class="form-label">Kode Penjualan:</label>
                                            <input type="text" name="kode_jual" id="kode_jual" class="form-control" required>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label for="kode_barang" class="col-form-label">Kode Barang:</label>
                                            <select name="kode_barang" id="kode_barang" class="form-control" required>
                                                <?php while ($row = $barang->fetch_assoc()) : ?>
                                                    <option value="<?php echo $row['kode_barang']; ?>"><?php echo $row['kode_barang'] . " - " . $row['nama_barang']; ?></option>
                                                <?php endwhile; ?>
                                            </select>
                                        </div>
                                        <div class="mb-3">
                                            <label for="tgl_jual" class="form-label">Tanggal Jual:</label>
                                            <input type="date" name="tgl_jual" id="tgl_jual" class="form-control" required>
                                        </div>
                                        <hr>
                                        <div id="dynamic-inputs">
                                            <div class="mb-3 row">
                                                <div class="col-md-4">
                                                    <label for="nama_barang" class="form-label">Nama Barang:</label>
                                                    <input type="text" name="nama_barang[]" class="form-control nama_barang" readonly>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="quantity" class="form-label">Quantity:</label>
                                                    <input type="number" name="quantity[]" class="form-control" min="1" value="1" required>
                                                </div>
                                                <div class="col-md-3">
                                                    <label for="harga" class="form-label">Harga:</label>
                                                    <input type="number" name="harga[]" class="form-control" required>
                                                </div>
                                                <div class="col-md-2 d-flex align-items-end">
                                                    <button type="button" class="btn btn-danger btn-sm remove-input">Delete</button>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="button" class="btn btn-sm btn-warning" id="add-input"> <i class="align-middle" data-feather="plus-square"></i></button>
                                        <hr>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" name="add_penjualan" class="btn btn-primary">Simpan</button>
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
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        function updateNamaBarang() {
            var kode_barang = $('#kode_barang').val();
            var nama_barang = $('#kode_barang option:selected').text().split(" - ")[1];
            $('.nama_barang').val(nama_barang);
        }

        $('#kode_barang').change(function() {
            updateNamaBarang();
        });

        updateNamaBarang();

        $('#add-input').click(function() {
            var newInput = `
                <div class="mb-3 row">
                    <div class="col-md-4">
                      
                        <input type="text" name="nama_barang[]" class="form-control nama_barang" readonly>
                    </div>
                    <div class="col-md-3">
                       
                        <input type="number" name="quantity[]" class="form-control" min="1" value="1" required>
                    </div>
                    <div class="col-md-3">
                       
                        <input type="number" name="harga[]" class="form-control" required>
                    </div>
                    <div class="col-md-2 d-flex align-items-end">
                        <button type="button" class="btn btn-danger btn-sm remove-input">Delete</button>
                    </div>
                </div>`;
            $('#dynamic-inputs').append(newInput);
            updateNamaBarang();
        });

        $(document).on('click', '.remove-input', function() {
            $(this).closest('.row').remove();
        });
    });
</script>

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

        return `JL-${month}${day}${year}${hours}${minutes}${seconds}`;
    }

    // Set default value to the input field
    document.getElementById('kode_jual').value = getFormattedDate();
</script>


<?php include '../lib/komponen/wrap-bottom.php'; ?>