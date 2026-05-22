<?php
session_start();
include('config/database.php');

/*
|--------------------------------------------------------------------------
| CHECK LOGIN
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

/*
|--------------------------------------------------------------------------
| VALIDATE INPUT
|--------------------------------------------------------------------------
*/

if (!isset($_GET['id']) || !isset($_GET['status'])) {

    header("Location: my-orders.php");
    exit();
}

$order_id = intval($_GET['id']);

$new_status = mysqli_real_escape_string(
    $conn,
    $_GET['status']
);

/*
|--------------------------------------------------------------------------
| GET ORDER
|--------------------------------------------------------------------------
*/

$orderQuery = mysqli_query($conn, "
    SELECT *
    FROM orders
    WHERE id = '$order_id'
");

if (!$orderQuery || mysqli_num_rows($orderQuery) == 0) {

    header("Location: my-orders.php");
    exit();
}

$order = mysqli_fetch_assoc($orderQuery);

$current_status = $order['order_status'];
$buyer_id = $order['user_id'];

/*
|--------------------------------------------------------------------------
| CHECK IF USER IS SELLER
|--------------------------------------------------------------------------
*/

$sellerQuery = mysqli_query($conn, "
    SELECT products.seller_id
    FROM order_items

    INNER JOIN products
        ON order_items.product_id = products.id

    WHERE order_items.order_id = '$order_id'
    LIMIT 1
");

$isSeller = false;

if ($sellerQuery && mysqli_num_rows($sellerQuery) > 0) {

    $sellerData = mysqli_fetch_assoc($sellerQuery);

    if ($sellerData['seller_id'] == $user_id) {

        $isSeller = true;
    }
}

/*
|--------------------------------------------------------------------------
| CHECK IF USER IS BUYER
|--------------------------------------------------------------------------
*/

$isBuyer = ($buyer_id == $user_id);

/*
|--------------------------------------------------------------------------
| VALIDATE WORKFLOW
|--------------------------------------------------------------------------
|
| Placed -> Shipped -> Delivered -> Completed
|
*/

$allowed = false;

/*
|--------------------------------------------------------------------------
| SELLER ACTIONS
|--------------------------------------------------------------------------
*/

if ($isSeller) {

    /*
    |--------------------------------------------------------------------------
    | Placed -> Shipped
    |--------------------------------------------------------------------------
    */

    if (
        $current_status == 'Placed'
        && $new_status == 'Shipped'
    ) {

        $allowed = true;
    }

    /*
    |--------------------------------------------------------------------------
    | Delivered -> Completed
    |--------------------------------------------------------------------------
    */

    if (
        $current_status == 'Delivered'
        && $new_status == 'Completed'
    ) {

        $allowed = true;
    }
}

/*
|--------------------------------------------------------------------------
| BUYER ACTIONS
|--------------------------------------------------------------------------
*/

if ($isBuyer) {

    /*
    |--------------------------------------------------------------------------
    | Shipped -> Delivered
    |--------------------------------------------------------------------------
    */

    if (
        $current_status == 'Shipped'
        && $new_status == 'Delivered'
    ) {

        $allowed = true;
    }
}

/*
|--------------------------------------------------------------------------
| BLOCK INVALID ACCESS
|--------------------------------------------------------------------------
*/

if (!$allowed) {

    header("Location: my-orders.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| UPDATE STATUS
|--------------------------------------------------------------------------
*/

mysqli_query($conn, "
    UPDATE orders
    SET order_status = '$new_status'
    WHERE id = '$order_id'
");

/*
|--------------------------------------------------------------------------
| REDIRECT
|--------------------------------------------------------------------------
*/

if ($isSeller) {

    header("Location: view-seller-order.php?id=$order_id");

} else {

    header("Location: view-my-order.php?id=$order_id");
}

exit();
?>