<?php

error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

include '../config/database.php';

/* CHECK LOGIN */
if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit();
}

/* GET CATEGORIES */
$categories_query = mysqli_query(
    $conn,
    "SELECT * FROM categories ORDER BY category_name ASC"
);

if (!$categories_query) {

    die("CATEGORY ERROR: " . mysqli_error($conn));
}

/* ADD PRODUCT */
if (isset($_POST['add_product'])) {

    $seller_id = $_SESSION['user_id'];

    $product_name = mysqli_real_escape_string(
        $conn,
        $_POST['product_name']
    );

    $category_id = mysqli_real_escape_string(
        $conn,
        $_POST['category_id']
    );

    $description = mysqli_real_escape_string(
        $conn,
        $_POST['description']
    );

    $price = mysqli_real_escape_string(
        $conn,
        $_POST['price']
    );

	/* IMAGE VALIDATION */

	$image_name = $_FILES['product_image']['name'];
	$image_tmp = $_FILES['product_image']['tmp_name'];
	$image_size = $_FILES['product_image']['size'];

	$allowed_types = ['jpg', 'jpeg', 'png', 'webp'];

	$image_extension = strtolower(
    	pathinfo($image_name, PATHINFO_EXTENSION)
	);

	/* CHECK FILE TYPE */

	if (!in_array($image_extension, $allowed_types)) {

    	die("Only JPG, JPEG, PNG and WEBP files are allowed.");
	}

	/* CHECK FILE SIZE */

	if ($image_size > 2 * 1024 * 1024) {

    	die("Image size must be less than 2MB.");
	}

	/* CREATE UNIQUE IMAGE NAME */

	$new_image_name =
    	time() . "_" .
    	rand(1000,9999) . "." .
    	$image_extension;

	/* MOVE IMAGE */

	move_uploaded_file(
    	$image_tmp,
    	"../assets/images/products/" . $new_image_name
	);
	
    /* INSERT PRODUCT */
    $query = "INSERT INTO products
    (
        seller_id,
        category_id,
        product_name,
        description,
        price,
        product_image
    )
    VALUES
    (
        '$seller_id',
        '$category_id',
        '$product_name',
        '$description',
        '$price',
        '$new_image_name'
    )";

    $result = mysqli_query($conn, $query);

    if (!$result) {

        die("INSERT ERROR: " . mysqli_error($conn));
    }

    header("Location: ../my-products.php");
    exit();
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

    <title>Add Product - TownTrade SA</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

</head>

<body>

<!-- NAVBAR -->
<?php include '../includes/navbar.php'; ?>

<section class="add-product-section">

    <div class="container">

        <div class="form-container">

            <h1>Add Product</h1>

            <form
                method="POST"
                enctype="multipart/form-data"
            >

                <!-- PRODUCT NAME -->
                <div class="form-group">

                    <label>
                        Product Name
                    </label>

                    <input
                        type="text"
                        name="product_name"
                        required
                    >

                </div>

                <!-- CATEGORY -->
                <div class="form-group">

                    <label>
                        Category
                    </label>

                    <select
                        name="category_id"
                        required
                    >

                        <option value="">
                            Select Category
                        </option>

                        <?php
                        while ($category = mysqli_fetch_assoc($categories_query)) {
                        ?>

                            <option value="<?php echo $category['id']; ?>">

                                <?php echo $category['category_name']; ?>

                            </option>

                        <?php
                        }
                        ?>

                    </select>

                </div>

                <!-- DESCRIPTION -->
                <div class="form-group">

                    <label>
                        Description
                    </label>

                    <textarea
                        name="description"
                        rows="6"
                        required
                    ></textarea>

                </div>

                <!-- PRICE -->
                <div class="form-group">

                    <label>
                        Price
                    </label>

                    <input
                        type="number"
                        step="0.01"
                        name="price"
                        required
                    >

                </div>

                <!-- PRODUCT IMAGE -->
                <div class="form-group">

                    <label>
                        Product Image
                    </label>

                    <input
                        type="file"
                        name="product_image"
                        required
                    >

                </div>

                <!-- BUTTON -->
                <button
                    type="submit"
                    name="add_product"
                    class="btn-primary"
                >
                    Add Product
                </button>

            </form>

        </div>

    </div>

</section>

<!-- FOOTER -->
<?php include '../includes/footer.php'; ?>

</body>
</html>