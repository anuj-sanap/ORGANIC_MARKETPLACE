<?php
require '../config/database.php';
include '../includes/functions.php';
if (!isLoggedIn() || getRole() != 'farmer') redirect('../login.php');

// Orders that contain crops from this farmer
$stmt = $pdo->prepare("SELECT o.* FROM orders o
	JOIN order_items oi ON o.order_id = oi.order_id
	JOIN crops c ON oi.crop_id = c.crop_id
	WHERE c.farmer_id = ?
	GROUP BY o.order_id
	ORDER BY o.created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();
?>
<?php include '../includes/header.php'; ?>
<div class="container py-5">
	<h2 class="mb-4">Orders for Your Produce</h2>
	<?php if (empty($orders)): ?>
		<div class="alert alert-info">No orders yet.</div>
	<?php else: ?>
		<div class="list-group">
			<?php foreach ($orders as $o): ?>
				<a class="list-group-item list-group-item-action" href="update_order.php?order_id=<?php echo $o['order_id']; ?>">
					Order #<?php echo $o['order_id']; ?> — <?php echo ucfirst($o['status']); ?> — <?php echo $o['created_at']; ?>
				</a>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>

