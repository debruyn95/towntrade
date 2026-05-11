<?php
session_start();

require_once 'config/app.php';
require_once 'config/database.php';

/* FEATURED PRODUCTS */
$featured_products = mysqli_query(
    $conn,
    "SELECT products.*, categories.category_name
     FROM products
     LEFT JOIN categories
     ON products.category_id = categories.id
     ORDER BY products.id DESC
     LIMIT 6"
);

/* TOTAL COUNTS */
$product_count = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM products")
);

$user_count = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM users")
);

$order_count = mysqli_num_rows(
    mysqli_query($conn, "SELECT * FROM orders")
);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>TownTrade SA</title>

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
            padding: 100px 0;
        }

        .hero-title {
            font-size: 55px;
            font-weight: bold;
        }

        .hero-subtitle {
            font-size: 20px;
            opacity: 0.9;
        }

        .stats-card {
            border: none;
            border-radius: 18px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.08);
            transition: 0.3s ease;
        }

        .stats-card:hover {
            transform: translateY(-5px);
        }

        .stat-number {
            font-size: 42px;
            font-weight: bold;
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
            transform: translateY(-5px);
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

        <img src="<?php echo BASE_URL; ?>/assets/images/logo.png"
             alt="TownTrade SA Logo"
             style="height: 100px; width: auto;"
             class="mb-4">

        <h1 class="hero-title">
            Welcome to TownTrade SA
        </h1>

        <p class="hero-subtitle mt-3 mb-4">

            South Africa’s trusted online marketplace
            for buying and selling products safely.

        </p>

        <div class="d-flex justify-content-center gap-3 flex-wrap">

            <a href="<?php echo BASE_URL; ?>/products.php"
               class="btn btn-light btn-lg px-4">

               Browse Products

            </a>

            <a href="<?php echo BASE_URL; ?>/register.php"
               class="btn btn-outline-light btn-lg px-4">

               Join Now

            </a>

        </div>

    </div>

</section>

<!-- STATS -->
<section class="py-5">

    <div class="container">

        <div class="row g-4">

            <!-- PRODUCTS -->
            <div class="col-md-4">

                <div class="card stats-card text-center p-4 h-100">

                    <h5 class="mb-3">
                        Products Listed
                    </h5>

                    <div class="stat-number text-primary">

                        <?php echo $product_count; ?>

                    </div>

                </div>

            </div>

            <!-- USERS -->
            <div class="col-md-4">

                <div class="card stats-card text-center p-4 h-100">

                    <h5 class="mb-3">
                        Registered Users
                    </h5>

                    <div class="stat-number text-success">

                        <?php echo $user_count; ?>

                    </div>

                </div>

            </div>

            <!-- ORDERS -->
            <div class="col-md-4">

                <div class="card stats-card text-center p-4 h-100">

                    <h5 class="mb-3">
                        Orders Placed
                    </h5>

                    <div class="stat-number text-danger">

                        <?php echo $order_count; ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- FEATURED PRODUCTS -->
<section class="py-5">

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2 class="fw-bold">
                Featured Products
            </h2>

            <a href="<?php echo BASE_URL; ?>/products.php"
               class="btn btn-dark">

               View All

            </a>

        </div>

        <?php if(mysqli_num_rows($featured_products) > 0): ?>

            <div class="row g-4">

                <?php while($product = mysqli_fetch_assoc($featured_products)): ?>

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
                    No Products Available
                </h3>

                <p class="mb-0">
                    Products will appear here once added.
                </p>

            </div>

        <?php endif; ?>

    </div>

</section>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>