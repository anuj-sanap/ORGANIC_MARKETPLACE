<?php require '../config/database.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FarmFresh Organic - Premium Marketplace</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>
    <div class="hero-bg"></div>
    
    <!-- Premium Navbar -->
    <nav class="navbar navbar-expand-lg fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"><i class="fas fa-seedling me-2"></i>FarmFresh</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link px-3" href="#features">Features</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="#marketplace">Marketplace</a></li>
                    <li class="nav-item"><a class="nav-link px-3" href="buyer/marketplace.php"><i class="fas fa-shopping-cart"></i> Shop</a></li>
                    <li class="nav-item"><a class="nav-link px-3 btn btn-premium text-white ms-2" href="login.php">Login</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6 hero-content">
                    <h1 class="display-3 fw-bold mb-4">Fresh From <span class="text-warning">Farm</span> to <span class="text-warning">Table</span></h1>
                    <p class="lead mb-5">100% Organic • Direct from Farmers • No Middlemen • Guaranteed Freshness</p>
                    <div>
                        <a href="buyer/marketplace.php" class="btn btn-premium btn-lg me-3 mb-3">
                            <i class="fas fa-shopping-bag me-2"></i>Shop Now
                        </a>
                        <a href="register.php?role=farmer" class="btn btn-outline-light btn-lg mb-3">
                            <i class="fas fa-tractor me-2"></i>Start Selling
                        </a>
                    </div>
                </div>
                <div class="col-lg-6 text-center">
                    <img src="https://images.unsplash.com/photo-1600585154340-be6161a56a0c?w=500&h=500&fit=crop" 
                         alt="Organic Farm" class="img-fluid rounded-4 shadow-lg" style="max-height: 500px; object-fit: cover;">
                </div>
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-5">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="display-5 fw-bold mb-4">Why Choose <span class="text-success">FarmFresh?</span></h2>
                <p class="lead text-muted px-4">Premium quality, direct sourcing, unbeatable prices</p>
            </div>
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="floating-card">
                        <div class="text-success mb-4">
                            <i class="fas fa-seedling fa-3x mb-3"></i>
                        </div>
                        <h4 class="fw-bold mb-3">100% Organic Certified</h4>
                        <p class="text-muted">Every product verified with organic certification. Traceability from farm to table.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="floating-card">
                        <div class="text-success mb-4">
                            <i class="fas fa-handshake fa-3x mb-3"></i>
                        </div>
                        <h4 class="fw-bold mb-3">No Middlemen</h4>
                        <p class="text-muted">Farmers get 100% fair price. You get 50% savings. Direct connection.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="floating-card">
                        <div class="text-success mb-4">
                            <i class="fas fa-shipping-fast fa-3x mb-3"></i>
                        </div>
                        <h4 class="fw-bold mb-3">Lightning Fresh</h4>
                        <p class="text-muted">Harvested today, delivered tomorrow. Freshest produce guaranteed.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section class="py-5 bg-white">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-3 mb-4">
                    <div class="counter-container">
                        <h3 class="display-4 fw-bold text-success counter" data-target="5000">0</h3>
                        <p class="h5 text-muted">Happy Customers</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="counter-container">
                        <h3 class="display-4 fw-bold text-success counter" data-target="1000">0</h3>
                        <p class="h5 text-muted">Active Farmers</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="counter-container">
                        <h3 class="display-4 fw-bold text-success counter" data-target="10000">0</h3>
                        <p class="h5 text-muted">Products Sold</p>
                    </div>
                </div>
                <div class="col-md-3 mb-4">
                    <div class="counter-container">
                        <h3 class="display-4 fw-bold text-primary counter" data-target="50">0</h3>
                        <p class="h5 text-muted">Cities Served</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-5">
        <div class="container text-center">
            <div class="floating-card mx-auto" style="max-width: 600px;">
                <h2 class="display-5 fw-bold mb-4">Ready to Experience Freshness?</h2>
                <p class="lead text-muted mb-4">Join thousands of satisfied customers and farmers</p>
                <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                    <a href="buyer/marketplace.php" class="btn btn-premium btn-lg">
                        <i class="fas fa-shopping-cart me-2"></i>Start Shopping
                    </a>
                    <a href="register.php?role=farmer" class="btn btn-outline-success btn-lg">
                        <i class="fas fa-tractor me-2"></i>Become Seller
                    </a>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="../assets/js/script.js"></script>
</body>
</html>
