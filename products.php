<?php
session_start();

require_once 'config/app.php';
include 'config/database.php';

$sql = "SELECT products.*, categories.category_name 
        FROM products 
        LEFT JOIN categories 
        ON products.category_id = categories.id
        ORDER BY products.id DESC";

$result = mysqli_query($conn, $sql);

$categories = mysqli_query($conn, "SELECT * FROM categories");
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Products - TownTrade SA</title>

    <link rel="icon"
          type="image/png"
          href="<?php echo BASE_URL; ?>/assets/images/logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">
</head>

<body style="background:#f5f5f5;">

<?php include 'includes/navbar.php'; ?>

<section class="py-5 text-white"
         style="background: linear-gradient(90deg,#1f2937,#0b1d5c);">

    <div class="container text-center">

        <h1 class="display-4 fw-bold">
            Browse Products
        </h1>

        <p class="lead">
            Explore products from sellers across South Africa.
        </p>

    </div>
</section>

<div class="container py-5">

    <div class="row">

        <?php while($product = mysqli_fetch_assoc($result)): ?>

            <div class="col-md-4 mb-4">

                <div class="card shadow-sm h-100 border-0">

                    <img src="<?php echo BASE_URL; ?>/assets/images/products/<?php echo $product['product_image']; ?>"
                         class="card-img-top"
                         style="height:250px; object-fit:cover;">

                    <div class="card-body">

                        <span class="badge bg-dark mb-2">
                            <?php echo $product['category_name']; ?>
                        </span>

                        <h4>
                            <?php echo $product['product_name']; ?>
                        </h4>

                        <p class="text-muted">
                            <?php echo substr($product['description'],0,100); ?>...
                        </p>

                        <h3 class="text-primary">
                            R<?php echo $product['price']; ?>
                        </h3>

                    </div>

                    <div class="card-footer bg-white border-0">

                        <a href="<?php echo BASE_URL; ?>/product-details.php?id=<?php echo $product['id']; ?>"
                           class="btn btn-primary w-100">
                            View Product
                        </a>

                    </div>

                </div>

            </div>

        <?php endwhile; ?>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>