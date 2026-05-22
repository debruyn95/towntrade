<?php
session_start();
include 'config/database.php';

/*
|--------------------------------------------------------------------------
| CHECK LOGIN
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| CHECK CART
|--------------------------------------------------------------------------
*/

if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {

    header("Location: cart.php");
    exit();
}

/*
|--------------------------------------------------------------------------
| DELIVERY OPTIONS
|--------------------------------------------------------------------------
*/

$delivery_options = [
    "Standard Delivery" => 60,
    "Express Delivery" => 120,
    "Collection/Pickup" => 0
];

/*
|--------------------------------------------------------------------------
| PLACE ORDER
|--------------------------------------------------------------------------
*/

if (isset($_POST['place_order'])) {

    $user_id = $_SESSION['user_id'];

    $delivery_method = mysqli_real_escape_string(
        $conn,
        $_POST['delivery_method']
    );

    $delivery_fee =
        $delivery_options[$delivery_method] ?? 0;

    $products_total = 0;

    /*
    |--------------------------------------------------------------------------
    | CALCULATE PRODUCTS TOTAL
    |--------------------------------------------------------------------------
    */

    foreach ($_SESSION['cart'] as $product_id => $quantity) {

        $product_id = intval($product_id);
        $quantity = intval($quantity);

        $query = mysqli_query($conn, "
            SELECT *
            FROM products
            WHERE id = '$product_id'
            LIMIT 1
        ");

        if ($query && mysqli_num_rows($query) > 0) {

            $product = mysqli_fetch_assoc($query);

            $line_total =
                $product['price']
                * $quantity;

            $products_total += $line_total;
        }
    }

    /*
    |--------------------------------------------------------------------------
    | FINAL TOTAL
    |--------------------------------------------------------------------------
    */

    $final_total =
        $products_total
        + $delivery_fee;

    /*
    |--------------------------------------------------------------------------
    | INSERT ORDER
    |--------------------------------------------------------------------------
    */

    $insert_order = "
        INSERT INTO orders
        (
            user_id,
            total_amount,
            delivery_method,
            delivery_fee,
            order_status
        )
        VALUES
        (
            '$user_id',
            '$final_total',
            '$delivery_method',
            '$delivery_fee',
            'Placed'
        )
    ";

    $order_result = mysqli_query(
        $conn,
        $insert_order
    );

    /*
    |--------------------------------------------------------------------------
    | INSERT ORDER ITEMS
    |--------------------------------------------------------------------------
    */

    if ($order_result) {

        $order_id = mysqli_insert_id($conn);

        foreach ($_SESSION['cart'] as $product_id => $quantity) {

            $product_id = intval($product_id);
            $quantity = intval($quantity);

            $query = mysqli_query($conn, "
                SELECT *
                FROM products
                WHERE id = '$product_id'
                LIMIT 1
            ");

            if ($query && mysqli_num_rows($query) > 0) {

                $product = mysqli_fetch_assoc($query);

                $price = $product['price'];

                mysqli_query($conn, "
                    INSERT INTO order_items
                    (
                        order_id,
                        product_id,
                        quantity,
                        price
                    )
                    VALUES
                    (
                        '$order_id',
                        '$product_id',
                        '$quantity',
                        '$price'
                    )
                ");
            }
        }

        /*
        |--------------------------------------------------------------------------
        | CLEAR CART
        |--------------------------------------------------------------------------
        */

        unset($_SESSION['cart']);

        /*
        |--------------------------------------------------------------------------
        | REDIRECT
        |--------------------------------------------------------------------------
        */

        header("Location: order-success.php");
        exit();

    } else {

        die("Error placing order.");
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
    Checkout - TownTrade SA
</title>

<link
    rel="stylesheet"
    href="assets/css/style.css"
>

<style>

body{
    background:#f5f5f5;
    font-family:Arial,sans-serif;
}

.checkout-container{
    width:90%;
    max-width:1200px;
    margin:50px auto;
}

.checkout-title{
    font-size:48px;
    font-weight:bold;
    margin-bottom:10px;
    color:#111827;
}

.checkout-subtitle{
    color:#6b7280;
    margin-bottom:40px;
    font-size:18px;
}

.checkout-grid{
    display:flex;
    flex-direction:column;
    gap:25px;
}

.checkout-card{
    background:#fff;
    border-radius:15px;
    padding:25px;
    display:flex;
    gap:25px;
    align-items:center;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
}

.checkout-image{
    width:180px;
    height:180px;
    object-fit:contain;
    border-radius:10px;
    background:#f9fafb;
    padding:10px;
}

.checkout-content{
    flex:1;
}

.checkout-content h3{
    margin-bottom:10px;
    color:#111827;
}

.checkout-description{
    color:#6b7280;
    margin-bottom:15px;
    line-height:1.6;
}

.checkout-price{
    font-size:22px;
    color:#2563eb;
    font-weight:bold;
    margin-bottom:10px;
}

.checkout-quantity{
    margin-bottom:10px;
}

.checkout-subtotal{
    font-size:24px;
    color:#16a34a;
    font-weight:bold;
}

.summary-box{
    background:#fff;
    border-radius:15px;
    padding:30px;
    margin-top:35px;
    box-shadow:0 2px 10px rgba(0,0,0,0.08);
}

.delivery-box{
    background:#f9fafb;
    border:1px solid #ddd;
    border-radius:10px;
    padding:20px;
    margin-bottom:25px;
}

.delivery-option{
    margin-bottom:15px;
}

.summary-line{
    display:flex;
    justify-content:space-between;
    margin-bottom:18px;
    font-size:20px;
}

.final-total{
    font-size:32px;
    color:#16a34a;
    font-weight:bold;
}

.place-order-btn{
    width:100%;
    margin-top:30px;
    background:#2563eb;
    color:#fff;
    border:none;
    padding:18px;
    border-radius:10px;
    font-size:22px;
    font-weight:bold;
    cursor:pointer;
}

.place-order-btn:hover{
    opacity:0.95;
}

@media(max-width:768px){

    .checkout-card{
        flex-direction:column;
        text-align:center;
    }

    .checkout-image{
        width:100%;
        max-width:280px;
    }
}

</style>

</head>

<body>

<?php include('includes/header.php'); ?>

<div class="checkout-container">

    <h1 class="checkout-title">
        Checkout
    </h1>

    <p class="checkout-subtitle">
        Review your order before placing it.
    </p>

    <div class="checkout-grid">

    <?php

    $products_total = 0;

    foreach ($_SESSION['cart'] as $product_id => $quantity) {

        $product_id = intval($product_id);
        $quantity = intval($quantity);

        $query = mysqli_query($conn, "
            SELECT *
            FROM products
            WHERE id = '$product_id'
            LIMIT 1
        ");

        if ($query && mysqli_num_rows($query) > 0) {

            $product = mysqli_fetch_assoc($query);

            $subtotal =
                $product['price']
                * $quantity;

            $products_total += $subtotal;

            ?>

            <div class="checkout-card">

                <img
                    src="assets/images/products/<?php echo $product['product_image']; ?>"
                    class="checkout-image"
                >

                <div class="checkout-content">

                    <h3>
                        <?php echo $product['product_name']; ?>
                    </h3>

                    <p class="checkout-description">

                        <?php
                        echo substr(
                            $product['description'],
                            0,
                            120
                        );
                        ?>...

                    </p>

                    <div class="checkout-price">

                        Price:
                        R<?php echo number_format(
                            $product['price'],
                            2
                        ); ?>

                    </div>

                    <div class="checkout-quantity">

                        Quantity:
                        <?php echo $quantity; ?>

                    </div>

                    <div class="checkout-subtotal">

                        Subtotal:
                        R<?php echo number_format(
                            $subtotal,
                            2
                        ); ?>

                    </div>

                </div>

            </div>

            <?php
        }
    }

    ?>

    </div>

    <div class="summary-box">

        <form method="POST">

            <div class="delivery-box">

                <h2 style="margin-bottom:20px;">
                    Delivery Method
                </h2>

                <div class="delivery-option">

                    <input
                        type="radio"
                        name="delivery_method"
                        value="Standard Delivery"
                        checked
                    >

                    Standard Delivery — R60 (3-5 days)

                </div>

                <div class="delivery-option">

                    <input
                        type="radio"
                        name="delivery_method"
                        value="Express Delivery"
                    >

                    Express Delivery — R120 (1-2 days)

                </div>

                <div class="delivery-option">

                    <input
                        type="radio"
                        name="delivery_method"
                        value="Collection/Pickup"
                    >

                    Collection/Pickup — Free

                </div>

            </div>

            <div class="summary-line">

                <span>
                    Products Total
                </span>

                <span>

                    R<?php echo number_format(
                        $products_total,
                        2
                    ); ?>

                </span>

            </div>

            <div class="summary-line">

                <span>
                    Delivery Fee
                </span>

                <span id="deliveryAmount">
                    R60.00
                </span>

            </div>

            <hr style="margin:20px 0;">

            <div class="summary-line final-total">

                <span>
                    Final Total
                </span>

                <span id="grandTotal">

                    R<?php echo number_format(
                        $products_total + 60,
                        2
                    ); ?>

                </span>

            </div>

            <button
                type="submit"
                name="place_order"
                class="place-order-btn"
            >
                Place Order
            </button>

        </form>

    </div>

</div>

<script>

const productsTotal =
<?php echo $products_total; ?>;

const deliveryRadios =
document.querySelectorAll(
    'input[name="delivery_method"]'
);

const deliveryAmount =
document.getElementById('deliveryAmount');

const grandTotal =
document.getElementById('grandTotal');

deliveryRadios.forEach(radio => {

    radio.addEventListener('change', function(){

        let fee = 0;

        if(this.value === 'Standard Delivery'){
            fee = 60;
        }

        if(this.value === 'Express Delivery'){
            fee = 120;
        }

        if(this.value === 'Collection/Pickup'){
            fee = 0;
        }

        deliveryAmount.innerHTML =
            'R' + fee.toFixed(2);

        grandTotal.innerHTML =
            'R' + (productsTotal + fee).toFixed(2);

    });

});

</script>

<?php include('includes/footer.php'); ?>

</body>
</html>