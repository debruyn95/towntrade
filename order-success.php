<?php
session_start();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order Successful - TownTrade SA</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>

        .success-container{
            min-height:70vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:40px;
        }

        .success-card{
            background:#fff;
            padding:50px;
            border-radius:15px;
            text-align:center;
            max-width:600px;
            width:100%;
            box-shadow:0 5px 20px rgba(0,0,0,0.1);
        }

        .success-icon{
            font-size:80px;
            color:#28a745;
            margin-bottom:20px;
        }

        .success-card h1{
            font-size:42px;
            margin-bottom:15px;
            color:#1c2b4a;
        }

        .success-card p{
            font-size:18px;
            color:#555;
            margin-bottom:30px;
        }

        .success-btn{
            display:inline-block;
            padding:14px 30px;
            background:#3b82f6;
            color:white;
            text-decoration:none;
            border-radius:10px;
            font-weight:bold;
            transition:0.3s;
        }

        .success-btn:hover{
            background:#2563eb;
        }

    </style>

</head>

<body>

<?php include 'includes/navbar.php'; ?>

<div class="success-container">

    <div class="success-card">

        <div class="success-icon">
            ✅
        </div>

        <h1>Order Placed!</h1>

        <p>
            Thank you for shopping with TownTrade SA.
            Your order has been placed successfully.
        </p>

        <a href="products.php" class="success-btn">
            Continue Shopping
        </a>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>