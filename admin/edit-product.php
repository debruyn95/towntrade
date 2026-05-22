<?php
include('../config/database.php');

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check login
if (!isset($_SESSION['user_id'])) {
     header("Location: ../login.php");
    exit();
}

// Check product ID
if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit();
}

$product_id = (int)$_GET['id'];

// Fetch product
$user_id = $_SESSION['user_id'];
$is_admin = ($_SESSION['role'] == 'admin');

if ($is_admin) {

    $query = "
        SELECT products.*, categories.category_name 
        FROM products
        LEFT JOIN categories 
        ON products.category_id = categories.id
        WHERE products.id = $product_id
    ";

} else {

    $query = "
        SELECT products.*, categories.category_name 
        FROM products
        LEFT JOIN categories 
        ON products.category_id = categories.id
        WHERE products.id = $product_id
        AND products.seller_id = $user_id
    ";
}

$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("Product not found.");
}

$product = mysqli_fetch_assoc($result);

// Fetch categories
$categories_query = "SELECT * FROM categories ORDER BY category_name ASC";
$categories_result = mysqli_query($conn, $categories_query);

// Update product
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $product_name = mysqli_real_escape_string($conn, $_POST['product_name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = mysqli_real_escape_string($conn, $_POST['price']);
    $category_id = (int)$_POST['category_id'];

    $image_name = $product['product_image'];

    // Upload new image
    if (!empty($_FILES['product_image']['name'])) {

        $target_dir = "../assets/images/products/";
        $image_name = time() . "_" . basename($_FILES["product_image"]["name"]);
        $target_file = $target_dir . $image_name;

        move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file);
    }

    // Update query
    $update_query = "
        UPDATE products SET
            product_name = '$product_name',
            description = '$description',
            price = '$price',
            category_id = '$category_id',
            product_image = '$image_name'
        WHERE id = $product_id
    ";

if (mysqli_query($conn, $update_query)) {

    if ($_SESSION['role'] == 'admin') {

        header("Location: products.php");

    } else {

        header("Location: ../my-products.php");
    }

    exit();

} else {

    echo "Error updating product: " . mysqli_error($conn);
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product - TownTrade SA</title>

    <style>

        body{
            margin:0;
            font-family:Arial, sans-serif;
            background:#f4f4f4;
        }

        .container{
            width:90%;
            max-width:700px;
            margin:40px auto;
            background:#fff;
            padding:30px;
            border-radius:10px;
            box-shadow:0 0 10px rgba(0,0,0,0.1);
        }

        h1{
            margin-bottom:30px;
            color:#111827;
        }

        .form-group{
            margin-bottom:20px;
        }

        label{
            display:block;
            margin-bottom:8px;
            font-weight:bold;
        }

        input,
        textarea,
        select{
            width:100%;
            padding:12px;
            border:1px solid #ccc;
            border-radius:6px;
            font-size:15px;
            box-sizing:border-box;
        }

        textarea{
            resize:vertical;
            min-height:120px;
        }

        .product-image{
            width:120px;
            border-radius:8px;
            margin-top:10px;
        }

        .btn{
            display:inline-block;
            padding:12px 20px;
            border:none;
            border-radius:6px;
            cursor:pointer;
            text-decoration:none;
            font-size:15px;
        }

        .btn-primary{
            background:#2563eb;
            color:#fff;
        }

        .btn-secondary{
            background:#6b7280;
            color:#fff;
            margin-left:10px;
        }

    </style>
</head>
<body>

<?php include('../navbar.php'); ?>

<div class="container">

    <h1>Edit Product</h1>

    <form method="POST" enctype="multipart/form-data">

        <div class="form-group">
            <label>Product Name</label>

            <input
                type="text"
                name="product_name"
                value="<?php echo htmlspecialchars($product['product_name']); ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>Category</label>

            <select name="category_id" required>

                <?php while($category = mysqli_fetch_assoc($categories_result)): ?>

                    <option
                        value="<?php echo $category['id']; ?>"
                        <?php if($category['id'] == $product['category_id']) echo 'selected'; ?>
                    >
                        <?php echo htmlspecialchars($category['category_name']); ?>
                    </option>

                <?php endwhile; ?>

            </select>
        </div>

        <div class="form-group">
            <label>Description</label>

            <textarea
                name="description"
                required
            ><?php echo htmlspecialchars($product['description']); ?></textarea>
        </div>

        <div class="form-group">
            <label>Price</label>

            <input
                type="number"
                step="0.01"
                name="price"
                value="<?php echo $product['price']; ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>Current Image</label><br>

            <img
                src="../assets/images/products/<?php echo $product['product_image']; ?>"
                class="product-image"
            >
        </div>

        <div class="form-group">
            <label>Upload New Image</label>

            <input
                type="file"
                name="product_image"
            >
        </div>

        <button type="submit" class="btn btn-primary">
            Update Product
        </button>

<?php if ($_SESSION['role'] == 'admin') { ?>

    <a href="products.php" class="btn btn-secondary">
        Cancel
    </a>

<?php } else { ?>

    <a href="../my-products.php" class="btn btn-secondary">
        Cancel
    </a>

<?php } ?>
        </a>

    </form>

</div>

<?php include('../footer.php'); ?>

</body>
</html>