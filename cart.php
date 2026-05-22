<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'config/database.php';

/*
|--------------------------------------------------------------------------
| CREATE CART SESSION
|--------------------------------------------------------------------------
*/
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

/*
|--------------------------------------------------------------------------
| ADD PRODUCT TO CART
|--------------------------------------------------------------------------
*/
if (isset($_GET['add'])) {

    $product_id = (int)$_GET['add'];

    if (isset($_SESSION['cart'][$product_id])) {

        $_SESSION['cart'][$product_id]++;

    } else {

        $_SESSION['cart'][$product_id] = 1;
    }

    header("Location: cart.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| REMOVE PRODUCT
|--------------------------------------------------------------------------
*/
if (isset($_GET['remove'])) {

    $product_id = (int)$_GET['remove'];

    unset($_SESSION['cart'][$product_id]);

    header("Location: cart.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| INCREASE QUANTITY
|--------------------------------------------------------------------------
*/
if (isset($_GET['increase'])) {

    $product_id = (int)$_GET['increase'];

    $_SESSION['cart'][$product_id]++;

    header("Location: cart.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| DECREASE QUANTITY
|--------------------------------------------------------------------------
*/
if (isset($_GET['decrease'])) {

    $product_id = (int)$_GET['decrease'];

    if ($_SESSION['cart'][$product_id] > 1) {

        $_SESSION['cart'][$product_id]--;

    } else {

        unset($_SESSION['cart'][$product_id]);
    }

    header("Location: cart.php");
    exit();
}

$total = 0;
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Shopping Cart - TownTrade SA</title>

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >

</head>

<body>

<!-- NAVBAR -->
<?php include 'includes/navbar.php'; ?>

<!-- PAGE BANNER -->
<section class="page-banner">

    <div class="container">

        <h1>Shopping Cart</h1>

        <p>
            Review and manage your selected products.
        </p>

    </div>

</section>

<!-- CART SECTION -->
<section class="cart-section">

    <div class="container">

        <?php if (!empty($_SESSION['cart'])): ?>

            <div class="products-grid">

                <?php
                foreach ($_SESSION['cart'] as $product_id => $quantity):

                    $query = "SELECT * FROM products WHERE id = $product_id";

                    $result = mysqli_query($conn, $query);

                    if ($result && mysqli_num_rows($result) > 0):

                        $product = mysqli_fetch_assoc($result);

                        $subtotal = $product['price'] * $quantity;

                        $total += $subtotal;
                ?>

                <!-- PRODUCT CARD -->
                <div class="product-card">

                    <!-- PRODUCT IMAGE -->
                    <img
                        src="assets/images/products/<?php echo $product['product_image']; ?>"
                        alt="<?php echo $product['product_name']; ?>"
                        class="product-image"
                    >

                    <!-- PRODUCT INFO -->
                    <div class="product-info">

                        <h3>
                            <?php echo $product['product_name']; ?>
                        </h3>

                        <p>
                            <?php
                            echo substr(
                                $product['description'],
                                0,
                                100
                            );
                            ?>...
                        </p>

                        <!-- PRICE -->
                        <div class="product-price">

                            Price:
                            R<?php echo number_format($product['price'], 2); ?>

                        </div>

                        <!-- QUANTITY -->
                        <p>

                            Quantity:
                            <?php echo $quantity; ?>

                        </p>

                        <!-- SUBTOTAL -->
                        <div
                            class="product-price"
                            style="color: green;"
                        >

                            Subtotal:
                            R<?php echo number_format($subtotal, 2); ?>

                        </div>

                        <!-- QUANTITY BUTTONS -->
                        <div class="quantity-buttons">

                            <a
                                href="cart.php?decrease=<?php echo $product_id; ?>"
                                class="quantity-btn"
                            >
                                -
                            </a>

                            <a
                                href="cart.php?increase=<?php echo $product_id; ?>"
                                class="quantity-btn"
                            >
                                +
                            </a>

                        </div>

                        <!-- REMOVE BUTTON -->
                        <div style="margin-top: 15px;">

                            <a
                                href="cart.php?remove=<?php echo $product_id; ?>"
                                class="remove-btn"
                            >
                                Remove Product
                            </a>

                        </div>

                    </div>

                </div>

                <?php
                    endif;
                endforeach;
                ?>

            </div>

            <!-- CART TOTAL -->
            <div class="cart-total-box">

                <h2>
                    Cart Total
                </h2>

                <div class="cart-total">

                    R<?php echo number_format($total, 2); ?>

                </div>

                <a
                    href="checkout.php"
                    class="checkout-btn"
                >
                    Proceed to Checkout
                </a>

            </div>

        <?php else: ?>

            <!-- EMPTY CART -->
            <div class="empty-cart">

                <h2>
                    Your cart is empty.
                </h2>

                <a
                    href="products.php"
                    class="checkout-btn"
                >
                    Browse Products
                </a>

            </div>

        <?php endif; ?>

    </div>

</section>

<!-- FOOTER -->
<?php include 'includes/footer.php'; ?>

</body>
</html>