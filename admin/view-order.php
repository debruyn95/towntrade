<?php
session_start();
include('../config/database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SESSION['role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit();
}

$order_id = intval($_GET['id']);

$order_query = mysqli_query($conn, "
    SELECT
        orders.*,
        users.fullname AS buyer_name,
        users.email,
        users.phone,
        users.address_line,
        users.suburb,
        users.city,
        users.province,
        users.postal_code
    FROM orders
    JOIN users ON orders.user_id = users.id
    WHERE orders.id = '$order_id'
");

$order = mysqli_fetch_assoc($order_query);

if (!$order) {
    header("Location: orders.php");
    exit();
}

$items_query = mysqli_query($conn, "
    SELECT
        order_items.*,
        products.product_name,
        products.product_image,
        products.seller_id,
        users.fullname AS seller_name
    FROM order_items

    JOIN products
        ON order_items.product_id = products.id

    JOIN users
        ON products.seller_id = users.id

    WHERE order_items.order_id = '$order_id'
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Order - TownTrade SA</title>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>
<body>

<?php include('../includes/navbar.php'); ?>

<section class="dashboard-section">
    <div class="container">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:30px;">

            <h1>
                Order #<?php echo $order['id']; ?>
            </h1>

            <a href="orders.php" class="btn btn-dark">
                Back To Orders
            </a>

        </div>

        <div class="card p-4 mb-4">

            <h3 class="mb-3">Order Information</h3>

            <p>
                <strong>Buyer:</strong>
                <?php echo $order['buyer_name']; ?>
            </p>

            <p>
                <strong>Status:</strong>
                <?php echo $order['order_status']; ?>
            </p>

            <p>
                <strong>Total:</strong>
                R<?php echo number_format($order['total_amount'], 2); ?>
            </p>

            <p>
                <strong>Placed:</strong>
                <?php echo $order['created_at']; ?>
            </p>

            <hr style="margin:20px 0;">

            <h4>Shipping Address</h4>

            <p>
                <strong>Phone:</strong>
                <?php echo !empty($order['phone']) ? $order['phone'] : 'Not Provided'; ?>
            </p>

            <p>
                <strong>Address:</strong><br>

                <?php echo !empty($order['address_line']) ? $order['address_line'] : ''; ?><br>

                <?php echo !empty($order['suburb']) ? $order['suburb'] : ''; ?><br>

                <?php echo !empty($order['city']) ? $order['city'] : ''; ?>,
                <?php echo !empty($order['province']) ? $order['province'] : ''; ?><br>

                <?php echo !empty($order['postal_code']) ? $order['postal_code'] : ''; ?>
            </p>

        </div>

        <div class="table-container">

            <table>

                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Seller</th>
                        <th>Quantity</th>
                        <th>Price</th>
                    </tr>
                </thead>

                <tbody>

                <?php while($item = mysqli_fetch_assoc($items_query)) { ?>

                    <tr>

                        <td>
                            <img
                                src="../assets/images/products/<?php echo $item['product_image']; ?>"
                                alt="Product Image"
                            >
                        </td>

                        <td>
                            <?php echo $item['product_name']; ?>
                        </td>

                        <td>
                            <?php echo $item['seller_name']; ?>
                        </td>

                        <td>
                            <?php echo $item['quantity']; ?>
                        </td>

                        <td class="order-price">
                            R<?php echo number_format($item['price'], 2); ?>
                        </td>

                    </tr>

                <?php } ?>

                </tbody>

            </table>

        </div>

    </div>
</section>

<?php include('../includes/footer.php'); ?>

</body>
</html>