<?php
session_start();

include 'config/database.php';

/* FEATURED PRODUCTS */
$featured_products = mysqli_query(
    $conn,
    "SELECT products.*, categories.category_name
     FROM products
     LEFT JOIN categories
     ON products.category_id = categories.id
     ORDER BY products.id DESC
     LIMIT 3"
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
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>TownTrade SA</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>

        body{
            font-family: 'Poppins', sans-serif;
            background:#f5f5f5;
        }

        .hero{
            background: linear-gradient(90deg,#1a1f2b,#101d63);
            color:white;
            padding:120px 0;
        }

        .hero-title{
            font-size:4rem;
            font-weight:700;
            line-height:1.1;
        }

        .hero-text{
            font-size:1.2rem;
            margin-top:20px;
            color:#e0e0e0;
        }

        .stats-card{
            border:none;
            border-radius:20px;
            padding:40px;
            text-align:center;
            background:white;
            box-shadow:0 5px 20px rgba(0,0,0,0.08);
        }

        .product-card{
            border:none;
            border-radius:20px;
            overflow:hidden;
            background:white;
            transition:0.3s;
            box-shadow:0 5px 20px rgba(0,0,0,0.08);
        }

        .product-card:hover{
            transform:translateY(-5px);
        }

        .product-image{
            height:260px;
            object-fit:cover;
        }

        .section-title{
            font-size:2.3rem;
            font-weight:700;
        }

        .feature-box{
            background:white;
            border-radius:18px;
            padding:35px;
            text-align:center;
            box-shadow:0 5px 20px rgba(0,0,0,0.08);
        }

    </style>

</head>

<body>

<?php include 'includes/navbar.php'; ?>

<!-- HERO -->
<section class="hero">

    <div class="container">

        <div class="row align-items-center">

            <div class="col-lg-6">

                <h1 class="hero-title">
                    Buy & Sell<br>
                    Products Across<br>
                    South Africa
                </h1>

                <p class="hero-text">
                    TownTrade SA is a trusted consumer-to-consumer marketplace where
                    users can safely buy and sell products online.
                </p>

                <div class="mt-4">

                    <a href="products.php" class="btn btn-primary btn-lg px-5 py-3">
                        Browse Marketplace
                    </a>

                </div>

            </div>

            <div class="col-lg-6 text-center mt-5 mt-lg-0">

                <img
                    src="https://towntradesa.infinityfreeapp.com/assets/images/logo.png"
                    alt="TownTrade SA Logo"
                    class="img-fluid"
                    style="max-width:400px;"
                >

            </div>

        </div>

    </div>

</section>

<!-- STATS -->
<section class="py-5">

    <div class="container">

        <div class="row g-4">

            <div class="col-md-4">

                <div class="stats-card">

                    <h1 class="text-primary fw-bold">
                        <?php echo $product_count; ?>
                    </h1>

                    <h4>Products Listed</h4>

                </div>

            </div>

            <div class="col-md-4">

                <div class="stats-card">

                    <h1 class="text-success fw-bold">
                        <?php echo $user_count; ?>
                    </h1>

                    <h4>Registered Users</h4>

                </div>

            </div>

            <div class="col-md-4">

                <div class="stats-card">

                    <h1 class="text-danger fw-bold">
                        <?php echo $order_count; ?>
                    </h1>

                    <h4>Orders Processed</h4>

                </div>

            </div>

        </div>

    </div>

</section>

<!-- FEATURED PRODUCTS -->
<section class="pb-5">

    <div class="container">

        <div class="d-flex justify-content-between align-items-center mb-4">

            <h2 class="section-title">
                Featured Products
            </h2>

            <a href="products.php" class="btn btn-dark">
                View All
            </a>

        </div>

        <div class="row g-4">

            <?php while($product = mysqli_fetch_assoc($featured_products)) : ?>

                <div class="col-md-4">

                    <div class="product-card h-100">

                        <!-- IMAGE -->
                        <img
                            src="https://towntradesa.infinityfreeapp.com/assets/images/products/<?php echo $product['product_image']; ?>"
                            class="card-img-top product-image"
                            alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                        >

                        <div class="card-body d-flex flex-column">

                            <!-- CATEGORY -->
                            <span class="badge bg-dark mb-2">
                                <?php echo htmlspecialchars($product['category_name']); ?>
                            </span>

                            <!-- PRODUCT NAME -->
                            <h3 class="fw-bold">
                                <?php echo htmlspecialchars($product['product_name']); ?>
                            </h3>

                            <!-- DESCRIPTION -->
                            <p class="text-muted flex-grow-1">
                                <?php echo substr(htmlspecialchars($product['description']),0,100); ?>...
                            </p>

                            <!-- PRICE -->
                            <h2 class="text-primary fw-bold">
                                R<?php echo number_format($product['price'],2); ?>
                            </h2>

                            <!-- BUTTONS -->
                            <div class="d-grid gap-2 mt-3">

                                <a href="product-details.php?id=<?php echo $product['id']; ?>"
                                   class="btn btn-primary">
                                    View Product
                                </a>

                                <a href="cart.php?id=<?php echo $product['id']; ?>"
                                   class="btn btn-dark">
                                    Add to Cart
                                </a>

                            </div>

                        </div>

                    </div>

                </div>

            <?php endwhile; ?>

        </div>

    </div>

</section>

<!-- WHY CHOOSE -->
<section class="pb-5">

    <div class="container">

        <h2 class="section-title text-center mb-5">
            Why Choose TownTrade SA?
        </h2>

        <div class="row g-4">

            <div class="col-md-4">

                <div class="feature-box">

                    <h3>Secure Trading</h3>

                    <p class="text-muted">
                        Buy and sell products safely with registered users.
                    </p>

                </div>

            </div>

            <div class="col-md-4">

                <div class="feature-box">

                    <h3>Easy Listings</h3>

                    <p class="text-muted">
                        List products quickly and reach buyers nationwide.
                    </p>

                </div>

            </div>

            <div class="col-md-4">

                <div class="feature-box">

                    <h3>Local Marketplace</h3>

                    <p class="text-muted">
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
