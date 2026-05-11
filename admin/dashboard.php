<?php
session_start();

require_once '../config/app.php';
require_once '../config/database.php';

/* CHECK ADMIN ACCESS */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

/* TOTAL COUNTS */
$total_users = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM users")
);

$total_products = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM products")
);

$total_orders = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM orders")
);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Admin Dashboard - TownTrade SA</title>

    <link rel="icon"
          type="image/png"
          href="<?php echo BASE_URL; ?>/assets/images/logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>

        body {
            background: #f5f5f5;
        }

        .hero-section {
            background: linear-gradient(
                90deg,
                #1c2431,
                #0f1f63
            );

            color: white;
            padding: 70px 0;
        }

        .dashboard-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: 0.3s ease;
        }

        .dashboard-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 42px;
            font-weight: bold;
        }

    </style>

</head>

<body>

<?php include '../includes/navbar.php'; ?>

<!-- HERO -->
<section class="hero-section text-center">

    <div class="container">

        <h1 class="display-4 fw-bold">
            Admin Dashboard
        </h1>

        <p class="lead mt-3">
            Manage users, products, and orders for TownTrade SA.
        </p>

    </div>

</section>

<!-- DASHBOARD -->
<div class="container py-5">

    <!-- STATS -->
    <div class="row g-4 mb-5">

        <!-- USERS -->
        <div class="col-md-4">

            <div class="card dashboard-card text-center p-4 h-100">

                <h5 class="mb-3">
                    Total Users
                </h5>

                <div class="stat-number text-primary">

                    <?php echo $total_users; ?>

                </div>

            </div>

        </div>

        <!-- PRODUCTS -->
        <div class="col-md-4">

            <div class="card dashboard-card text-center p-4 h-100">

                <h5 class="mb-3">
                    Total Products
                </h5>

                <div class="stat-number text-success">

                    <?php echo $total_products; ?>

                </div>

            </div>

        </div>

        <!-- ORDERS -->
        <div class="col-md-4">

            <div class="card dashboard-card text-center p-4 h-100">

                <h5 class="mb-3">
                    Total Orders
                </h5>

                <div class="stat-number text-danger">

                    <?php echo $total_orders; ?>

                </div>

            </div>

        </div>

    </div>

    <!-- MANAGEMENT BUTTONS -->
    <div class="row g-4">

        <!-- USERS -->
        <div class="col-md-4">

            <div class="card dashboard-card text-center p-4 h-100">

                <h3 class="mb-4">
                    Manage Users
                </h3>

                <a href="<?php echo BASE_URL; ?>/admin/users.php"
                   class="btn btn-primary">

                   View Users

                </a>

            </div>

        </div>

        <!-- PRODUCTS -->
        <div class="col-md-4">

            <div class="card dashboard-card text-center p-4 h-100">

                <h3 class="mb-4">
                    Manage Products
                </h3>

                <a href="<?php echo BASE_URL; ?>/admin/products.php"
                   class="btn btn-success">

                   View Products

                </a>

            </div>

        </div>

        <!-- ORDERS -->
        <div class="col-md-4">

            <div class="card dashboard-card text-center p-4 h-100">

                <h3 class="mb-4">
                    Manage Orders
                </h3>

                <a href="<?php echo BASE_URL; ?>/admin/orders.php"
                   class="btn btn-dark">

                   View Orders

                </a>

            </div>

        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>