<?php
session_start();

require_once 'config/app.php';
include 'config/database.php';

/* FEATURED PRODUCTS */
$featured_products = mysqli_query(
    $conn,
    "SELECT * FROM products ORDER BY id DESC LIMIT 3"
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
          href="/assets/images/logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>
        body {
            background-color: #f5f5f5;
            font-family: Arial, sans-serif;
        }

        .hero {
            background: linear-gradient(to right, #1f2937, #0b1c48);
            color: white;
            padding: 100px 0;
        }

        .hero img {
            max-width: 100%;
        }

        .stats-box {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            transition: 0.3s;
        }

        .stats-box:hover {
            transform: translateY(-5px);
        }

        .product-card {
            border: none;
            border-radius: 15px;
            overflow: hidden;
            transition: 0.3s;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .product-card:hover {
            transform: translateY(-5px);
        }

        .product-card img {
            height: 250px;
            object-fit: cover;
        }

        .why-box {
            background: white;
            padding: 30px;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
            text-align: center;
        }
    </style>
</head>

<body>

<?php include 'includes/navbar.php'; ?>

<!-- HERO SECTION -->
<section class="hero">
    <div class="container">
        <div class="row align-items-center">

            <div class="col-md-6">
                <h1 class="display-2 fw-bold">
                    Buy & Sell Products Across South Africa
                </h1>

                <p class="lead mt-4">
                    TownTrade SA is a trusted consumer-to-consumer marketplace
                    where users can safely buy and sell products online.
                </p>

                <a href="products.php"
                   class="btn btn-primary btn-lg mt-3 px-5 py-3">
                    Browse Marketplace
                </a>
            </div>

            <div class="col-md-6 text-center">
                <img src="assets/images/logo.png"
                     alt="TownTrade SA Logo"
                     style="max-width: 350px;">
            </div>

        </div>
    </div>
</section>

<!-- STATS -->
<section class="py-5">
    <div class="container">
        <div class="row g-4">

            <div class="col-md-4">
                <div class="stats-box">
                    <h1 class="text-primary">
                        <?php echo $product_count; ?>
                    </h1>
                    <h3>Products Listed</h3>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stats-box">
                    <h1 class="text-success">
                        <?php echo $user_count; ?>
                    </h1>
                    <h3>Registered Users</h3>
                </div>
            </div>

            <div class="col-md-4">
                <div class="stats-box">
                    <h1 class="text-danger">
                        <?php echo $order_count; ?>
                    </h1>
                    <h3>Orders Processed</h3>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- FEATURED PRODUCTS -->
<section class="pb-5">
    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2>Featured Products</h2>

            <a href="products.php"
               class="btn btn-dark">
                View All
            </a>
        </div>

        <div class="row g-4">

            <?php while($product = mysqli_fetch_assoc($featured_products)) { ?>

                <div class="col-md-4">
                    <div class="card product-card h-100">

                        <img src="assets/images/products/<?php echo $product['product_image']; ?>"
                             class="card-img-top"
                             alt="<?php echo $product['product_name']; ?>">

                        <div class="card-body d-flex flex-column">

                            <h5 class="card-title">
                                <?php echo $product['product_name']; ?>
                            </h5>

                            <p class="card-text text-muted">
                                <?php echo substr($product['description'], 0, 100); ?>...
                            </p>

                            <h4 class="text-primary mt-auto">
                                R<?php echo $product['price']; ?>
                            </h4>

                            <a href="product-details.php?id=<?php echo $product['id']; ?>"
                               class="btn btn-primary mt-3">
                                View Product
                            </a>

                        </div>
                    </div>
                </div>

            <?php } ?>

        </div>
    </div>
</section>

<!-- WHY CHOOSE -->
<section class="pb-5">
    <div class="container">

        <h2 class="text-center mb-5">
            Why Choose TownTrade SA?
        </h2>

        <div class="row g-4">

            <div class="col-md-4">
                <div class="why-box">
                    <h3>Secure Trading</h3>

                    <p>
                        Buy and sell products safely with registered users.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="why-box">
                    <h3>Easy Listings</h3>

                    <p>
                        List products quickly and reach buyers nationwide.
                    </p>
                </div>
            </div>

            <div class="col-md-4">
                <div class="why-box">
                    <h3>Local Marketplace</h3>

                    <p>
                        Support local buying and selling across South Africa.
                    </p>
                </div>
            </div>

        </div>
    </div>
</section>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>