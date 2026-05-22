<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

/* COUNTS */
$users_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM users"));
$products_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM products"));
$orders_count = mysqli_num_rows(mysqli_query($conn, "SELECT * FROM orders"));

/* TOTAL SALES */
$sales_query = mysqli_query($conn, "
    SELECT SUM(total_amount) AS total_sales
    FROM orders
");
$sales_data = mysqli_fetch_assoc($sales_query);
$total_sales = $sales_data['total_sales'] ?? 0;
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - TownTrade SA</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body style="background:#f4f4f4;">

<!-- NAVBAR -->
<?php include '../includes/navbar.php'; ?>

<!-- PAGE -->
<div class="container py-5">

    <h1 class="fw-bold mb-5">Admin Dashboard</h1>

    <!-- STATS -->
    <div class="row g-4 mb-5">

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-4">
                <h1 class="text-primary fw-bold">
                    <?php echo $users_count; ?>
                </h1>

                <h4>Registered Users</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-4">
                <h1 class="text-primary fw-bold">
                    <?php echo $products_count; ?>
                </h1>

                <h4>Total Products</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-4">
                <h1 class="text-primary fw-bold">
                    <?php echo $orders_count; ?>
                </h1>

                <h4>Total Orders</h4>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card border-0 shadow-sm text-center p-4">
                <h1 class="text-success fw-bold">
                    R<?php echo number_format($total_sales, 2); ?>
                </h1>

                <h4>Total Sales</h4>
            </div>
        </div>

    </div>

    <!-- ADMIN ACTIONS -->
    <div class="row g-4">

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-5">

                <h3 class="fw-bold mb-4">
                    Manage Users
                </h3>

                <a href="users.php" class="btn btn-dark">
                    View Users
                </a>

            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-5">

                <h3 class="fw-bold mb-4">
                    Manage Products
                </h3>

                <a href="products.php" class="btn btn-dark">
                    View Products
                </a>

            </div>
        </div>

        <div class="col-md-4">
            <div class="card border-0 shadow-sm text-center p-5">

                <h3 class="fw-bold mb-4">
                    Manage Orders
                </h3>

                <a href="orders.php" class="btn btn-dark">
                    View Orders
                </a>

            </div>
        </div>

    </div>

</div>

<!-- FOOTER -->
<footer class="bg-dark text-white pt-5 pb-3 mt-5">

    <div class="container">

        <div class="row">

            <!-- ABOUT -->
            <div class="col-md-4 mb-4">

                <h2 class="fw-bold">
                    TownTrade SA
                </h2>

                <p class="mt-3">
                    South Africa’s trusted online marketplace for buying and
                    selling products safely.
                </p>

            </div>

            <!-- LINKS -->
            <div class="col-md-4 mb-4">

                <h4 class="fw-bold">
                    Quick Links
                </h4>

                <ul class="list-unstyled mt-3">

                    <li class="mb-2">
                        <a href="../index.php" class="text-white text-decoration-none">
                            Home
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="../products.php" class="text-white text-decoration-none">
                            Products
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="../login.php" class="text-white text-decoration-none">
                            Login
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="../register.php" class="text-white text-decoration-none">
                            Register
                        </a>
                    </li>

                </ul>

            </div>

            <!-- CONTACT -->
            <div class="col-md-4 mb-4">

                <h4 class="fw-bold">
                    Contact
                </h4>

                <p class="mt-3">
                    Email:
                    <a href="mailto:support@towntradesa.co.za"
                       class="text-white">
                        support@towntradesa.co.za
                    </a>
                </p>

                <p>
                    Phone:
                    <a href="https://wa.me/27784515381"
                       target="_blank"
                       class="text-white">
                        +27 78 451 5381
                    </a>
                </p>

                <p>
                    Cape Town, South Africa
                </p>

            </div>

        </div>

        <hr class="border-secondary">

        <div class="text-center">
            © 2026 TownTrade SA | Built for ITECA3-12 Web Development Project
        </div>

    </div>

</footer>

</body>
</html>