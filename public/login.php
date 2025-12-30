<?php 
require_once 'config/database.php'; 
session_start();

// Handle AJAX requests
if ($_POST['action'] ?? false) {
    header('Content-Type: application/json');
    
    if ($_POST['action'] == 'login') {
        $email = $_POST['email'];
        $password = $_POST['password'];
        
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND status = 'approved'");
        $stmt->execute([$email]);
        $user = $stmt->fetch();
        
        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            echo json_encode(['success' => true, 'role' => $user['role'], 'username' => $user['username']]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Invalid credentials']);
        }
        exit;
    }
    
    if ($_POST['action'] == 'register') {
        try {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
            $role = $_POST['role'];
            
            $stmt = $pdo->prepare("INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)");
            $stmt->execute([$username, $email, $password, $role]);
            echo json_encode(['success' => true, 'message' => 'Registration successful!']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'Email already exists']);
        }
        exit;
    }
    
    if ($_POST['action'] == 'add_crop' && isset($_SESSION['user_id'])) {
        // Add crop logic here (simplified)
        echo json_encode(['success' => true, 'message' => 'Crop added! Awaiting approval.']);
        exit;
    }
    
    if ($_POST['action'] == 'logout') {
        session_destroy();
        echo json_encode(['success' => true]);
        exit;
    }
}

// Check login status
$isLoggedIn = isset($_SESSION['user_id']);
$role = $_SESSION['role'] ?? null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmFresh Organic - Single Page Interactive App</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
    <!-- Floating Background -->
    <div class="floating-bg"></div>
    
    <!-- SPA Navigation -->
    <nav class="navbar navbar-expand-lg navbar-glass fixed-top">
        <div class="container">
            <a class="navbar-brand fw-bold fs-3" href="#" onclick="showPage('home')">
                <i class="fas fa-seedling text-success me-2"></i>FarmFresh
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="#" onclick="showPage('home')">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#" onclick="showPage('marketplace')">Marketplace</a></li>
                </ul>
                <ul class="navbar-nav">
                    <?php if ($isLoggedIn): ?>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
                                <i class="fas fa-user-circle me-1"></i><?php echo $_SESSION['username']; ?>
                            </a>
                            <ul class="dropdown-menu">
                                <li><a class="dropdown-item" href="#" onclick="showPage('<?php echo $role; ?>')"><?php echo ucfirst($role); ?> Dashboard</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="#" onclick="logout()">Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="#" onclick="showPage('login')">Login</a></li>
                        <li class="nav-item"><a class="nav-link" href="#" onclick="showPage('register')">Register</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <!-- SPA Content Area -->
    <div class="page-container">
        <!-- HOME PAGE -->
        <div id="home" class="page-content active">
            <section class="hero-section">
                <div class="container">
                    <div class="row align-items-center min-vh-100">
                        <div class="col-lg-6">
                            <h1 class="display-3 fw-bold mb-4 gradient-text">
                                Farm to <span class="text-success">Table</span><br>
                                in <span class="animate-pulse">24 Hours</span>
                            </h1>
                            <p class="lead mb-5 text-white-50">Single Page Interactive App • Live Data • Zero Reloads</p>
                            <div class="d-flex flex-wrap gap-3">
                                <button onclick="showPage('marketplace')" class="btn btn-success btn-lg px-5 py-3 shadow-lg">
                                    <i class="fas fa-shopping-cart me-2"></i>Shop Now
                                </button>
                                <?php if (!$isLoggedIn): ?>
                                <button onclick="showPage('register')" class="btn btn-outline-light btn-lg px-5 py-3">
                                    <i class="fas fa-user-plus me-2"></i>Join Free
                                </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="col-lg-6 text-center">
                            <div class="hero-mockup">
                                <img src="https://images.unsplash.com/photo-1556909114-f6e7ad7d3133?w=500" alt="Farm" class="img-fluid rounded-5 shadow-lg">
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>

        <!-- MARKETPLACE PAGE -->
        <div id="marketplace" class="page-content">
            <div class="container-fluid py-5 bg-light min-vh-100">
                <div class="row mb-5">
                    <div class="col-12 text-center">
                        <h1 class="display-4 fw-bold mb-3">
                            <i class="fas fa-store text-success me-3"></i>Live Marketplace
                        </h1>
                        <button onclick="showPage('home')" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back Home
                        </button>
                    </div>
                </div>
                <div class="row g-4" id="crop-grid">
                    <!-- Crops loaded by AJAX -->
                    <div class="col-12 text-center py-5">
                        <div class="spinner-border text-success" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Loading fresh produce...</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- LOGIN PAGE -->
        <div id="login" class="page-content">
            <div class="container">
                <div class="row justify-content-center min-vh-100 align-items-center">
                    <div class="col-md-6 col-lg-4">
                        <div class="card glass-card shadow-lg">
                            <div class="card-body p-5">
                                <div class="text-center mb-5">
                                    <i class="fas fa-seedling fa-4x text-success mb-3"></i>
                                    <h3 class="fw-bold">Welcome Back</h3>
                                </div>
                                <form id="loginForm">
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Email</label>
                                        <input type="email" id="loginEmail" class="form-control" required>
                                    </div>
                                    <div class="mb-4">
                                        <label class="form-label fw-bold">Password</label>
                                        <input type="password" id="loginPassword" class="form-control" required>
                                    </div>
                                    <button type="submit" class="btn btn-success w-100 btn-lg">
                                        <i class="fas fa-sign-in-alt me-2"></i>Login
                                    </button>
                                </form>
                                <div class="text-center mt-4">
                                    <p>Don't have account? <a href="#" onclick="showPage('register')">Register</a></p>
                                </div>
                                <div id="loginMessage"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- DASHBOARD PAGES (Dynamic) -->
        <div id="farmer" class="page-content">Loading Farmer Dashboard...</div>
        <div id="buyer" class="page-content">Loading Buyer Dashboard...</div>
        <div id="admin" class="page-content">Loading Admin Dashboard...</div>
        <div id="register" class="page-content">Loading Register...</div>
    </div>

    <!-- Cart Sidebar -->
    <div class="cart-sidebar position-fixed end-0 top-50 translate-middle-y p-4" id="cartSidebar" style="display:none;z-index:9999;">
        <div class="card shadow-lg" style="width:380px;">
            <div class="card-header bg-success text-white d-flex justify-content-between">
                <h5><i class="fas fa-shopping-cart"></i> Cart</h5>
                <button class="btn-close btn-close-white" onclick="toggleCart()"></button>
            </div>
            <div class="card-body p-0" id="cartItems">
                <div class="p-4 text-center text-muted">Cart is empty</div>
            </div>
            <div class="card-footer bg-light">
                <div class="d-flex justify-content-between fs-5 fw-bold mb-3">
                    <span>Total:</span>
                    <span id="cartTotal">₹0</span>
                </div>
                <button class="btn btn-success w-100" onclick="checkout()">Checkout</button>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="assets/js/spa.js"></script>
</body>
</html>
