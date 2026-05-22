<?php
session_start();

include '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (isset($_GET['id'])) {

    $id = intval($_GET['id']);

    // Get product image first
    $query = "SELECT product_image FROM products WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {

        $product = mysqli_fetch_assoc($result);

        // Delete image from uploads folder
        if (!empty($product['product_image'])) {

            $image_path = "../assets/images/products/" . $product['product_image'];

            if (file_exists($image_path)) {
                unlink($image_path);
            }
        }

        // Delete product from database
        $delete_query = "DELETE FROM products WHERE id = $id";

        if (mysqli_query($conn, $delete_query)) {

            header("Location: products.php");
            exit();

        } else {

            echo "Error deleting product.";

        }

    } else {

        echo "Product not found.";

    }

} else {

    echo "Invalid request.";

}
?>