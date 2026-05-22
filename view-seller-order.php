<?php
session_start();
include('config/database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$seller_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    header("Location: seller-orders.php");
    exit();
}

$order_id = intval($_GET['id']);

/*
|--------------------------------------------------------------------------
| GET ORDER DETAILS
|--------------------------------------------------------------------------
*/

$orderQuery = mysqli_query($conn, "
    SELECT
        orders.*,
        users.fullname,
        users.email,
        users.phone,
        users.address_line,
        users.suburb,
        users.city,
        users.province,
        users.postal_code

    FROM orders

    INNER JOIN users
        ON orders.user_id = users.id

    WHERE orders.id = '$order_id'

    LIMIT 1
");

if (!$orderQuery || mysqli_num_rows($orderQuery) == 0) {

    header("Location: seller-orders.php");
    exit();
}

$order = mysqli_fetch_assoc($orderQuery);

/*
|--------------------------------------------------------------------------
| SECURITY CHECK
|--------------------------------------------------------------------------
*/

$securityCheck = mysqli_query($conn, "
    SELECT products.id

    FROM order_items

    INNER JOIN products
        ON order_items.product_id = products.id

    WHERE order_items.order_id = '$order_id'
    AND products.seller_id = '$seller_id'

    LIMIT 1
");

if (!$securityCheck || mysqli_num_rows($securityCheck) == 0) {

    header("Location: seller-orders.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| GET ORDER ITEMS
|--------------------------------------------------------------------------
*/

$itemsQuery = mysqli_query($conn, "
    SELECT
        order_items.*,
        products.product_name,
        products.product_image,
        products.price,
        categories.category_name

    FROM order_items

    INNER JOIN products
        ON order_items.product_id = products.id

    LEFT JOIN categories
        ON products.category_id = categories.id

    WHERE order_items.order_id = '$order_id'
    AND products.seller_id = '$seller_id'
");

/*
|--------------------------------------------------------------------------
| CALCULATE SELLER TOTAL
|--------------------------------------------------------------------------
*/

$sellerTotal = 0;

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Seller Order View - TownTrade SA
    </title>

    <link rel="stylesheet"
          href="assets/css/style.css">

    <style>

        .page-orders{
            background:#f5f5f5;
            min-height:100vh;
            padding-top:40px;
        }

        .container{
            width:90%;
            max-width:1100px;
            margin:50px auto;
        }

        .order-box{
            background:#fff;
            padding:30px;
            border-radius:10px;
            box-shadow:0 2px 10px rgba(0,0,0,0.08);
            margin-bottom:30px;
        }

        .top-flex{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:30px;
        }

        .back-btn{
            background:#111827;
            color:#fff;
            padding:10px 16px;
            border-radius:6px;
            text-decoration:none;
            font-weight:bold;
        }

        .info-grid{
            display:grid;
            grid-template-columns:1fr 1fr 1fr;
            gap:30px;
        }

        .status-badge{
            display:inline-block;
            padding:8px 14px;
            border-radius:20px;
            color:#fff;
            font-size:13px;
            font-weight:bold;
        }

        .Placed{
            background:#6b7280;
        }

        .Shipped{
            background:#2563eb;
        }

        .Delivered{
            background:#f59e0b;
        }

        .Completed{
            background:#16a34a;
        }

        .action-btn{
            display:inline-block;
            margin-top:15px;
            background:#2563eb;
            color:#fff;
            padding:10px 18px;
            border-radius:6px;
            text-decoration:none;
            font-weight:bold;
        }

        .complete-btn{
            background:#16a34a;
        }

        table{
            width:100%;
            border-collapse:collapse;
            margin-top:20px;
        }

        table thead{
            background:#1f2937;
            color:#fff;
        }

        table th,
        table td{
            padding:14px;
            border-bottom:1px solid #ddd;
        }

        .product-img{
            width:70px;
            height:70px;
            object-fit:cover;
            border-radius:6px;
        }

        .green{
            color:green;
            font-weight:bold;
        }

    </style>

</head>

<body class="page-orders">

<?php include('includes/navbar.php'); ?>

<div class="container">

    <div class="top-flex">

        <div>

            <h1>Order #<?php echo $order['id']; ?></h1>

            <p>
                Placed on:
                <?php echo $order['created_at']; ?>
            </p>

        </div>

        <a href="seller-orders.php"
           class="back-btn">
            Back to Orders
        </a>

    </div>

    <div class="order-box">

        <div class="info-grid">

            <div>

                <h3>Shipping Address</h3>

                <p>
                    <?php echo $order['address_line']; ?>
                </p>

                <p>
                    <?php echo $order['suburb']; ?>
                </p>

                <p>
                    <?php echo $order['city']; ?>,
                    <?php echo $order['province']; ?>
                </p>

                <p>
                    <?php echo $order['postal_code']; ?>
                </p>

                <p>
                    <strong>
                        Phone:
                    </strong>

                    <?php echo $order['phone']; ?>
                </p>

            </div>

            <div>

                <h3>Buyer Details</h3>

                <p>
                    <strong>Name:</strong>
                    <?php echo $order['fullname']; ?>
                </p>

                <p>
                    <strong>Email:</strong>
                    <?php echo $order['email']; ?>
                </p>

            </div>

            <div>

                <h3>Order Status</h3>

                <span class="status-badge <?php echo $order['order_status']; ?>">

                    <?php echo $order['order_status']; ?>

                </span>

                <br>

                <?php

                /*
                |--------------------------------------------------------------------------
                | SELLER ACTIONS
                |--------------------------------------------------------------------------
                */

                if($order['order_status'] == 'Placed'){

                    echo '
                    <a class="action-btn"
                       href="update-order-status.php?id='.$order['id'].'&status=Shipped">
                        Mark As Shipped
                    </a>
                    ';
                }

                if($order['order_status'] == 'Delivered'){

                    echo '
                    <a class="action-btn complete-btn"
                       href="update-order-status.php?id='.$order['id'].'&status=Completed">
                        Mark As Completed
                    </a>
                    ';
                }

                ?>

                <hr style="margin:20px 0;">

                <h3>Delivery Information</h3>

                <p>
                    <strong>Method:</strong>
                    <?php echo $order['delivery_method']; ?>
                </p>

                <p>
                    <strong>Delivery Fee:</strong>
                    R<?php echo number_format($order['delivery_fee'], 2); ?>
                </p>

            </div>

        </div>

    </div>

    <div class="order-box">

        <h2>Products Sold</h2>

        <table>

            <thead>

                <tr>

                    <th>Image</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Price</th>
                    <th>Qty</th>
                    <th>Total</th>

                </tr>

            </thead>

            <tbody>

            <?php

            while($item = mysqli_fetch_assoc($itemsQuery)){

                $lineTotal =
                    $item['price']
                    * $item['quantity'];

                $sellerTotal += $lineTotal;

                ?>

                <tr>

                    <td>

                        <img
                            src="assets/images/products/<?php echo $item['product_image']; ?>"
                            Class="product-img"
                            alt="<?php echo $item['product_name']; ?>"
                        >

                    </td>

                    <td>
                        <?php echo $item['product_name']; ?>
                    </td>

                    <td>
                        <?php echo $item['category_name']; ?>
                    </td>

                    <td>
                        R<?php echo number_format($item['price'], 2); ?>
                    </td>

                    <td>
                        <?php echo $item['quantity']; ?>
                    </td>

                    <td class="green">

                        R<?php echo number_format($lineTotal, 2); ?>

                    </td>

                </tr>

                <?php
            }

            ?>

            </tbody>

        </table>

        <div style="text-align:right; margin-top:20px;">

            <h2>

                Seller Total Earned:
                <span class="green">

                    R<?php echo number_format($sellerTotal, 2); ?>

                </span>

            </h2>

            <small>
                Delivery fees are excluded from seller earnings.
            </small>

        </div>

    </div>

</div>

<?php include('includes/footer.php'); ?>

</body>
</html>