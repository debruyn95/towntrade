<?php
session_start();
include('config/database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if (!isset($_GET['id'])) {
    header("Location: my-orders.php");
    exit();
}

$order_id = intval($_GET['id']);

$order_query = mysqli_query($conn, "
    SELECT
        orders.*,
        users.fullname,
        users.phone,
        users.address_line,
        users.suburb,
        users.city,
        users.province,
        users.postal_code,
        users.bank_name,
        users.account_holder,
        users.account_number,
        users.branch_code,
        users.account_type
    FROM orders

    INNER JOIN users
        ON orders.user_id = users.id

    WHERE orders.id = '$order_id'
    AND orders.user_id = '$user_id'
");

if (!$order_query || mysqli_num_rows($order_query) == 0) {
    header("Location: my-orders.php");
    exit();
}

$order = mysqli_fetch_assoc($order_query);

$items_query = mysqli_query($conn, "
    SELECT
        order_items.*,
        products.product_name,
        products.product_image,
        users.fullname AS seller_name,
        users.email AS seller_email,
        users.phone AS seller_phone
    FROM order_items

    INNER JOIN products
        ON order_items.product_id = products.id

    INNER JOIN users
        ON products.seller_id = users.id

    WHERE order_items.order_id = '$order_id'
");

$products_total =
    $order['total_amount']
    - $order['delivery_fee'];
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        Order #<?php echo $order_id; ?> - TownTrade SA
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

        .top-bar{
            display:flex;
            justify-content:space-between;
            align-items:center;
            margin-bottom:30px;
        }

        .back-btn{
            background:#1e293b;
            color:#fff;
            text-decoration:none;
            padding:12px 20px;
            border-radius:8px;
            font-weight:bold;
        }

        .card{
            background:#fff;
            padding:35px;
            border-radius:14px;
            box-shadow:0 2px 10px rgba(0,0,0,0.08);
            margin-bottom:30px;
        }

        .grid{
            display:grid;
            grid-template-columns:1fr 1fr 1fr;
            gap:30px;
        }

        .payment-grid{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:30px;
        }

        .status-badge{
            display:inline-block;
            padding:8px 14px;
            border-radius:20px;
            color:#fff;
            font-size:12px;
            font-weight:bold;
        }

        .placed{
            background:#6b7280;
        }

        .shipped{
            background:#2563eb;
        }

        .delivered{
            background:#f59e0b;
        }

        .completed{
            background:#16a34a;
        }

        .action-btn{
            display:inline-block;
            margin-top:15px;
            padding:12px 18px;
            background:#16a34a;
            color:#fff;
            text-decoration:none;
            border-radius:8px;
            font-weight:bold;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table th{
            background:#1e293b;
            color:#fff;
            padding:14px;
            text-align:left;
        }

        table td{
            padding:16px 14px;
            border-bottom:1px solid #ddd;
            vertical-align:middle;
        }

        .product-img{
            width:70px;
            height:70px;
            object-fit:cover;
            border-radius:8px;
        }

        .green{
            color:green;
            font-weight:bold;
        }

        .notice{
            background:#fef3c7;
            padding:15px;
            border-radius:8px;
            margin-top:20px;
            font-size:14px;
            line-height:1.7;
        }

        @media(max-width:768px){

            .grid{
                grid-template-columns:1fr;
            }

            .payment-grid{
                grid-template-columns:1fr;
            }

            table{
                display:block;
                overflow-x:auto;
            }
        }

    </style>

</head>

<body class="page-orders">

<?php include('includes/navbar.php'); ?>

<div class="container">

    <div class="top-bar">

        <div>

            <h1>
                Order #<?php echo $order_id; ?>
            </h1>

            <p>
                Placed on:
                <?php echo $order['created_at']; ?>
            </p>

        </div>

        <a href="my-orders.php"
           class="back-btn">
            Back to My Orders
        </a>

    </div>

    <div class="card">

        <div class="grid">

            <div>

                <h3>Shipping Address</h3>

                <p><?php echo $order['address_line']; ?></p>

                <p><?php echo $order['suburb']; ?></p>

                <p>
                    <?php echo $order['city']; ?>,
                    <?php echo $order['province']; ?>
                </p>

                <p><?php echo $order['postal_code']; ?></p>

                <p>
                    <strong>Phone:</strong>
                    <?php echo $order['phone']; ?>
                </p>

            </div>

            <div>

                <h3>Order Status</h3>

                <span class="status-badge <?php echo strtolower($order['order_status']); ?>">

                    <?php echo ucfirst($order['order_status']); ?>

                </span>

                <?php if($order['order_status'] == 'Shipped') { ?>

                    <br>

                    <a class="action-btn"
                       href="update-order-status.php?id=<?php echo $order['id']; ?>&status=Delivered">

                        Confirm Delivery

                    </a>

                <?php } ?>

            </div>

            <div>

                <h3>Order Summary</h3>

                <p>

                    <strong>Products Total:</strong>

                    R<?php echo number_format($products_total, 2); ?>

                </p>

                <p>

                    <strong>Delivery Method:</strong>

                    <?php echo $order['delivery_method']; ?>

                </p>

                <p>

                    <strong>Delivery Fee:</strong>

                    R<?php echo number_format($order['delivery_fee'], 2); ?>

                </p>

                <hr>

                <h2 class="green">

                    Final Total:
                    R<?php echo number_format($order['total_amount'], 2); ?>

                </h2>

            </div>

        </div>

    </div>

    <div class="card">

        <h2>EFT Payment Details</h2>

        <div class="payment-grid">

            <div>

                <p>

                    <strong>Bank Name:</strong><br>

                    <?php echo $order['bank_name']; ?>

                </p>

                <p>

                    <strong>Account Holder:</strong><br>

                    <?php echo $order['account_holder']; ?>

                </p>

                <p>

                    <strong>Account Number:</strong><br>

                    <?php echo $order['account_number']; ?>

                </p>

            </div>

            <div>

                <p>

                    <strong>Branch Code:</strong><br>

                    <?php echo $order['branch_code']; ?>

                </p>

                <p>

                    <strong>Account Type:</strong><br>

                    <?php echo $order['account_type']; ?>

                </p>

                <p>

                    <strong>Payment Reference:</strong><br>

                    <?php
                    echo $order['fullname'];
                    ?>
                    ORDER<?php echo $order_id; ?>

                </p>

            </div>

        </div>

        <div class="notice">

            <strong>IMPORTANT:</strong>

            Please use your Name,
            Surname and Order Number
            as the payment reference
            when making EFT payment.

            <br><br>

            <?php
            mysqli_data_seek($items_query, 0);
            $seller_info = mysqli_fetch_assoc($items_query);
            ?>

            <strong>Please send your POP to:</strong><br>

            <?php echo $seller_info['seller_email']; ?>

            <br><br>

            <strong>Seller Contact Number:</strong><br>

            <?php echo $seller_info['seller_phone']; ?>

        </div>

    </div>

    <div class="card">

        <h2>Ordered Products</h2>

        <table>

            <thead>

                <tr>

                    <th>Image</th>
                    <th>Product</th>
                    <th>Seller</th>
                    <th>Price</th>
                    <th>Quantity</th>
                    <th>Total</th>

                </tr>

            </thead>

            <tbody>

            <?php
            mysqli_data_seek($items_query, 0);

            while($item = mysqli_fetch_assoc($items_query)) {
            ?>

                <tr>

                    <td>

                        <img class="product-img"
                             src="assets/images/products/<?php echo $item['product_image']; ?>">

                    </td>

                    <td>

                        <?php echo $item['product_name']; ?>

                    </td>

                    <td>

                        <?php echo $item['seller_name']; ?>

                    </td>

                    <td>

                        R<?php echo number_format($item['price'], 2); ?>

                    </td>

                    <td>

                        <?php echo $item['quantity']; ?>

                    </td>

                    <td class="green">

                        R<?php echo number_format(
                            $item['price'] * $item['quantity'],
                            2
                        ); ?>

                    </td>

                </tr>

            <?php } ?>

            </tbody>

        </table>

    </div>

</div>

<?php include('includes/footer.php'); ?>

</body>
</html>