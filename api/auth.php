<?php
require __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

$action = $_POST['action'] ?? $_GET['action'] ?? null;
if ($action === 'login') {
	$email = $_POST['email'] ?? '';
	$password = $_POST['password'] ?? '';
	$stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'approved'");
	$stmt->execute([$email]);
	$user = $stmt->fetch();
	if ($user && password_verify($password, $user['password'])) {
		session_start();
		$_SESSION['user_id'] = $user['user_id'];
		$_SESSION['username'] = $user['username'];
		$_SESSION['role'] = $user['role'];
		echo json_encode(['success'=>true,'role'=>$user['role']]);
	} else echo json_encode(['success'=>false,'message'=>'Invalid credentials']);
	exit;
}

if ($action === 'logout') {
	session_start(); session_destroy();
	echo json_encode(['success'=>true]); exit;
}

if ($action === 'register') {
	$username = $_POST['username'] ?? '';
	$email = $_POST['email'] ?? '';
	$password = $_POST['password'] ?? '';
	$role = $_POST['role'] ?? 'buyer';
	if (!$username || !$email || !$password) {
		echo json_encode(['success'=>false,'message'=>'Missing fields']); exit;
	}
	$hash = password_hash($password, PASSWORD_BCRYPT);
	try {
		$stmt = $pdo->prepare("INSERT INTO users (username,email,password,role) VALUES (?,?,?,?)");
		$stmt->execute([$username,$email,$hash,$role]);
		echo json_encode(['success'=>true]);
	} catch (Exception $e) {
		echo json_encode(['success'=>false,'message'=>'Registration failed']);
	}
	exit;
}

echo json_encode(['success'=>false,'message'=>'No action']);

