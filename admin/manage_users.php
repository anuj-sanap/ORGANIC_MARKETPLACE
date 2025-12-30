<?php
require '../config/database.php';
include '../includes/functions.php';
if (!isLoggedIn() || getRole() != 'admin') redirect('../login.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['user_id'], $_POST['status'])) {
	$upd = $pdo->prepare("UPDATE users SET status = ? WHERE user_id = ?");
	$upd->execute([$_POST['status'], $_POST['user_id']]);
}

$users = $pdo->query("SELECT * FROM users ORDER BY created_at DESC")->fetchAll();
?>
<?php include '../includes/header.php'; ?>
<div class="container py-5">
	<h2>Manage Users</h2>
	<table class="table">
		<thead><tr><th>ID</th><th>Username</th><th>Email</th><th>Role</th><th>Status</th><th>Action</th></tr></thead>
		<tbody>
			<?php foreach ($users as $u): ?>
			<tr>
				<td><?php echo $u['user_id']; ?></td>
				<td><?php echo htmlspecialchars($u['username']); ?></td>
				<td><?php echo htmlspecialchars($u['email']); ?></td>
				<td><?php echo $u['role']; ?></td>
				<td><?php echo $u['status']; ?></td>
				<td>
					<form method="POST" class="d-inline">
						<input type="hidden" name="user_id" value="<?php echo $u['user_id']; ?>">
						<select name="status" class="form-select d-inline w-auto">
							<option value="pending" <?php echo $u['status']=='pending'?'selected':''; ?>>pending</option>
							<option value="approved" <?php echo $u['status']=='approved'?'selected':''; ?>>approved</option>
							<option value="suspended" <?php echo $u['status']=='suspended'?'selected':''; ?>>suspended</option>
						</select>
						<button class="btn btn-sm btn-primary">Update</button>
					</form>
				</td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>
</div>
<?php include '../includes/footer.php'; ?>

