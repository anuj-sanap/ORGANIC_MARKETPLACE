<?php
require __DIR__ . '/../config/database.php';
header('Content-Type: application/json');

$action = $_GET['action'] ?? $_POST['action'] ?? 'list';
if ($action === 'list') {
	$stmt = $pdo->prepare("SELECT c.*, u.username as farmer_name, cat.name as category FROM crops c JOIN users u ON c.farmer_id=u.user_id LEFT JOIN categories cat ON c.cat_id=cat.cat_id WHERE c.status='approved'");
	$stmt->execute();
	$rows = $stmt->fetchAll();
	echo json_encode(['success'=>true,'data'=>$rows]);
	exit;
}

echo json_encode(['success'=>false,'message'=>'No action']);

