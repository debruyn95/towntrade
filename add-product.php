<?php
session_start();

require_once 'config/app.php';
require_once 'config/database.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$message = "";

/* ADD PRODUCT */
if (isset($_POST['add_product'])) {

    $product_name = mysqli_real_escape_string(
        $conn,
        $_POST['product_name']
    );

    $description = mysqli_real_escape_string(
        $conn,
        $_POST['description']
    );

    $price = mysqli_real_escape_string(
        $conn,
        $_POST['price']
    );

    $category_id = mysqli_real_escape_string(
        $conn,
        $_POST['category_id']
    );

    $seller_id = $_SESSION['user_id'];

    /* IMAGE UPLOAD */
    $image_name = $_FILES['product_image']['name'];
    $temp_name = $_FILES['product_image']['tmp_name'];

    move_uploaded_file(
        $temp_name,
        "assets/images/products/" . $image_name
    );

    /* INSERT PRODUCT */
    $sql = "INSERT INTO products
            (seller_id, category_id, product_name, description, price, product_image)
            VALUES
            (
                '$seller_id',
                '$category_id',
                '$product_name',
                '$description',
                '$price',
                '$image_name'
            )";

    if (mysqli_query($conn, $sql)) {
        $message = "Product added successfully!";
    } else {
        $message = "Error: " . mysqli_error($conn);
    }
}

/* GET CATEGORIES */
$categories = mysqli_query(
    $conn,
    "SELECT * FROM categories"
);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Add Product - TownTrade SA</title>

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

        .btn-primary {
            background: #3b6df6;
            border: none;
        }

        .btn-primary:hover {
            background: #2954cc;
        }

    </style>

</head>

<body>

<?php include 'includes/navbar.php'; ?>

<!-- HERO -->
<section class="page-header text-center">

    <div class="container">

        <h1 class="fw-bold display-5">
            Add New Product
        </h1>

        <p class="lead">
            List your product on TownTrade SA
        </p>

    </div>

</section>

<!-- FORM -->
<div class="container my-5">

    <div class="row justify-content-center">

        <div class="col-lg-8">

            <div class="card card-custom p-4">

                <h3 class="mb-4">
                    Product Information
                </h3>

                <?php if ($message != ""): ?>

                    <div class="alert alert-info">
                        <?php echo $message; ?>
                    </div>

                <?php endif; ?>

                <form method="POST"
                      enctype="multipart/form-data">

                    <!-- PRODUCT NAME -->
                    <div class="mb-3">

                        <label class="form-label">
                            Product Name
                        </label>

                        <input type="text"
                               name="product_name"
                               class="form-control"
                               required>

                    </div>

                    <!-- CATEGORY -->
                    <div class="mb-3">

                        <label class="form-label">
                            Category
                        </label>

                        <select name="category_id"
                                class="form-select"
                                required>

                            <option value="">
                                Select Category
                            </option>

                            <?php while ($cat = mysqli_fetch_assoc($categories)): ?>

                                <option value="<?php echo $cat['id']; ?>">

                                    <?php echo $cat['category_name']; ?>

                                </option>

                            <?php endwhile; ?>

                        </select>

                    </div>

                    <!-- DESCRIPTION -->
                    <div class="mb-3">

                        <label class="form-label">
                            Description
                        </label>

                        <textarea name="description"
                                  class="form-control"
                                  rows="5"
                                  required></textarea>

                    </div>

                    <!-- PRICE -->
                    <div class="mb-3">

                        <label class="form-label">
                            Price (R)
                        </label>

                        <input type="number"
                               step="0.01"
                               name="price"
                               class="form-control"
                               required>

                    </div>

                    <!-- IMAGE -->
                    <div class="mb-4">

                        <label class="form-label">
                            Product Image
                        </label>

                        <input type="file"
                               name="product_image"
                               class="form-control"
                               accept="image/*"
                               required>

                    </div>

                    <!-- BUTTON -->
                    <button type="submit"
                            name="add_product"
                            class="btn btn-primary w-100">

                        Add Product

                    </button>

                </form>

            </div>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>