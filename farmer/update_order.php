<?php
require '../config/database.php';
include '../includes/functions.php';
if (!isLoggedIn() || getRole() != 'farmer') redirect('../login.php');

$order_id = $_GET['order_id'] ?? null;
if (!$order_id) redirect('my_orders.php');

// Verify this order contains at least one crop from this farmer
$stmt = $pdo->prepare("SELECT o.* FROM orders o
	JOIN order_items oi ON o.order_id = oi.order_id
	JOIN crops c ON oi.crop_id = c.crop_id
	WHERE o.order_id = ? AND c.farmer_id = ?");
$stmt->execute([$order_id, $_SESSION['user_id']]);
$order = $stmt->fetch();
if (!$order) {
	die('Order not found or you are not authorized.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['status'])) {
	$status = $_POST['status'];
	$upd = $pdo->prepare("UPDATE orders SET status = ? WHERE order_id = ?");
	$upd->execute([$status, $order_id]);
	$message = 'Order updated.';
	// reload
	header('Location: update_order.php?order_id=' . $order_id);
	exit;
}

// fetch items for display
$items = $pdo->prepare("SELECT oi.*, c.name, c.farmer_id FROM order_items oi JOIN crops c ON oi.crop_id = c.crop_id WHERE oi.order_id = ? AND c.farmer_id = ?");
$items->execute([$order_id, $_SESSION['user_id']]);
$items = $items->fetchAll();
?>
<?php include '../includes/header.php'; ?>
<div class="container py-5">
	<h3>Order #<?php echo $order['order_id']; ?></h3>
	<p>Status: <strong><?php echo ucfirst($order['status']); ?></strong></p>
	<?php if (!empty($message)): ?><div class="alert alert-success"><?php echo $message; ?></div><?php endif; ?>
	<h5>Items from your farm</h5>
	<ul class="list-group mb-4">
		<?php foreach ($items as $it): ?>
			<li class="list-group-item"><?php echo htmlspecialchars($it['name']); ?> — qty: <?php echo $it['quantity']; ?> — price: <?php echo formatPrice($it['price']); ?></li>
		<?php endforeach; ?>
	</ul>

	<form method="POST" class="mb-3">
		<label class="form-label">Update Order Status</label>
		<select name="status" class="form-control mb-2">
			<option value="pending" <?php echo $order['status']=='pending'?'selected':''; ?>>Pending</option>
			<option value="confirmed" <?php echo $order['status']=='confirmed'?'selected':''; ?>>Confirmed</option>
			<option value="shipped" <?php echo $order['status']=='shipped'?'selected':''; ?>>Shipped</option>
			<option value="delivered" <?php echo $order['status']=='delivered'?'selected':''; ?>>Delivered</option>
		</select>
		<button class="btn btn-primary">Update</button>
	</form>

	<a href="my_orders.php" class="btn btn-outline-secondary">Back to orders</a>
</div>
<?php include '../includes/footer.php'; ?>

