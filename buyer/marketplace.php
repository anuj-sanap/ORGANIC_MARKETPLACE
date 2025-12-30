<?php 
require '../config/database.php'; 
include '../includes/functions.php'; 
if (!isLoggedIn() || getRole() != 'buyer') redirect('../login.php');

if ($_POST && isset($_POST['add_to_cart'])) {
    $crop_id = $_POST['crop_id'];
    $quantity = $_POST['quantity'];
    
    // Add to session cart
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    $_SESSION['cart'][$crop_id] = $quantity;
    
    $success = "Added to cart! <a href='cart.php'>View Cart</a>";
}

// Fetch approved crops
$stmt = $pdo->query("SELECT c.*, u.username as farmer_name, cat.name as category 
                     FROM crops c 
                     JOIN users u ON c.farmer_id = u.user_id 
                     LEFT JOIN categories cat ON c.cat_id = cat.cat_id 
                     WHERE c.status = 'approved' AND c.quantity > 0 
                     ORDER BY c.created_at DESC");
$crops = $stmt->fetchAll();
?>
<?php include '../includes/header.php'; ?>
<div class="container-fluid py-5 bg-light min-vh-100">
    <div class="row mb-5">
        <div class="col-12 text-center">
            <h1 class="display-4 fw-bold mb-3">
                <i class="fas fa-store text-success me-3"></i>
                Organic Marketplace
            </h1>
            <?php if (isset($success)): ?>
                <div class="alert alert-success alert-dismissible fade show"><?php echo $success; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <div class="row g-4">
        <?php foreach ($crops as $crop): ?>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="crop-card h-100">
                <div class="position-relative overflow-hidden">
                    <?php if ($crop['image']): ?>
                    <img src="../assets/uploads/<?php echo htmlspecialchars($crop['image']); ?>" class="crop-img w-100" alt="<?php echo $crop['name']; ?>">
                    <?php endif; ?>
                    <span class="badge bg-success position-absolute top-3 end-3"><?php echo $crop['quantity']; ?>kg</span>
                </div>
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <h5 class="fw-bold mb-0"><?php echo htmlspecialchars($crop['name']); ?></h5>
                        <div class="text-warning">
                            ★★★★☆
                        </div>
                    </div>
                    <div class="mb-3">
                        <small class="text-muted">by <?php echo htmlspecialchars($crop['farmer_name']); ?></small>
                    </div>
                    <h3 class="text-success fw-bold mb-3"><?php echo formatPrice($crop['price']); ?>/kg</h3>
                    
                    <?php if ($crop['description']): ?>
                    <p class="text-muted small mb-4"><?php echo substr($crop['description'], 0, 100); ?>...</p>
                    <?php endif; ?>
                    
                    <form method="POST" class="add-to-cart-form">
                        <input type="hidden" name="crop_id" value="<?php echo $crop['crop_id']; ?>">
                        <div class="input-group mb-3">
                            <span class="input-group-text bg-success text-white">
                                <i class="fas fa-hashtag"></i>
                            </span>
                            <input type="number" name="quantity" class="form-control qty-input" value="1" min="1" max="<?php echo $crop['quantity']; ?>">
                            <button type="submit" name="add_to_cart" class="btn btn-success add-to-cart px-4">
                                <i class="fas fa-cart-plus me-1"></i>Add
                            </button>
                        </div>
                    </form>
                    <a href="cart.php" class="btn btn-outline-success w-100">
                        <i class="fas fa-shopping-cart me-2"></i>View Cart
                    </a>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>
<?php include '../includes/footer.php'; ?>
