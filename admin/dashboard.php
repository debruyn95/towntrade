<?php
session_start();

require_once '../config/app.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
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

        .card-box {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        }

    </style>

</head>

<body>

<?php include '../includes/navbar.php'; ?>

<div class="container my-5">

    <h1 class="fw-bold mb-4">
        Admin Dashboard
    </h1>

    <div class="row g-4">

        <!-- USERS -->
        <div class="col-lg-4">

            <div class="card card-box p-4 text-center">

                <h3 class="mb-3">
                    Manage Users
                </h3>

                <a href="<?php echo BASE_URL; ?>/admin/users.php"
                   class="btn btn-primary">

                   View Users

                </a>

            </div>

        </div>

        <!-- PRODUCTS -->
        <div class="col-lg-4">

            <div class="card card-box p-4 text-center">

                <h3 class="mb-3">
                    Manage Products
                </h3>

                <a href="<?php echo BASE_URL; ?>/admin/products.php"
                   class="btn btn-success">

                   View Products

                </a>

            </div>

        </div>

        <!-- ORDERS -->
        <div class="col-lg-4">

            <div class="card card-box p-4 text-center">

                <h3 class="mb-3">
                    Orders
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