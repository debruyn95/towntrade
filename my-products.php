<?php
session_start();
include('config/database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$query = mysqli_query($conn, "
    SELECT * FROM products
    WHERE seller_id = '$user_id'
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Products - TownTrade SA</title>
    <link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<?php include('includes/navbar.php'); ?>

<section class="dashboard-section">
    <div class="container">

        <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
            <h1>My Products</h1>

            <a href="admin/add-product.php" class="btn btn-primary">
                Add Product
            </a>
        </div>

        <?php if(mysqli_num_rows($query) > 0) { ?>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Image</th>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>

                <?php while($product = mysqli_fetch_assoc($query)) { ?>

                    <tr>
                        <td>#<?php echo $product['id']; ?></td>

                        <td>
                            <img
                                src="assets/images/products/<?php echo trim($product['product_image']); ?>"
                                width="70"
                            >
                        </td>

                        <td>
                            <?php echo $product['product_name']; ?>
                        </td>

                        <td>
                            R<?php echo number_format($product['price'], 2); ?>
                        </td>

                        <td>

                            <div class="actions-buttons">

                                <a
                                    href="admin/edit-product.php?id=<?php echo $product['id']; ?>"
                                    class="btn btn-primary"
                                >
                                    Edit
                                </a>

                                <a
                                    href="delete-my-product.php?id=<?php echo $product['id']; ?>"
                                    class="btn btn-danger"
                                    onclick="return confirm('Delete this product?')"
                                >
                                    Delete
                                </a>

                            </div>

                        </td>
                    </tr>

                <?php } ?>

                </tbody>
            </table>
        </div>

        <?php } else { ?>

        <div class="empty-products">
            <h2>No Products Yet</h2>

            <p>
                You have not added any products yet.
            </p>

            <a href="admin/add-product.php" class="btn btn-primary">
                Add Your First Product
            </a>
        </div>

        <?php } ?>

    </div>
</section>

<?php include('includes/footer.php'); ?>

</body>
</html>