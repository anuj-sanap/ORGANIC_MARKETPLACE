<?php
require '../config/database.php';
include '../includes/functions.php';

$success = $error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	$username = trim($_POST['username'] ?? '');
	$email = trim($_POST['email'] ?? '');
	$phone = trim($_POST['phone'] ?? '');
	$password = $_POST['password'] ?? '';
	$confirm = $_POST['confirm_password'] ?? '';

	if (!$username || !$email || !$password) {
		$error = 'Please fill in all required fields.';
	} elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		$error = 'Please provide a valid email address.';
	} elseif ($password !== $confirm) {
		$error = 'Passwords do not match.';
	} else {
		$hash = password_hash($password, PASSWORD_BCRYPT);
		try {
			$stmt = $pdo->prepare("INSERT INTO users (username, email, password, phone, role) VALUES (?, ?, ?, ?, ?)");
			$stmt->execute([$username, $email, $hash, $phone, 'farmer']);
			$success = 'Registration successful! Await admin approval.';
		} catch (PDOException $e) {
			// handle duplicate email/username or other DB errors
			$error = 'Registration failed: ' . ($e->getCode() === '23000' ? 'Email or username already exists.' : 'Please try again later.');
		}
	}
}
?>
<?php include '../includes/header.php'; ?>
<div class="container mt-5">
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card shadow">
				<div class="card-header bg-success text-white text-center">
					<h3>Farmer Registration</h3>
				</div>
				<div class="card-body">
					<?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
					<?php if ($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>

					<form method="POST" novalidate>
						<div class="mb-3">
							<label class="form-label">Username</label>
							<input type="text" name="username" class="form-control" required value="<?php echo isset($username) ? htmlspecialchars($username) : ''; ?>">
						</div>
						<div class="mb-3">
							<label class="form-label">Email</label>
							<input type="email" name="email" class="form-control" required value="<?php echo isset($email) ? htmlspecialchars($email) : ''; ?>">
						</div>
						<div class="mb-3">
							<label class="form-label">Phone</label>
							<input type="tel" name="phone" class="form-control" value="<?php echo isset($phone) ? htmlspecialchars($phone) : ''; ?>">
						</div>
						<div class="mb-3">
							<label class="form-label">Password</label>
							<input type="password" name="password" class="form-control" required>
						</div>
						<div class="mb-3">
							<label class="form-label">Confirm Password</label>
							<input type="password" name="confirm_password" class="form-control" required>
						</div>
						<button type="submit" class="btn btn-success w-100">Register as Farmer</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include '../includes/footer.php'; ?>

