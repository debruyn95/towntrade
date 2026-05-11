<?php
session_start();

include 'config/database.php';

if(!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = $_GET['id'];

/* PRODUCT QUERY */
$sql = "SELECT products.*, categories.category_name
        FROM products
        JOIN categories
        ON products.category_id = categories.id
        WHERE products.id = '$product_id'";

$result = mysqli_query($conn, $sql);

$product = mysqli_fetch_assoc($result);

if(!$product) {
    echo "Product not found.";
    exit();
}

/* ADD REVIEW */
if(isset($_POST['submit_review'])) {

    if(isset($_SESSION['user_id'])) {

        $user_id = $_SESSION['user_id'];
        $rating = $_POST['rating'];
        $comment = $_POST['comment'];

        $insert_review = "INSERT INTO reviews
                          (product_id, user_id, rating, comment)
                          VALUES
                          ('$product_id', '$user_id', '$rating', '$comment')";

        mysqli_query($conn, $insert_review);
    }
}

/* GET REVIEWS */
$reviews = mysqli_query(
    $conn,
    "SELECT reviews.*, users.fullname
     FROM reviews
     JOIN users
     ON reviews.user_id = users.id
     WHERE product_id = '$product_id'
     ORDER BY reviews.id DESC"
);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>
        <?php echo $product['product_name']; ?>
    </title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>

<body>

<?php include 'includes/navbar.php'; ?>

<div class="container mt-5">

    <div class="row">

        <!-- PRODUCT IMAGE -->
        <div class="col-md-6">

            <img src="assets/images/products/<?php echo $product['product_image']; ?>"
                 class="img-fluid rounded shadow">

        </div>

        <!-- PRODUCT DETAILS -->
        <div class="col-md-6">

            <h1>
                <?php echo $product['product_name']; ?>
            </h1>

            <p class="text-muted">

                Category:
                <?php echo $product['category_name']; ?>

            </p>

            <h2 class="text-primary mb-4">

                R <?php echo $product['price']; ?>

            </h2>

            <p>

                <?php echo $product['description']; ?>

            </p>

            <a href="cart.php?id=<?php echo $product['id']; ?>"
               class="btn btn-success btn-lg w-100">

                Add to Cart

            </a>

        </div>

    </div>

    <!-- REVIEW SECTION -->
    <div class="mt-5">

        <h2 class="mb-4">
            Product Reviews
        </h2>

        <!-- REVIEW FORM -->
        <?php if(isset($_SESSION['user_id'])) { ?>

            <div class="card shadow mb-4">

                <div class="card-body">

                    <form method="POST">

                        <div class="mb-3">

                            <label>
                                Rating
                            </label>

                            <select name="rating"
                                    class="form-control"
                                    required>

                                <option value="">
                                    Select Rating
                                </option>

                                <option value="1">1 Star</option>
                                <option value="2">2 Stars</option>
                                <option value="3">3 Stars</option>
                                <option value="4">4 Stars</option>
                                <option value="5">5 Stars</option>

                            </select>

                        </div>

                        <div class="mb-3">

                            <label>
                                Comment
                            </label>

                            <textarea name="comment"
                                      class="form-control"
                                      rows="4"
                                      required></textarea>

                        </div>

                        <button type="submit"
                                name="submit_review"
                                class="btn btn-primary">

                            Submit Review

                        </button>

                    </form>

                </div>

            </div>

        <?php } else { ?>

            <div class="alert alert-warning">

                Please login to leave a review.

            </div>

        <?php } ?>

        <!-- DISPLAY REVIEWS -->
        <?php while($review = mysqli_fetch_assoc($reviews)) { ?>

            <div class="card shadow mb-3">

                <div class="card-body">

                    <h5>

                        <?php echo $review['fullname']; ?>

                    </h5>

                    <p class="text-warning">

                        Rating:
                        <?php echo $review['rating']; ?>/5

                    </p>

                    <p>

                        <?php echo $review['comment']; ?>

                    </p>

                </div>

            </div>

        <?php } ?>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>