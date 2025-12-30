<nav class="navbar navbar-expand-lg navbar-glass fixed-top">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand fw-bold fs-4 d-flex align-items-center" href="index.php">
            <i class="fas fa-seedling text-success me-2"></i><span>FarmFresh</span>
        </a>
        <div class="d-flex align-items-center">
            <?php if (isLoggedIn()): ?>
                <a class="me-3 nav-link d-inline-flex align-items-center" href="<?php echo getRole()=='buyer' ? 'buyer/marketplace.php' : (getRole()=='farmer' ? 'farmer/dashboard.php' : 'admin/dashboard.php'); ?>">
                    <i class="fas fa-user-circle me-1"></i><span class="d-none d-md-inline"><?php echo $_SESSION['username']; ?></span>
                </a>
                <a class="btn btn-outline-secondary btn-sm d-none d-md-inline" href="logout.php">Logout</a>
            <?php else: ?>
                <a class="btn btn-outline-light btn-sm me-2 d-none d-md-inline" href="login.php">Login</a>
                <a class="btn btn-success btn-sm d-none d-md-inline" href="register.php">Register</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<!-- Mobile bottom navigation (visible on small screens) -->
<div class="mobile-bottom-nav">
    <a href="index.php" class="<?php echo basename($_SERVER['PHP_SELF'])=='index.php' ? 'active' : ''; ?>">
        <i class="fas fa-home icon"></i>
        <small>Home</small>
    </a>
    <a href="buyer/marketplace.php" class="<?php echo strpos($_SERVER['REQUEST_URI'], 'marketplace') ? 'active' : ''; ?>">
        <i class="fas fa-store icon"></i>
        <small>Market</small>
    </a>
    <a href="buyer/cart.php">
        <i class="fas fa-shopping-cart icon"></i>
        <small>Cart</small>
    </a>
    <?php if (isLoggedIn()): ?>
        <a href="<?php echo getRole()=='buyer' ? 'buyer/dashboard.php' : (getRole()=='farmer' ? 'farmer/dashboard.php' : 'admin/dashboard.php'); ?>">
            <i class="fas fa-user icon"></i>
            <small>Profile</small>
        </a>
    <?php else: ?>
        <a href="login.php">
            <i class="fas fa-sign-in-alt icon"></i>
            <small>Login</small>
        </a>
    <?php endif; ?>
</div>

<!-- Floating action button for quick add (visible on mobile) -->
<a href="buyer/marketplace.php" class="fab d-md-none" title="Quick shop">
    <i class="fas fa-shopping-basket"></i>
</a>
