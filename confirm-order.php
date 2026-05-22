<?php
session_start();
include('config/database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: my-orders.php");
    exit();
}

$order_id = intval($_GET['id']);
$user_id = $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| VERIFY ORDER BELONGS TO BUYER
|--------------------------------------------------------------------------
*/

$query = mysqli_query($conn, "
    SELECT *
    FROM orders
    WHERE id = '$order_id'
    AND user_id = '$user_id'
");

if (mysqli_num_rows($query) == 0) {
    header("Location: my-orders.php");
    exit();
}

$order = mysqli_fetch_assoc($query);

/*
|--------------------------------------------------------------------------
| ONLY ALLOW SHIPPED ORDERS
|--------------------------------------------------------------------------
*/

if ($order['order_status'] != 'Shipped') {
    header("Location: view-my-order.php?id=$order_id");
    exit();
}

/*
|--------------------------------------------------------------------------
| UPDATE ORDER STATUS
|--------------------------------------------------------------------------
*/

mysqli_query($conn, "
    UPDATE orders
    SET order_status = 'Completed'
    WHERE id = '$order_id'
");

header("Location: view-my-order.php?id=$order_id");
exit();
?>