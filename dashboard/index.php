<?php $menu = 'dashboard';
include '../lib/komponen/wrap-top.php';
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
                         WHERE k.jenis_kas = 'keluar' AND k.id_pembelian IS NOT NULL";
$result_keluar_pembelian = $conn->query($sql_keluar_pembelian);
$row_keluar_pembelian = $result_keluar_pembelian->fetch_assoc();
$total_keluar_pembelian = $row_keluar_pembelian['total_keluar_pembelian'] ?? 0;

$sql_keluar_biaya = "SELECT SUM(b.nominal_biaya * b.quantity) AS total_keluar_biaya
                     FROM kas k
                     LEFT JOIN biaya b ON k.id_biaya = b.id_biaya
                     WHERE k.jenis_kas = 'keluar' AND k.id_biaya IS NOT NULL";
$result_keluar_biaya = $conn->query($sql_keluar_biaya);
$row_keluar_biaya = $result_keluar_biaya->fetch_assoc();
$total_keluar_biaya = $row_keluar_biaya['total_keluar_biaya'] ?? 0;

$total_keluar = $total_keluar_pembelian + $total_keluar_biaya;

// Calculate the balance
$balance = $total_masuk - $total_keluar;

// Fetch total number of transactions for each type
$sql_total_transactions = "SELECT COUNT(*) AS total_transactions FROM kas";
$result_total_transactions = $conn->query($sql_total_transactions);
$row_total_transactions = $result_total_transactions->fetch_assoc();
$total_transactions = $row_total_transactions['total_transactions'] ?? 0;

// Fetch total number of kas masuk
$sql_total_masuk = "SELECT COUNT(*) AS total_masuk_transactions FROM kas WHERE jenis_kas = 'masuk'";
$result_total_masuk = $conn->query($sql_total_masuk);
$row_total_masuk = $result_total_masuk->fetch_assoc();
$total_masuk_transactions = $row_total_masuk['total_masuk_transactions'] ?? 0;

// Fetch total number of kas keluar
$sql_total_keluar = "SELECT COUNT(*) AS total_keluar_transactions FROM kas WHERE jenis_kas = 'keluar'";
$result_total_keluar = $conn->query($sql_total_keluar);
$row_total_keluar = $result_total_keluar->fetch_assoc();
$total_keluar_transactions = $row_total_keluar['total_keluar_transactions'] ?? 0;
?>

<h1 class="h3 mb-3">Dashboard</h1>

<div class="row">
	<div class="col-12 d-flex">
		<div class="w-100">
			<div class="row">
				<div class="col-sm-6">
					<!-- kas masuk -->
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col mt-0">
									<h5 class="card-title">Total Kas Masuk</h5>
								</div>

								<div class="col-auto">
									<div class="stat text-primary">
										<i class="align-middle" data-feather="log-in"></i>
									</div>
								</div>
							</div>
							<h1 class="mt-1 mb-3"><?php echo number_format($total_masuk, 2); ?></h1>

						</div>
					</div>

					<!-- Jumlah Kas Masuk -->
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col mt-0">
									<h5 class="card-title">Jumlah Kas Masuk</h5>
								</div>

								<div class="col-auto">
									<div class="stat text-primary">
										<i class="align-middle" data-feather="arrow-down-circle"></i>
									</div>
								</div>
							</div>
							<h1 class="mt-1 mb-3"><?php echo $total_masuk_transactions; ?></h1>

						</div>
					</div>

					<!-- jumlah transaksi -->
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col mt-0">
									<h5 class="card-title">Jumlah Transaksi</h5>
								</div>

								<div class="col-auto">
									<div class="stat text-primary">
										<i class="align-middle" data-feather="shopping-bag"></i>
									</div>
								</div>
							</div>
							<h1 class="mt-1 mb-3"><?php echo $total_transactions; ?></h1>

						</div>
					</div>
				</div>
				<div class="col-sm-6">
					<!-- kas keluar -->
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col mt-0">
									<h5 class="card-title">Total Kas Keluar</h5>
								</div>

								<div class="col-auto">
									<div class="stat text-primary">
										<i class="align-middle" data-feather="log-out"></i>
									</div>
								</div>
							</div>
							<h1 class="mt-1 mb-3"><?php echo number_format($total_keluar, 2); ?></h1>

						</div>
					</div>
					<!-- Jumlah Kas Keluar -->
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col mt-0">
									<h5 class="card-title">Jumlah Kas Keluar</h5>
								</div>

								<div class="col-auto">
									<div class="stat text-primary">
										<i class="align-middle" data-feather="arrow-up-circle"></i>
									</div>
								</div>
							</div>
							<h1 class="mt-1 mb-3"><?php echo $total_keluar_transactions; ?></h1>

						</div>
					</div>
					<!-- Saldo Akhir -->
					<div class="card">
						<div class="card-body">
							<div class="row">
								<div class="col mt-0">
									<h5 class="card-title">Saldo Akhir</h5>
								</div>

								<div class="col-auto">
									<div class="stat text-primary">
										<i class="align-middle" data-feather="table"></i>
									</div>
								</div>
							</div>
							<h1 class="mt-1 mb-3"><?php echo number_format($balance, 2); ?></h1>

						</div>
					</div>


				</div>
			</div>
		</div>
	</div>

</div>

<div class="row">

	<div class="col-12 col-md-12 col-xxl-12 d-flex order-3 order-xxl-2">
		<div class="card flex-fill w-100">
			<div class="card-header">

				<h5 class="card-title mb-0">Grafik Penjualan</h5>
			</div>
			<div class="card-body px-4">
				<canvas id="penjualanChart"></canvas>
			</div>
		</div>
	</div>
</div>


<script>
	// Fetch data from data_penjualan.php
	fetch('data_penjualan.php')
		.then(response => response.json())
		.then(data => {
			const labels = data.map(entry => entry.tgl_jual);
			const totalPenjualan = data.map(entry => entry.total_penjualan);

			const ctx = document.getElementById('penjualanChart').getContext('2d');
			const penjualanChart = new Chart(ctx, {
				type: 'bar',
				data: {
					labels: labels,
					datasets: [{
						label: 'Total Penjualan',
						data: totalPenjualan,
						backgroundColor: 'rgba(75, 192, 192, 0.2)',
						borderColor: 'rgba(75, 192, 192, 1)',
						borderWidth: 1,
						fill: false
					}]
				},
				options: {
					scales: {
						y: {
							beginAtZero: true
						}
					}
				}
			});
		});
</script>

<?php include '../lib/komponen/wrap-bottom.php'; ?>