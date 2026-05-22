<?php
session_start();
include 'config/database.php';

/* =========================================
   GET FILTER VALUES
========================================= */

$search = isset($_GET['search'])
    ? trim($_GET['search'])
    : '';

$category = isset($_GET['category'])
    ? intval($_GET['category'])
    : 0;

/* =========================================
   LOAD CATEGORIES
========================================= */

$categoriesQuery = mysqli_query(
    $conn,
    "SELECT * FROM categories ORDER BY category_name ASC"
);

/* =========================================
   BUILD PRODUCTS QUERY
========================================= */

$query = "
    SELECT
        products.*,
        categories.category_name
    FROM products
    LEFT JOIN categories
        ON products.category_id = categories.id
    WHERE 1
";

/* CATEGORY FILTER */
if ($category > 0) {

    $query .= "
        AND products.category_id = '$category'
    ";
}

/* SEARCH FILTER */
if (!empty($search)) {

    $searchSafe = mysqli_real_escape_string(
        $conn,
        $search
    );

    $query .= "
        AND (
            products.product_name LIKE '%$searchSafe%'
            OR products.description LIKE '%$searchSafe%'
            OR categories.category_name LIKE '%$searchSafe%'
        )
    ";
}

/* ORDER */
$query .= "
    ORDER BY products.id DESC
";

/* RUN QUERY */
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Products - TownTrade SA</title>

    <link
        rel="stylesheet"
        href="assets/css/style.css"
    >

</head>

<body>

<?php include 'includes/navbar.php'; ?>

<!-- PAGE BANNER -->
<section class="page-banner">

    <div class="container">

        <h1>Products</h1>

        <p>
            Browse products available on TownTrade SA.
        </p>

    </div>

</section>

<!-- FILTER SECTION -->
<section class="products-filter-section">

    <div class="container">

        <form
            method="GET"
            action="products.php"
            class="products-filter-form"
        >

            <!-- SEARCH -->
            <input
                type="text"
                name="search"
                placeholder="Search products..."
                value="<?php echo htmlspecialchars($search); ?>"
                class="search-input"
            >

            <!-- CATEGORY -->
            <select
                name="category"
                class="category-select"
            >

                <option value="0">
                    Show All Categories
                </option>

                <?php
                while ($cat = mysqli_fetch_assoc($categoriesQuery)) {
                ?>

                    <option
                        value="<?php echo $cat['id']; ?>"
                        <?php
                        if ($category == $cat['id']) {
                            echo 'selected';
                        }
                        ?>
                    >
                        <?php echo $cat['category_name']; ?>
                    </option>

                <?php } ?>

            </select>

            <!-- SEARCH BUTTON -->
            <button
                type="submit"
                class="btn-primary"
            >
                Search
            </button>

            <!-- RESET -->
            <a
                href="products.php"
                class="btn-secondary"
            >
                Reset
            </a>

        </form>

    </div>

</section>

<!-- PRODUCTS SECTION -->
<section class="products-section">

    <div class="container">

        <?php
        if ($result && mysqli_num_rows($result) > 0) {
        ?>

            <div class="products-grid">

                <?php
                while ($product = mysqli_fetch_assoc($result)) {
                ?>

                    <div class="product-card">

                        <!-- PRODUCT IMAGE -->
                        <img
                            src="assets/images/products/<?php echo $product['product_image']; ?>"
                            alt="<?php echo $product['product_name']; ?>"
                            class="product-image"
                        >

                        <!-- PRODUCT INFO -->
                        <div class="product-info">

                            <!-- CATEGORY -->
                            <div class="product-category">

                                <?php
                                echo $product['category_name']
                                    ? $product['category_name']
                                    : 'Uncategorized';
                                ?>

                            </div>

                            <!-- PRODUCT NAME -->
                            <h3>
                                <?php echo $product['product_name']; ?>
                            </h3>

                            <!-- DESCRIPTION -->
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

                                R<?php
                                echo number_format(
                                    $product['price'],
                                    2
                                );
                                ?>

                            </div>

                            <!-- BUTTONS -->
                            <div class="product-buttons">

                                <a
                                    href="product-details.php?id=<?php echo $product['id']; ?>"
                                    class="btn-primary"
                                >
                                    View Product
                                </a>

                                <a
                                    href="cart.php?add=<?php echo $product['id']; ?>"
                                    class="btn-secondary"
                                >
                                    Add To Cart
                                </a>

                            </div>

                        </div>

                    </div>

                <?php } ?>

            </div>

        <?php
        } else {
        ?>

            <!-- NO RESULTS -->
            <div class="empty-products">

                <h2>
                    No products found
                </h2>

                <p>
                    No products found matching your search.
                </p>

                <a
                    href="products.php"
                    class="btn-primary"
                >
                    Back To Products
                </a>

            </div>

        <?php } ?>

    </div>

</section>

<?php include 'includes/footer.php'; ?>

</body>
</html>