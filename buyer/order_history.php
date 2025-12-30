<?php
require '../config/database.php';
include '../includes/functions.php';
if (!isLoggedIn() || getRole() != 'buyer') redirect('../login.php');

$stmt = $pdo->prepare("SELECT * FROM orders WHERE buyer_id = ? ORDER BY created_at DESC");
$stmt->execute([$_SESSION['user_id']]);
$orders = $stmt->fetchAll();
?>
<?php include '../includes/header.php'; ?>
<div class="container py-5">
	<h2>Your Orders</h2>
	<?php if (empty($orders)): ?>
		<div class="alert alert-info">You have not placed any orders yet.</div>
	<?php else: ?>
		<?php foreach ($orders as $o): ?>
			<div class="card mb-3">
				<div class="card-body">
					<h5>Order #<?php echo $o['order_id']; ?> — <?php echo ucfirst($o['status']); ?></h5>
					<p class="mb-1">Placed: <?php echo $o['created_at']; ?></p>
					<p class="mb-1">Total: <?php echo formatPrice($o['total_amount']); ?></p>
					<a class="btn btn-sm btn-outline-primary" href="../public/index.php?action=view_order&order_id=<?php echo $o['order_id']; ?>">View details</a>
				</div>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>

