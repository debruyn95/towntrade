<?php
session_start();

require_once 'config/app.php';
require_once 'config/database.php';

/* CREATE CART SESSION */
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/* ADD PRODUCT TO CART */
if (isset($_GET['id'])) {

    $product_id = (int) $_GET['id'];

    if (isset($_SESSION['cart'][$product_id])) {

        $_SESSION['cart'][$product_id]++;

    } else {

        $_SESSION['cart'][$product_id] = 1;
    }
}

/* INCREASE QUANTITY */
if (isset($_GET['increase'])) {

    $id = (int) $_GET['increase'];

    if (isset($_SESSION['cart'][$id])) {
        $_SESSION['cart'][$id]++;
    }
}

/* DECREASE QUANTITY */
if (isset($_GET['decrease'])) {

    $id = (int) $_GET['decrease'];

    if (isset($_SESSION['cart'][$id])) {

        $_SESSION['cart'][$id]--;

        if ($_SESSION['cart'][$id] <= 0) {
            unset($_SESSION['cart'][$id]);
        }
    }
}

/* REMOVE PRODUCT */
if (isset($_GET['remove'])) {

    $id = (int) $_GET['remove'];

    unset($_SESSION['cart'][$id]);
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Shopping Cart - TownTrade SA</title>

    <link rel="icon"
          type="image/png"
          href="<?php echo BASE_URL; ?>/assets/images/logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>

        body {
            background: #f5f5f5;
        }

        .page-header {
            background: linear-gradient(
                90deg,
                #1c2431,
                #0f1f63
            );

            color: white;
            padding: 60px 0;
        }

        .card-custom {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        }

        .product-image {
            height: 250px;
            object-fit: cover;
        }

    </style>

</head>

<body>

<?php include 'includes/navbar.php'; ?>

<!-- HEADER -->
<section class="page-header text-center">

    <div class="container">

        <h1 class="fw-bold display-5">
            Shopping Cart
        </h1>

        <p class="lead">
            Review your selected products
        </p>

    </div>

</section>

<!-- CART -->
<div class="container my-5">

    <?php if (count($_SESSION['cart']) > 0): ?>

        <div class="row g-4">

            <?php
            foreach ($_SESSION['cart'] as $product_id => $quantity):

                $sql = "SELECT * FROM products WHERE id = '$product_id'";

                $result = mysqli_query($conn, $sql);

                $product = mysqli_fetch_assoc($result);

                if (!$product) {
                    continue;
                }

                $subtotal = $product['price'] * $quantity;

                $total += $subtotal;
            ?>

            <div class="col-lg-4">

                <div class="card card-custom h-100">

                    <img src="<?php echo BASE_URL; ?>/assets/images/products/<?php echo $product['product_image']; ?>"
                         class="card-img-top product-image">

                    <div class="card-body d-flex flex-column">

                        <h5 class="card-title">
                            <?php echo $product['product_name']; ?>
                        </h5>

                        <p class="text-muted">
                            <?php echo substr($product['description'], 0, 100); ?>...
                        </p>

                        <h4 class="text-primary">
                            R<?php echo number_format($product['price'], 2); ?>
                        </h4>

                        <p class="mb-2">
                            Quantity:
                            <strong><?php echo $quantity; ?></strong>
                        </p>

                        <!-- QUANTITY BUTTONS -->
                        <div class="d-flex gap-2 mb-3">

                            <a href="cart.php?decrease=<?php echo $product['id']; ?>"
                               class="btn btn-outline-secondary">

                               −

                            </a>

                            <a href="cart.php?increase=<?php echo $product['id']; ?>"
                               class="btn btn-outline-secondary">

                               +

                            </a>

                        </div>

                        <h5 class="text-success mb-3">
                            Subtotal:
                            R<?php echo number_format($subtotal, 2); ?>
                        </h5>

                        <a href="cart.php?remove=<?php echo $product['id']; ?>"
                           class="btn btn-danger mt-auto">

                           Remove

                        </a>

                    </div>

                </div>

            </div>

            <?php endforeach; ?>

        </div>

        <!-- TOTAL -->
        <div class="card card-custom mt-5 p-4">

            <div class="d-flex justify-content-between align-items-center">

                <h3>
                    Total:
                </h3>

                <h3 class="text-success">
                    R<?php echo number_format($total, 2); ?>
                </h3>

            </div>

            <a href="checkout.php"
               class="btn btn-primary mt-3">

               Proceed to Checkout

            </a>

        </div>

    <?php else: ?>

        <div class="card card-custom p-5 text-center">

            <h3>
                Your cart is empty
            </h3>

            <p class="text-muted">
                Browse products and add items to your cart.
            </p>

            <a href="products.php"
               class="btn btn-primary">

               Browse Products

            </a>

        </div>

    <?php endif; ?>

</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>