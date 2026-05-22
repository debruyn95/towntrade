<?php
session_start();
include('config/database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$product_id = $_GET['id'];

$product_query = mysqli_query($conn, "
    SELECT * FROM products
    WHERE id = '$product_id'
    AND seller_id = '$user_id'
");

if(mysqli_num_rows($product_query) == 0) {
    die("Access denied.");
}

mysqli_query($conn, "
    DELETE FROM products
    WHERE id = '$product_id'
    AND seller_id = '$user_id'
");

header("Location: my-products.php");
exit();
?>