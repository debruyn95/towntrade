<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../config/app.php';
?>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">

        <!-- LOGO -->
        <a class="navbar-brand d-flex align-items-center fw-bold"
           href="<?php echo BASE_URL; ?>/index.php">

            <img src="<?php echo BASE_URL; ?>/assets/images/logo.png"
                 alt="TownTrade SA Logo"
                 style="height: 45px; width: auto; margin-right: 10px;">

            TownTrade SA
        </a>

        <!-- MOBILE TOGGLE -->
        <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">

            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- NAVIGATION -->
        <div class="collapse navbar-collapse" id="navbarNav">

            <!-- LEFT SIDE -->
            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link"
                       href="<?php echo BASE_URL; ?>/index.php">
                       Home
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link"
                       href="<?php echo BASE_URL; ?>/products.php">
                       Products
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link"
                       href="<?php echo BASE_URL; ?>/add-product.php">
                       Add Product
                    </a>
                </li>

            </ul>

            <!-- RIGHT SIDE -->
            <ul class="navbar-nav ms-auto">

                <?php if (isset($_SESSION['user_id'])): ?>

                    <li class="nav-item">
                        <a class="nav-link"
                           href="<?php echo BASE_URL; ?>/profile.php">
                           Profile
                        </a>
                    </li>

                    <?php if (
                        isset($_SESSION['role']) &&
                        $_SESSION['role'] === 'admin'
                    ): ?>

                        <li class="nav-item">
                            <a class="nav-link"
                               href="<?php echo BASE_URL; ?>/admin/dashboard.php">
                               Admin
                            </a>
                        </li>

                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="btn btn-danger btn-sm ms-lg-2"
                           href="<?php echo BASE_URL; ?>/logout.php">
                           Logout
                        </a>
                    </li>

                <?php else: ?>

                    <li class="nav-item">
                        <a class="nav-link"
                           href="<?php echo BASE_URL; ?>/login.php">
                           Login
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link"
                           href="<?php echo BASE_URL; ?>/register.php">
                           Register
                        </a>
                    </li>

                <?php endif; ?>

            </ul>

        </div>
    </div>
</nav>