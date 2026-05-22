<?php
session_start();
include 'config/database.php';

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = (int) $_GET['id'];

/* FETCH PRODUCT */
$product_query = mysqli_query($conn, "
    SELECT 
        products.*, 
        users.fullname,
        users.email,
        users.role,
        users.created_at,
        users.is_verified,
        users.verified_at,
        categories.category_name
    FROM products
    LEFT JOIN users 
        ON products.seller_id = users.id
    LEFT JOIN categories
        ON products.category_id = categories.id
    WHERE products.id = $product_id
");

if (!$product_query || mysqli_num_rows($product_query) == 0) {
    die("Product not found.");
}

$product = mysqli_fetch_assoc($product_query);

/* ADD REVIEW */
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_review'])) {

    if (!isset($_SESSION['user_id'])) {
        header("Location: login.php");
        exit();
    }

    $user_id = $_SESSION['user_id'];
    $rating = (int) $_POST['rating'];
    $comment = mysqli_real_escape_string($conn, $_POST['comment']);

    mysqli_query($conn, "
        INSERT INTO reviews (product_id, user_id, rating, comment)
        VALUES ('$product_id', '$user_id', '$rating', '$comment')
    ");

    header("Location: product-details.php?id=$product_id");
    exit();
}

/* FETCH REVIEWS */
$reviews_query = mysqli_query($conn, "
    SELECT reviews.*, users.fullname
    FROM reviews
    JOIN users ON reviews.user_id = users.id
    WHERE reviews.product_id = $product_id
    ORDER BY reviews.id DESC
");
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
        <?php echo htmlspecialchars($product['product_name']); ?> - TownTrade SA
    </title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>

        body{
            margin:0;
            background:#f4f4f4;
            font-family:Arial, sans-serif;
        }

        .container{
            width:90%;
            max-width:1200px;
            margin:auto;
        }

        .product-wrapper{
            display:grid;
            grid-template-columns:1fr 1fr;
            gap:50px;
            background:#fff;
            padding:40px;
            border-radius:20px;
            margin:40px 0;
            align-items:start;
        }

        .product-image{
            display:flex;
            align-items:center;
            justify-content:center;
            background:#f9fafb;
            border-radius:18px;
            padding:30px;
            min-height:650px;
            overflow:hidden;
        }

        .zoom-image{
            width:100%;
            height:600px;

            background-repeat:no-repeat;
            background-position:center;
            background-size:100%;

            border-radius:14px;

            transition:background-size 0.3s ease;

            cursor:zoom-in;
        }

        .product-info h1{
            font-size:52px;
            margin-bottom:20px;
            line-height:1.2;
            color:#111827;
        }

        .price{
            color:#3867f0;
            font-size:42px;
            font-weight:bold;
            margin:20px 0;
        }

        .badge{
            display:inline-block;
            background:#111827;
            color:#fff;
            padding:8px 14px;
            border-radius:6px;
            font-size:14px;
            margin-bottom:20px;
        }

        .product-info h3{
            margin-top:35px;
            margin-bottom:15px;
            font-size:30px;
            color:#111827;
        }

        .product-info p{
            line-height:1.8;
            color:#555;
            margin-bottom:15px;
        }

        .btn{
            display:block;
            width:100%;
            padding:16px;
            border:none;
            border-radius:10px;
            text-align:center;
            text-decoration:none;
            font-size:18px;
            cursor:pointer;
            margin-top:18px;
            transition:0.3s;
        }

        .btn-primary{
            background:#3867f0;
            color:#fff;
        }

        .btn-primary:hover{
            background:#1f4ed8;
        }

        .btn-secondary{
            background:#fff;
            border:1px solid #d1d5db;
            color:#111;
        }

        .btn-secondary:hover{
            background:#f3f4f6;
        }

        .reviews-section{
            background:#fff;
            padding:40px;
            border-radius:20px;
            margin-bottom:40px;
        }

        .review-card{
            border:1px solid #eee;
            padding:25px;
            border-radius:14px;
            margin-bottom:20px;
        }

        .stars{
            color:gold;
            font-size:20px;
            margin:10px 0;
        }

        textarea,
        select{
            width:100%;
            padding:14px;
            margin-top:10px;
            margin-bottom:20px;
            border-radius:10px;
            border:1px solid #ccc;
            font-size:15px;
        }

        @media(max-width:900px){

            .product-wrapper{
                grid-template-columns:1fr;
            }

            .product-info h1{
                font-size:38px;
            }

            .zoom-image{
                height:420px;
            }

            .product-image{
                min-height:auto;
            }

        }

    </style>
</head>

<body>

<?php include 'includes/navbar.php'; ?>

<div class="container">

    <div class="product-wrapper">

        <!-- PRODUCT IMAGE -->

        <div class="product-image">

            <?php
            $product_image = "assets/images/products/" . $product['product_image'];

            if (
                empty($product['product_image']) ||
                !file_exists($product_image)
            ) {
                $product_image = "assets/images/products/default-product.png";
            }
            ?>

            <div
                class="zoom-image"
                style="background-image:url('<?php echo $product_image; ?>');"
            ></div>

        </div>

        <!-- PRODUCT INFO -->

        <div class="product-info">

            <span class="badge">
                <?php echo htmlspecialchars($product['category_name']); ?>
            </span>

            <h1>
                <?php echo htmlspecialchars($product['product_name']); ?>
            </h1>

            <div class="price">
                R<?php echo number_format($product['price'], 2); ?>
            </div>

            <h3>Product Description</h3>

            <p>
                <?php echo nl2br(htmlspecialchars($product['description'])); ?>
            </p>

            <h3>Seller Information</h3>

            <p>
                <strong>Seller:</strong>
                <?php echo htmlspecialchars($product['fullname']); ?>
            </p>

            <p>
                <strong>Email:</strong>
                <?php echo htmlspecialchars($product['email']); ?>
            </p>

            <?php if ($product['is_verified'] == 1 || $product['role'] == 'admin'): ?>

                <p style="color:green; font-weight:bold;">

                    ✔ Verified Member Since

                    <?php

                    if ($product['role'] == 'admin') {

                        echo date(
                            "F Y",
                            strtotime($product['created_at'])
                        );

                    } else {

                        echo date(
                            "F Y",
                            strtotime($product['verified_at'])
                        );

                    }

                    ?>

                </p>

            <?php endif; ?>

            <a
                href="cart.php?add=<?php echo $product['id']; ?>"
                class="btn btn-primary"
            >
                Add to Cart
            </a>

            <a
                href="products.php"
                class="btn btn-secondary"
            >
                Back to Products
            </a>

        </div>

    </div>

    <!-- REVIEWS SECTION -->

    <div class="reviews-section">

        <h2>Customer Reviews</h2>

        <br>

        <?php if(mysqli_num_rows($reviews_query) > 0) { ?>

            <?php while($review = mysqli_fetch_assoc($reviews_query)) { ?>

                <div class="review-card">

                    <h3>
                        <?php echo htmlspecialchars($review['fullname']); ?>
                    </h3>

                    <div class="stars">

                        <?php

                        for($i = 1; $i <= 5; $i++) {

                            if($i <= $review['rating']) {
                                echo "★";
                            } else {
                                echo "☆";
                            }

                        }

                        ?>

                    </div>

                    <p>
                        <?php echo htmlspecialchars($review['comment']); ?>
                    </p>

                </div>

            <?php } ?>

        <?php } else { ?>

            <p>No reviews yet.</p>

        <?php } ?>

        <br>

        <?php if(isset($_SESSION['user_id'])) { ?>

            <div class="review-card">

                <h2>Leave a Review</h2>

                <form method="POST">

                    <label>Rating</label>

                    <select name="rating" required>

                        <option value="">
                            Select Rating
                        </option>

                        <option value="5">
                            5 Stars
                        </option>

                        <option value="4">
                            4 Stars
                        </option>

                        <option value="3">
                            3 Stars
                        </option>

                        <option value="2">
                            2 Stars
                        </option>

                        <option value="1">
                            1 Star
                        </option>

                    </select>

                    <label>Review</label>

                    <textarea
                        name="comment"
                        rows="5"
                        placeholder="Write your review..."
                        required
                    ></textarea>

                    <button
                        type="submit"
                        name="submit_review"
                        class="btn btn-primary"
                    >
                        Submit Review
                    </button>

                </form>

            </div>

        <?php } else { ?>

            <div class="review-card">

                <p>
                    Please
                    <a href="login.php">login</a>
                    to leave a review.
                </p>

            </div>

        <?php } ?>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

<script>

const zoomImage = document.querySelector(".zoom-image");

zoomImage.addEventListener("mousemove", function(e){

    const rect = zoomImage.getBoundingClientRect();

    const x = ((e.clientX - rect.left) / rect.width) * 100;
    const y = ((e.clientY - rect.top) / rect.height) * 100;

    zoomImage.style.backgroundPosition = `${x}% ${y}%`;
    zoomImage.style.backgroundSize = "140%";

});

zoomImage.addEventListener("mouseleave", function(){

    zoomImage.style.backgroundPosition = "center";
    zoomImage.style.backgroundSize = "100%";

});

</script>

</body>
</html>