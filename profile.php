<?php
session_start();

require_once 'config/app.php';
include 'config/database.php';

if (!isset($_SESSION['user_id'])) {

    header("Location: " . BASE_URL . "/login.php");
    exit();

}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Profile - TownTrade SA</title>

    <link rel="icon"
          type="image/png"
          href="<?php echo BASE_URL; ?>/assets/images/logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>

<body style="background:#f5f5f5;">

<?php include 'includes/navbar.php'; ?>

<div class="container py-5">

    <div class="card shadow border-0">

        <div class="card-body p-4">

            <h1 class="fw-bold">
                Welcome, <?php echo $_SESSION['fullname']; ?>
            </h1>

            <p class="mt-3">
                You are now logged into TownTrade SA.
            </p>

            <p>
                <strong>Role:</strong>
                <?php echo $_SESSION['role']; ?>
            </p>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>