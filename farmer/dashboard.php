<?php 
require '../config/database.php'; 
include '../includes/functions.php'; 
if (!isLoggedIn() || getRole() != 'farmer') redirect('../login.php');

/* -------- FUNCTIONALITY UNCHANGED -------- */

// Add crop
if ($_POST && isset($_POST['add_crop'])) {
    $name = $_POST['name'];
    $cat_id = $_POST['cat_id'];
    $price = $_POST['price'];
    $quantity = $_POST['quantity'];
    $description = $_POST['description'];
    $certification = $_POST['certification'] ?? '';
    
    $image = uploadImage($_FILES['image']);
    
    $stmt = $pdo->prepare("
        INSERT INTO crops 
        (farmer_id, cat_id, name, description, price, quantity, image, certification) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        $_SESSION['user_id'], $cat_id, $name, $description,
        $price, $quantity, $image, $certification
    ]);

    $success = "Crop added successfully! Awaiting admin approval.";
}

// Fetch farmer crops
$stmt = $pdo->prepare("
    SELECT c.*, cat.name as cat_name 
    FROM crops c 
    LEFT JOIN categories cat ON c.cat_id = cat.cat_id 
    WHERE farmer_id = ? 
    ORDER BY created_at DESC
");
$stmt->execute([$_SESSION['user_id']]);
$crops = $stmt->fetchAll();
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid px-4 py-4">

    <!-- PAGE HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">
                <i class="fas fa-tractor text-success me-2"></i>
                <?php echo htmlspecialchars($_SESSION['username']); ?>
            </h2>
            <p class="text-muted mb-0">Manage your crops and inventory</p>
        </div>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- ADD CROP -->
    <div class="row mb-5">
        <div class="col-xl-8 col-lg-10">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-plus-circle me-2"></i>Add New Crop
                    </h5>
                </div>

                <div class="card-body">
                    <form method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="add_crop" value="1">

                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Crop Name</label>
                                <input type="text" name="name" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Category</label>
                                <select name="cat_id" class="form-select" required>
                                    <?php
                                    $cats = $pdo->query("SELECT * FROM categories")->fetchAll();
                                    foreach ($cats as $cat): ?>
                                        <option value="<?php echo $cat['cat_id']; ?>">
                                            <?php echo htmlspecialchars($cat['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Price (₹/kg)</label>
                                <input type="number" step="0.01" name="price" class="form-control" required>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Quantity (kg)</label>
                                <input type="number" name="quantity" class="form-control" required>
                            </div>

                            <div class="col-12">
                                <label class="form-label fw-semibold">Description</label>
                                <textarea name="description" class="form-control" rows="3"></textarea>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Certification</label>
                                <input type="text" name="certification" class="form-control"
                                       placeholder="NPOP, USDA Organic, etc.">
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold">Crop Image</label>
                                <input type="file" name="image" class="form-control" accept="image/*" required>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-success px-4">
                                <i class="fas fa-upload me-2"></i>Add Crop
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MY CROPS -->
    <div class="mb-3">
        <h4 class="fw-bold">My Crops</h4>
        <p class="text-muted mb-4">Total listed: <?php echo count($crops); ?></p>
    </div>

    <div class="row g-4">
        <?php foreach ($crops as $crop): ?>
        <div class="col-xl-3 col-lg-4 col-md-6">
            <div class="card crop-card h-100 shadow-sm border-0">
                <?php if ($crop['image']): ?>
                    <img src="../assets/uploads/<?php echo $crop['image']; ?>"
                         class="card-img-top crop-img"
                         alt="<?php echo htmlspecialchars($crop['name']); ?>">
                <?php endif; ?>

                <div class="card-body">
                    <h5 class="fw-semibold mb-1">
                        <?php echo htmlspecialchars($crop['name']); ?>
                    </h5>

                    <p class="text-muted small mb-2">
                        <?php echo $crop['cat_name'] ?? 'Uncategorized'; ?>
                    </p>

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="fw-bold text-success">
                            <?php echo formatPrice($crop['price']); ?>
                        </span>

                        <span class="badge rounded-pill bg-<?php 
                            echo $crop['status'] == 'approved' ? 'success' : 'warning'; ?>">
                            <?php echo ucfirst($crop['status']); ?>
                        </span>
                    </div>

                    <small class="text-muted">
                        <?php echo $crop['quantity']; ?> kg available
                    </small>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

</div>

<!-- UI-ONLY STYLES -->
<style>
.crop-img {
    height: 18px;
    object-fit: cover;
}
.crop-card:hover {
    transform: translateY(-4px);
    transition: 0.3s ease;
}
</style>

<?php include '../includes/footer.php'; ?>
