<?php
session_start();

require_once '../config/app.php';
require_once '../config/database.php';

/* CHECK ADMIN ACCESS */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

/* DELETE PRODUCT */
if (isset($_GET['delete'])) {

    $delete_id = (int) $_GET['delete'];

    mysqli_query(
        $conn,
        "DELETE FROM products WHERE id = '$delete_id'"
    );

    header("Location: products.php");
    exit();
}

/* GET PRODUCTS */
$sql = "SELECT products.*,
               categories.category_name
        FROM products
        LEFT JOIN categories
        ON products.category_id = categories.id
        ORDER BY products.id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Products - TownTrade SA</title>

    <link rel="icon"
          type="image/png"
          href="<?php echo BASE_URL; ?>/assets/images/logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>

        body {
            background: #f5f5f5;
        }

        .card-custom {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        }

        .table img {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 10px;
        }

    </style>

</head>

<body>

<?php include '../includes/navbar.php'; ?>

<div class="container my-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h1 class="fw-bold">
            Manage Products
        </h1>

        <a href="<?php echo BASE_URL; ?>/admin/dashboard.php"
           class="btn btn-dark">

           Back to Dashboard

        </a>

    </div>

    <div class="card card-custom p-4">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-dark">

                    <tr>

                        <th>ID</th>

                        <th>Image</th>

                        <th>Product</th>

                        <th>Category</th>

                        <th>Price</th>

                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>

                    <?php while ($product = mysqli_fetch_assoc($result)): ?>

                    <tr>

                        <td>
                            <?php echo $product['id']; ?>
                        </td>

                        <td>

                            <img src="<?php echo BASE_URL; ?>/assets/images/products/<?php echo $product['product_image']; ?>"
                                 alt="Product Image">

                        </td>

                        <td>

                            <strong>
                                <?php echo $product['product_name']; ?>
                            </strong>

                            <br>

                            <small class="text-muted">

                                <?php echo substr($product['description'], 0, 60); ?>...

                            </small>

                        </td>

                        <td>

                            <?php echo $product['category_name']; ?>

                        </td>

                        <td>

                            R<?php echo number_format($product['price'], 2); ?>

                        </td>

                        <td>

                            <a href="<?php echo BASE_URL; ?>/product-details.php?id=<?php echo $product['id']; ?>"
                               class="btn btn-primary btn-sm">

                               View

                            </a>

                            <a href="products.php?delete=<?php echo $product['id']; ?>"
                               class="btn btn-danger btn-sm"
                               onclick="return confirm('Delete this product?')">

                               Delete

                            </a>

                        </td>

                    </tr>

                    <?php endwhile; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>