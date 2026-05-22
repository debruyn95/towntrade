
<?php
session_start();
include '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

/* DELETE PRODUCT */
if (isset($_GET['delete'])) {

    $delete_id = intval($_GET['delete']);

    mysqli_query($conn, "DELETE FROM products WHERE id = '$delete_id'");

    header("Location: products.php");
    exit();
}

/* GET PRODUCTS */
$products = mysqli_query($conn, "
    SELECT * FROM products
    ORDER BY id DESC
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Products - TownTrade SA</title>

    <!-- BOOTSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- CUSTOM CSS -->
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body style="background:#f4f4f4;">

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">

        <!-- LOGO -->
        <a class="navbar-brand fw-bold d-flex align-items-center" href="../index.php">
            <img src="../assets/images/logo.png"
                 alt="TownTrade SA Logo"
                 style="height:45px; margin-right:10px;">
            TownTrade SA
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <!-- LEFT -->
            <ul class="navbar-nav me-auto">

                <li class="nav-item">
                    <a class="nav-link" href="../index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../products.php">Products</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../add-product.php">Add Product</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../cart.php">Cart</a>
                </li>

            </ul>

            <!-- RIGHT -->
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="../profile.php">
                        Profile
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link active fw-bold text-warning"
                       href="dashboard.php">
                        Admin
                    </a>
                </li>

                <li class="nav-item">
                    <a class="btn btn-danger btn-sm ms-2"
                       href="../logout.php">
                        Logout
                    </a>
                </li>

            </ul>

        </div>
    </div>
</nav>

<!-- PAGE -->
<div class="container py-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h1 class="fw-bold">Manage Products</h1>

        <a href="dashboard.php" class="btn btn-dark">
            Back to Dashboard
        </a>

    </div>

    <div class="card border-0 shadow-sm">

        <div class="card-body">

            <div class="table-responsive">

                <table class="table align-middle">

                    <thead class="table-dark">

                        <tr>
                            <th>ID</th>
                            <th>Image</th>
                            <th>Product</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Seller</th>
                            <th>Actions</th>
                        </tr>

                    </thead>

                    <tbody>

                    <?php if (mysqli_num_rows($products) > 0): ?>

                        <?php while ($product = mysqli_fetch_assoc($products)): ?>

                            <tr>

                                <td>
                                    #<?php echo $product['id']; ?>
                                </td>

                                <td>

                                    <img src="../assets/images/products/<?php echo $product['product_image']; ?>"
                                         alt="<?php echo htmlspecialchars($product['product_name']); ?>"
                                         style="width:80px; height:80px; object-fit:cover; border-radius:10px;">

                                </td>

                                <td class="fw-bold">
                                    <?php echo htmlspecialchars($product['product_name']); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($product['category']); ?>
                                </td>

                                <td class="text-success fw-bold">
                                    R<?php echo number_format($product['price'], 2); ?>
                                </td>

                                <td>
                                    <?php echo htmlspecialchars($product['seller_name']); ?>
                                </td>

                                <td>

                                    <a href="../product-details.php?id=<?php echo $product['id']; ?>" class="btn-view">
                                        View
                                    </a>

                                    <a href="edit-product.php?id=<?php echo $product['id']; ?>" class="btn-edit">
                                       Edit
                                   </a>
                                    <a href="delete-product.php?id=<?php echo $product['id']; ?>" class="btn-delete">
                                        Delete
                                    </a>

                                </td>

                            </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>
                            <td colspan="7" class="text-center py-4">
                                No products found.
                            </td>
                        </tr>

                    <?php endif; ?>

                    </tbody>

                </table>

            </div>

        </div>

    </div>

</div>

<!-- FOOTER -->
<footer class="bg-dark text-white pt-5 pb-3 mt-5">

    <div class="container">

        <div class="row">

            <!-- ABOUT -->
            <div class="col-md-4 mb-4">

                <h2 class="fw-bold">TownTrade SA</h2>

                <p class="mt-3">
                    South Africa’s trusted online marketplace for buying and
                    selling products safely.
                </p>

            </div>

            <!-- LINKS -->
            <div class="col-md-4 mb-4">

                <h4 class="fw-bold">Quick Links</h4>

                <ul class="list-unstyled mt-3">

                    <li class="mb-2">
                        <a href="../index.php"
                           class="text-white text-decoration-none">
                            Home
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="../products.php"
                           class="text-white text-decoration-none">
                            Products
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="../login.php"
                           class="text-white text-decoration-none">
                            Login
                        </a>
                    </li>

                    <li class="mb-2">
                        <a href="../register.php"
                           class="text-white text-decoration-none">
                            Register
                        </a>
                    </li>

                </ul>

            </div>

            <!-- CONTACT -->
            <div class="col-md-4 mb-4">

                <h4 class="fw-bold">Contact</h4>

                <p class="mt-3">
                    Email:
                    <a href="mailto:support@towntradesa.co.za"
                       class="text-white">
                        support@towntradesa.co.za
                    </a>
                </p>

                <p>
                    Phone:
                    <a href="https://wa.me/27784515381"
                       target="_blank"
                       class="text-white">
                        +27 78 451 5381
                    </a>
                </p>

                <p>Cape Town, South Africa</p>

            </div>

        </div>

        <hr class="border-secondary">

        <div class="text-center">
            © 2026 TownTrade SA | Built for ITECA3-12 Web Development Project
        </div>

    </div>

</footer>

</body>
</html>
