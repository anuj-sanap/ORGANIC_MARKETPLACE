<?php
require __DIR__ . '/../config/database.php';
header('Content-Type: application/json');
session_start();

$action = $_GET['action'] ?? $_POST['action'] ?? null;
if ($action === 'list' && isset($_SESSION['user_id'])) {
	$role = $_SESSION['role'] ?? null;
	if ($role === 'buyer') {
		$stmt = $pdo->prepare("SELECT * FROM orders WHERE buyer_id = ? ORDER BY created_at DESC");
		$stmt->execute([$_SESSION['user_id']]);
		echo json_encode(['success'=>true,'data'=>$stmt->fetchAll()]);
	} else {
		// For farmer, return orders that include their crops
		$stmt = $pdo->prepare("SELECT o.* FROM orders o JOIN order_items oi ON o.order_id=oi.order_id JOIN crops c ON oi.crop_id=c.crop_id WHERE c.farmer_id = ? GROUP BY o.order_id ORDER BY o.created_at DESC");
		$stmt->execute([$_SESSION['user_id']]);
		echo json_encode(['success'=>true,'data'=>$stmt->fetchAll()]);
	}
	exit;
}

echo json_encode(['success'=>false,'message'=>'No action or not authenticated']);
