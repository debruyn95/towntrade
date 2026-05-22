<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

$orders = mysqli_query($conn, "
    SELECT orders.*, users.fullname 
    FROM orders
    JOIN users ON orders.user_id = users.id
    ORDER BY orders.id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - TownTrade SA</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body style="background:#f4f4f4;">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">

        <a class="navbar-brand fw-bold d-flex align-items-center" href="../index.php">
            <img src="../assets/images/logo.png"
                 alt="TownTrade SA Logo"
                 style="height:45px; margin-right:10px;">
            TownTrade SA
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <!-- LEFT -->
            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../products.php">Products</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../add-product.php">Add Product</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../cart.php">Cart</a>
                </li>

            </ul>

            <!-- RIGHT -->
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="../profile.php">Profile</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active fw-bold text-warning" href="dashboard.php">
                        Admin
                    </a>
                </li>

                <li class="nav-item">
                    <a class="btn btn-danger btn-sm ms-2" href="../logout.php">
                        Logout
                    </a>
                </li>

            </ul>

        </div>
    </div>
</nav>

<!-- PAGE -->
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h1 class="fw-bold">Orders</h1>

        <a href="dashboard.php" class="btn btn-dark">
            Back to Dashboard
        </a>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th>Date</th>
							<th>Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php if (mysqli_num_rows($orders) > 0): ?>

                        <?php while ($order = mysqli_fetch_assoc($orders)): ?>

                            <tr>

                                <td>
                                    #<?php echo $order['id']; ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($order['fullname']); ?>
                                </td>

                                <td class="fw-bold text-success">
                                    R<?php echo number_format($order['total_amount'], 2); ?>
                                </td>

                                <td>

                                   <?php
									$status = $order['order_status'] ?? 'Pending';

									$badge = 'bg-secondary';

									if ($status == 'Pending') {
    									$badge = 'bg-warning text-dark';
									}

									if ($status == 'Shipped') {
    									$badge = 'bg-primary';
									}

									if ($status == 'Completed') {
    									$badge = 'bg-success';
									}

									if ($status == 'Cancelled') {
    									$badge = 'bg-danger';
									}
									?>

                                    <span class="badge <?php echo $badge; ?>">
                                        <?php echo $status; ?>
                                    </span>

                                </td>

                                <td>

    <a
        href="view-order.php?id=<?php echo $order['id']; ?>"
        class="btn btn-primary btn-sm"
    >
        View
    </a>

    <a
        href="delete-order.php?id=<?php echo $order['id']; ?>"
        class="btn btn-danger btn-sm"
        onclick="return confirm('Delete this order?')"
    >
        Delete
    </a>

</td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="6" class="text-center py-4">
                                No orders found.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

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

                <h2 class="fw-bold">TownTrade SA</h2>

                <p class="mt-3">
                    South Africa’s trusted online marketplace for buying and
                    selling products safely.
                </p>

            </div>

            <!-- LINKS -->
            <div class="col-md-4 mb-4">

                <h4 class="fw-bold">Quick Links</h4>

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

                <h4 class="fw-bold">Contact</h4>

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

                <p>Cape Town, South Africa</p>

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
