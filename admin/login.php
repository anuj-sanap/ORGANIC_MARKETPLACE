<?php
require '../config/database.php';
include '../includes/functions.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$email = $_POST['email'] ?? '';
	$password = $_POST['password'] ?? '';

	$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin' AND status = 'approved'");
	$stmt->execute([$email]);
	$user = $stmt->fetch();

	if ($user && password_verify($password, $user['password'])) {
		$_SESSION['user_id'] = $user['user_id'];
		$_SESSION['username'] = $user['username'];
		$_SESSION['role'] = $user['role'];
		header('Location: dashboard.php');
		exit;
	} else {
		$error = 'Invalid credentials or account not approved.';
	}
}
?>
<?php include '../includes/header.php'; ?>
<div class="container mt-5">
	<div class="row justify-content-center">
		<div class="col-md-5">
			<div class="card shadow">
				<div class="card-header bg-primary text-white text-center">Admin Login</div>
				<div class="card-body">
					<?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
					<form method="POST">
						<div class="mb-3">
							<label>Email</label>
							<input type="email" name="email" class="form-control" required>
						</div>
						<div class="mb-3">
							<label>Password</label>
							<input type="password" name="password" class="form-control" required>
						</div>
						<button class="btn btn-primary w-100">Login</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include '../includes/footer.php'; ?>

