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

	if (!$username || !$email || !$password) $error = 'Please fill all required fields.';
	elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $error = 'Invalid email.';
	elseif ($password !== $confirm) $error = 'Passwords do not match.';
	else {
		$hash = password_hash($password, PASSWORD_BCRYPT);
		try {
			$stmt = $pdo->prepare("INSERT INTO users (username, email, password, phone, role) VALUES (?, ?, ?, ?, ?)");
			$stmt->execute([$username, $email, $hash, $phone, 'buyer']);
			$success = 'Registration successful! Await admin approval.';
		} catch (PDOException $e) {
			$error = 'Registration failed: email or username may already exist.';
		}
	}
}
?>
<?php include '../includes/header.php'; ?>
<div class="container mt-5">
	<div class="row justify-content-center">
		<div class="col-md-6">
			<div class="card shadow">
				<div class="card-header bg-success text-white text-center">Buyer Registration</div>
				<div class="card-body">
					<?php if ($error): ?><div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div><?php endif; ?>
					<?php if ($success): ?><div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div><?php endif; ?>
					<form method="POST">
						<div class="mb-3"><label>Username</label><input type="text" name="username" class="form-control" required></div>
						<div class="mb-3"><label>Email</label><input type="email" name="email" class="form-control" required></div>
						<div class="mb-3"><label>Phone</label><input type="tel" name="phone" class="form-control"></div>
						<div class="mb-3"><label>Password</label><input type="password" name="password" class="form-control" required></div>
						<div class="mb-3"><label>Confirm Password</label><input type="password" name="confirm_password" class="form-control" required></div>
						<button class="btn btn-success w-100">Register</button>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
<?php include '../includes/footer.php'; ?>

