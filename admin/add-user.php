<?php
session_start();

include("../config/database.php");

// Check if admin is logged in
if (!isset($_SESSION['role']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Add user
if (isset($_POST['add_user'])) {

    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email    = mysqli_real_escape_string($conn, $_POST['email']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    // Default role for admin-created users
    $role = 'buyer';

    // Hash password
    $password = password_hash($password, PASSWORD_DEFAULT);

    // Insert user
    mysqli_query($conn, "
        INSERT INTO users(fullname, email, password, role)
        VALUES('$fullname', '$email', '$password', '$role')
    ");

    header("Location: users.php");
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

    <title>Add User - TownTrade SA</title>

    <link
        rel="stylesheet"
        href="../assets/css/style.css"
    >

</head>

<body>

<?php include("../includes/navbar.php"); ?>

<section class="add-product-section">

    <div class="container">

        <div class="form-container">

            <h1>Add User</h1>

            <form method="POST">

                <!-- FULL NAME -->
                <div class="form-group">

                    <label>Full Name</label>

                    <input
                        type="text"
                        name="fullname"
                        required
                    >

                </div>

                <!-- EMAIL -->
                <div class="form-group">

                    <label>Email Address</label>

                    <input
                        type="email"
                        name="email"
                        required
                    >

                </div>

                <!-- PASSWORD -->
                <div class="form-group">

                    <label>Password</label>

                    <input
                        type="password"
                        name="password"
                        required
                    >

                </div>

                <!-- BUTTONS -->
                <div style="display:flex; gap:10px; margin-top:20px;">

                    <button
                        type="submit"
                        name="add_user"
                        class="btn-primary"
                    >
                        Add User
                    </button>

                    <a
                        href="users.php"
                        class="btn btn-secondary"
                    >
                        Cancel
                    </a>

                </div>

            </form>

        </div>

    </div>

</section>

<?php include("../includes/footer.php"); ?>

</body>
</html>