<?php
session_start();

require_once '../config/app.php';
require_once '../config/database.php';

/* CHECK ADMIN ACCESS */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

/* GET ORDERS */
$sql = "SELECT orders.*,
               users.fullname
        FROM orders
        LEFT JOIN users
        ON orders.user_id = users.id
        ORDER BY orders.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Orders - TownTrade SA</title>

    <link rel="icon"
          type="image/png"
          href="<?php echo BASE_URL; ?>/assets/images/logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>

        body {
            background: #f5f5f5;
        }

        .card-custom {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        }

    </style>

</head>

<body>

<?php include '../includes/navbar.php'; ?>

<div class="container my-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h1 class="fw-bold">
            Orders
        </h1>

        <a href="<?php echo BASE_URL; ?>/admin/dashboard.php"
           class="btn btn-dark">

           Back to Dashboard

        </a>

    </div>

    <div class="card card-custom p-4">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-dark">

                    <tr>

                        <th>Order ID</th>

                        <th>Customer</th>

                        <th>Total</th>

                        <th>Status</th>

                        <th>Date</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (mysqli_num_rows($result) > 0): ?>

                        <?php while ($order = mysqli_fetch_assoc($result)): ?>

                        <tr>

                            <td>
                                #<?php echo $order['id']; ?>
                            </td>

                            <td>
                                <?php echo $order['fullname']; ?>
                            </td>

                            <td>
                                R<?php echo number_format($order['total_amount'], 2); ?>
                            </td>

                            <td>

                                <span class="badge bg-success">

                                    <?php echo $order['status']; ?>

                                </span>

                            </td>

                            <td>

                                <?php echo $order['created_at']; ?>

                            </td>

                        </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="5" class="text-center">

                                No orders found.

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>