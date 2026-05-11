```php
<?php
session_start();

require_once 'config/app.php';
require_once 'config/database.php';

$sql = "SELECT products.*, categories.category_name
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

    <title>Products - TownTrade SA</title>

    <link rel="icon"
          type="image/png"
          href="<?php echo BASE_URL; ?>/assets/images/logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>

        body {
            background: #f5f5f5;
        }

        .hero-section {
            background: linear-gradient(
                90deg,
                #1c2431,
                #0f1f63
            );

            color: white;
            padding: 70px 0;
        }

        .product-card {
            border: none;
            border-radius: 18px;
            overflow: hidden;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: 0.3s ease;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-6px);
        }

        .product-image {
            height: 250px;
            object-fit: cover;
        }

        .product-description {
            min-height: 70px;
        }

    </style>

</head>

<body>

<?php include 'includes/navbar.php'; ?>

<!-- HERO -->
<section class="hero-section text-center">

    <div class="container">

        <h1 class="display-4 fw-bold">
            Browse Products
        </h1>

        <p class="lead mt-3">
            Explore quality products from sellers across South Africa.
        </p>

    </div>

</section>

<!-- PRODUCTS -->
<div class="container py-5">

    <?php if(mysqli_num_rows($result) > 0): ?>

        <div class="row g-4">

            <?php while($product = mysqli_fetch_assoc($result)): ?>

                <div class="col-md-4">

                    <div class="card product-card">

                        <img src="<?php echo BASE_URL; ?>/assets/images/products/<?php echo $product['product_image']; ?>"
                             class="card-img-top product-image"
                             alt="<?php echo $product['product_name']; ?>">

                        <div class="card-body d-flex flex-column">

                            <span class="badge bg-dark mb-2 align-self-start">
                                <?php echo $product['category_name']; ?>
                            </span>

                            <h4 class="fw-bold">
                                <?php echo $product['product_name']; ?>
                            </h4>

                            <p class="text-muted product-description flex-grow-1">
                                <?php echo substr($product['description'], 0, 100); ?>...
                            </p>

                            <h3 class="text-primary fw-bold mb-3">
                                R<?php echo number_format($product['price'], 2); ?>
                            </h3>

                            <div class="d-grid gap-2">

                                <a href="<?php echo BASE_URL; ?>/product-details.php?id=<?php echo $product['id']; ?>"
                                   class="btn btn-primary rounded-3">

                                    View Product

                                </a>

                                <a href="<?php echo BASE_URL; ?>/cart.php?id=<?php echo $product['id']; ?>"
                                   class="btn btn-dark rounded-3">

                                    Add to Cart

                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    <?php else: ?>

        <div class="alert alert-info text-center p-5 rounded-4 shadow-sm">

            <h3 class="mb-3">
                No Products Found
            </h3>

            <p class="mb-0">
                There are currently no products available.
            </p>

        </div>

    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstra
```
