<?php
session_start();
include('config/database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "
    SELECT *
    FROM orders
    WHERE user_id = '$user_id'
    ORDER BY created_at DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Orders - TownTrade SA</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        .page-orders{
            background:#f5f5f5;
            min-height:100vh;
            padding-top:40px;
        }

        .orders-wrapper{
            min-height:70vh;
            padding:60px 20px;
        }

        .orders-container{
            width:100%;
            max-width:1000px;
            margin:0 auto;
            background:#fff;
            padding:40px;
            border-radius:14px;
            box-shadow:0 2px 10px rgba(0,0,0,0.08);
        }

        .orders-container h1{
            margin-bottom:30px;
            color:#0f172a;
            font-size:42px;
        }

        table{
            width:100%;
            border-collapse:collapse;
        }

        table th{
            background:#1e293b;
            color:#fff;
            padding:16px;
            text-align:left;
        }

        table td{
            padding:18px 16px;
            border-bottom:1px solid #ddd;
        }

        .total-price{
            color:green;
            font-weight:bold;
        }

        .status-badge{
            display:inline-block;
            padding:7px 14px;
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

        .view-btn{
            background:#3b82f6;
            color:#fff;
            padding:10px 16px;
            text-decoration:none;
            border-radius:6px;
            font-size:13px;
            font-weight:bold;
            display:inline-block;
        }

        .view-btn:hover{
            background:#2563eb;
        }

        .no-orders{
            text-align:center;
            padding:40px;
            color:#777;
        }

        @media(max-width:768px){

            .orders-container{
                padding:20px;
            }

            table{
                display:block;
                overflow-x:auto;
            }

            .orders-container h1{
                font-size:32px;
            }
        }
    </style>
</head>
<body class="page-orders">

<?php include('includes/navbar.php'); ?>

<div class="orders-wrapper">

    <div class="orders-container">

        <h1>My Orders</h1>

        <table>

            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>

            <tbody>

            <?php
            if(mysqli_num_rows($query) > 0){

                while($row = mysqli_fetch_assoc($query)){

                    $status_class = strtolower($row['order_status']);
            ?>

                <tr>

                    <td>
                        #<?php echo $row['id']; ?>
                    </td>

                    <td class="total-price">
                        R<?php echo number_format($row['total_amount'], 2); ?>
                    </td>

                    <td>
                        <span class="status-badge <?php echo $status_class; ?>">
                            <?php echo ucfirst($row['order_status']); ?>
                        </span>
                    </td>

                    <td>
                        <?php echo $row['created_at']; ?>
                    </td>

                    <td>
                        <a class="view-btn"
                           href="view-my-order.php?id=<?php echo $row['id']; ?>">
                            View
                        </a>
                    </td>

                </tr>

            <?php
                }

            } else {
            ?>

                <tr>
                    <td colspan="5" class="no-orders">
                        No orders found.
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