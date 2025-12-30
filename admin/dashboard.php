<?php 
require '../config/database.php'; 
include '../includes/functions.php'; 
if (!isLoggedIn() || getRole() != 'admin') redirect('../login.php');

/* ----- FUNCTIONALITY UNCHANGED ----- */

// Approve/Reject crops
if ($_POST && isset($_POST['crop_id'])) {
    $status = $_POST['status'];
    $crop_id = $_POST['crop_id'];
    $stmt = $pdo->prepare("UPDATE crops SET status = ? WHERE crop_id = ?");
    $stmt->execute([$status, $crop_id]);
    $success = "Crop status updated!";
}

// Stats
$stats = [
    'users' => $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(),
    'farmers' => $pdo->query("SELECT COUNT(*) FROM users WHERE role='farmer'")->fetchColumn(),
    'crops' => $pdo->query("SELECT COUNT(*) FROM crops WHERE status='approved'")->fetchColumn(),
    'pending' => $pdo->query("SELECT COUNT(*) FROM crops WHERE status='pending'")->fetchColumn()
];

// Pending crops
$pending = $pdo->query("
    SELECT c.*, u.username 
    FROM crops c 
    JOIN users u ON c.farmer_id = u.user_id 
    WHERE c.status = 'pending' 
    ORDER BY c.created_at DESC
")->fetchAll();
?>

<?php include '../includes/header.php'; ?>

<div class="container-fluid px-4 py-4">

    <!-- PAGE HEADER -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Admin Dashboard</h2>
            <p class="text-muted mb-0">Manage users, crops, and approvals</p>
        </div>
    </div>

    <?php if (isset($success)): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i><?php echo $success; ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- STATS CARDS -->
    <div class="row g-4 mb-5">

        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="icon-circle bg-primary text-white mb-3">
                        <i class="fas fa-users"></i>
                    </div>
                    <h3 class="fw-bold"><?php echo $stats['users']; ?></h3>
                    <p class="text-muted mb-0">Total Users</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="icon-circle bg-success text-white mb-3">
                        <i class="fas fa-tractor"></i>
                    </div>
                    <h3 class="fw-bold"><?php echo $stats['farmers']; ?></h3>
                    <p class="text-muted mb-0">Farmers</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="icon-circle bg-info text-white mb-3">
                        <i class="fas fa-seedling"></i>
                    </div>
                    <h3 class="fw-bold"><?php echo $stats['crops']; ?></h3>
                    <p class="text-muted mb-0">Approved Crops</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3 col-md-6">
            <div class="card shadow-sm border-0 h-100">
                <div class="card-body text-center">
                    <div class="icon-circle bg-warning text-white mb-3">
                        <i class="fas fa-clock"></i>
                    </div>
                    <h3 class="fw-bold"><?php echo $stats['pending']; ?></h3>
                    <p class="text-muted mb-0">Pending Approvals</p>
                </div>
            </div>
        </div>

    </div>

    <!-- PENDING CROPS TABLE -->
    <div class="card shadow-sm border-0">
        <div class="card-header bg-white border-bottom">
            <h5 class="fw-bold mb-0">
                <i class="fas fa-hourglass-half text-danger me-2"></i>
                Pending Crop Approvals
            </h5>
        </div>

        <div class="card-body p-0">
            <?php if (empty($pending)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-check-circle fa-4x text-success mb-3"></i>
                    <h5 class="fw-bold">No Pending Crops</h5>
                    <p class="text-muted">All submitted crops have been reviewed</p>
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table align-middle table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Crop Name</th>
                                <th>Farmer</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($pending as $crop): ?>
                            <tr>
                                <td class="fw-semibold"><?php echo htmlspecialchars($crop['name']); ?></td>
                                <td><?php echo htmlspecialchars($crop['username']); ?></td>
                                <td><?php echo formatPrice($crop['price']); ?></td>
                                <td><?php echo $crop['quantity']; ?> kg</td>
                                <td class="text-center">
                                    <form method="POST" class="d-inline-flex gap-2">
                                        <input type="hidden" name="crop_id" value="<?php echo $crop['crop_id']; ?>">
                                        <button name="status" value="approved" class="btn btn-success btn-sm px-3">
                                            <i class="fas fa-check me-1"></i>Approve
                                        </button>
                                        <button name="status" value="rejected" class="btn btn-outline-danger btn-sm px-3">
                                            <i class="fas fa-times me-1"></i>Reject
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<!-- UI HELPER STYLES (ONLY UI) -->
<style>
.icon-circle {
    width: 55px;
    height: 55px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
    margin: auto;
}
.card:hover {
    transform: translateY(-3px);
    transition: 0.3s ease;
}
</style>

<?php include '../includes/footer.php'; ?>
