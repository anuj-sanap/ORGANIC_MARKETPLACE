<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getRole() {
    return $_SESSION['role'] ?? null;
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function uploadImage($file) {
    $target_dir = "../assets/uploads/";
    if (!file_exists($target_dir)) {
        mkdir($target_dir, 0777, true);
    }
    
    $target_file = $target_dir . time() . '_' . basename($file["name"]);
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    
    $allowed = ['jpg', 'jpeg', 'png', 'gif'];
    if (in_array($imageFileType, $allowed) && $file["size"] < 5000000) {
        if (move_uploaded_file($file["tmp_name"], $target_file)) {
            return basename($target_file);
        }
    }
    return false;
}

function formatPrice($price) {
    return '₹' . number_format($price, 2);
}
?>
