<?php
require '../config/database.php';
include '../includes/functions.php';
if (!isLoggedIn() || getRole() != 'buyer') redirect('../login.php');

$cart = $_SESSION['cart'] ?? [];
$items = [];
$total = 0;
if (!empty($cart)) {
	$ids = array_keys($cart);
	$placeholders = implode(',', array_fill(0, count($ids), '?'));
	$stmt = $pdo->prepare("SELECT * FROM crops WHERE crop_id IN ($placeholders)");
	$stmt->execute($ids);
	$rows = $stmt->fetchAll();
	foreach ($rows as $r) {
		$qty = $cart[$r['crop_id']] ?? 1;
		$r['qty'] = $qty;
		$r['subtotal'] = $qty * $r['price'];
		$total += $r['subtotal'];
		$items[] = $r;
	}
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['checkout'])) {
	header('Location: checkout.php'); exit;
}
?>
<?php include '../includes/header.php'; ?>
<div class="container py-5">
	<h2>Your Cart</h2>
	<?php if (empty($items)): ?>
		<div class="alert alert-info">Your cart is empty.</div>
	<?php else: ?>
		<table class="table">
			<thead><tr><th>Crop</th><th>Price</th><th>Qty</th><th>Subtotal</th></tr></thead>
			<tbody>
				<?php foreach ($items as $it): ?>
					<tr>
						<td><?php echo htmlspecialchars($it['name']); ?></td>
						<td><?php echo formatPrice($it['price']); ?></td>
						<td><?php echo $it['qty']; ?></td>
						<td><?php echo formatPrice($it['subtotal']); ?></td>
					</tr>
				<?php endforeach; ?>
			</tbody>
		</table>
		<div class="d-flex justify-content-between align-items-center">
			<h4>Total: <?php echo formatPrice($total); ?></h4>
			<form method="POST"><button name="checkout" class="btn btn-success">Proceed to Checkout</button></form>
		</div>
	<?php endif; ?>
</div>
<?php include '../includes/footer.php'; ?>

