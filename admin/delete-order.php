<?php
session_start();
include '../config/database.php';

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

/* DELETE ORDER ITEMS FIRST */
mysqli_query($conn, "
    DELETE FROM order_items
    WHERE order_id = '$order_id'
");

/* DELETE ORDER */
mysqli_query($conn, "
    DELETE FROM orders
    WHERE id = '$order_id'
");

header("Location: orders.php");
exit();
?>