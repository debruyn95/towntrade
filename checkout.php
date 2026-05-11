<?php
session_start();

include 'config/database.php';

if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if(!isset($_SESSION['cart']) || count($_SESSION['cart']) == 0) {
    header("Location: cart.php");
    exit();
}

$total = 0;

foreach($_SESSION['cart'] as $id) {

    $sql = "SELECT * FROM products WHERE id='$id'";
    $result = mysqli_query($conn, $sql);

    $product = mysqli_fetch_assoc($result);

    $total += $product['price'];
}

if(isset($_POST['checkout'])) {

    $user_id = $_SESSION['user_id'];

    $insert = "INSERT INTO orders(user_id, total_amount, order_status)
               VALUES('$user_id', '$total', 'Pending')";

    if(mysqli_query($conn, $insert)) {

        unset($_SESSION['cart']);

        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>

<nav class="navbar navbar-dark bg-dark">

    <div class="container">

        <a class="navbar-brand" href="index.php">
            TownTrade SA
        </a>

    </div>

</nav>

<div class="container mt-5">

<?php if(isset($success)) { ?>

    <div class="card shadow">

        <div class="card-body text-center">

            <h1 class="text-success">
                Order Placed Successfully!
            </h1>

            <p>
                Thank you for shopping with TownTrade SA.
            </p>

            <a href="products.php"
               class="btn btn-primary">

               Continue Shopping

            </a>

        </div>

    </div>

<?php } else { ?>

    <div class="card shadow">

        <div class="card-header bg-dark text-white">
            <h3>Checkout</h3>
        </div>

        <div class="card-body">

            <h4>
                Total Amount:
                <span class="text-success">
                    R <?php echo $total; ?>
                </span>
            </h4>

            <form method="POST">

                <button type="submit"
                        name="checkout"
                        class="btn btn-success btn-lg w-100 mt-4">

                    Confirm Order

                </button>

            </form>

        </div>

    </div>

<?php } ?>

</div>

</body>
</html>