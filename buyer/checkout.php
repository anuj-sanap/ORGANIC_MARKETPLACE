<?php
require '../config/database.php';
include '../includes/functions.php';
if (!isLoggedIn() || getRole() != 'buyer') redirect('../login.php');

$cart = $_SESSION['cart'] ?? [];
if (empty($cart)) {
	redirect('cart.php');
}

// Build cart items
$ids = array_keys($cart);
$placeholders = implode(',', array_fill(0, count($ids), '?'));
$stmt = $pdo->prepare("SELECT * FROM crops WHERE crop_id IN ($placeholders)");
$stmt->execute($ids);
$rows = $stmt->fetchAll();
$total = 0;
foreach ($rows as $r) {
	$qty = $cart[$r['crop_id']];
	$total += $qty * $r['price'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$address = $_POST['address'] ?? '';
	if (!$address) {
		$error = 'Please provide delivery address.';
	} else {
		try {
			$pdo->beginTransaction();
			$ins = $pdo->prepare("INSERT INTO orders (buyer_id, total_amount, delivery_address) VALUES (?, ?, ?)");
			$ins->execute([$_SESSION['user_id'], $total, $address]);
			$order_id = $pdo->lastInsertId();
			$itIns = $pdo->prepare("INSERT INTO order_items (order_id, crop_id, quantity, price) VALUES (?, ?, ?, ?)");
			foreach ($rows as $r) {
				$qty = $cart[$r['crop_id']];
				$itIns->execute([$order_id, $r['crop_id'], $qty, $r['price']]);
				// reduce quantity
				$upd = $pdo->prepare("UPDATE crops SET quantity = quantity - ? WHERE crop_id = ?");
				$upd->execute([$qty, $r['crop_id']]);
			}
			$pdo->commit();
			unset($_SESSION['cart']);
			header('Location: order_history.php'); exit;
		} catch (Exception $e) {
			$pdo->rollBack();
			$error = 'Checkout failed. Please try again.';
		}
	}
}
?>
<?php include '../includes/header.php'; ?>
<div class="container py-5">
	<h2>Checkout</h2>
	<?php if (!empty($error)): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
	<p>Total: <strong><?php echo formatPrice($total); ?></strong></p>
	<form method="POST">
		<div class="mb-3"><label>Delivery Address</label><textarea name="address" class="form-control" required></textarea></div>
		<button class="btn btn-success">Place Order</button>
	</form>
</div>
<?php include '../includes/footer.php'; ?>
