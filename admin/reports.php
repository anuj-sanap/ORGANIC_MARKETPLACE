<?php
require '../config/database.php';
include '../includes/functions.php';
if (!isLoggedIn() || getRole() != 'admin') redirect('../login.php');

$totalSales = $pdo->query("SELECT SUM(total_amount) FROM orders")->fetchColumn();
$ordersCount = $pdo->query("SELECT COUNT(*) FROM orders")->fetchColumn();
$topCrops = $pdo->query("SELECT c.name, SUM(oi.quantity) as sold FROM order_items oi JOIN crops c ON oi.crop_id=c.crop_id GROUP BY oi.crop_id ORDER BY sold DESC LIMIT 5")->fetchAll();
?>
<?php include '../includes/header.php'; ?>
<div class="container py-5">
	<h2>Reports</h2>
	<div class="row mt-4">
		<div class="col-md-4"><div class="card p-3">Total Sales<br><strong><?php echo formatPrice($totalSales ?: 0); ?></strong></div></div>
		<div class="col-md-4"><div class="card p-3">Total Orders<br><strong><?php echo $ordersCount; ?></strong></div></div>
		<div class="col-md-4"><div class="card p-3">Top Crops<br>
			<?php foreach ($topCrops as $c): ?>
				<div><?php echo htmlspecialchars($c['name']); ?> — <?php echo $c['sold']; ?>kg</div>
			<?php endforeach; ?></div></div>
	</div>
</div>
<?php include '../includes/footer.php'; ?>

