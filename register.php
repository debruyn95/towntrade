<?php
session_start();

require_once 'config/app.php';
include 'config/database.php';

$message = "";

if (isset($_POST['register'])) {

    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);

    $password = password_hash(
        $_POST['password'],
        PASSWORD_DEFAULT
    );

    // CHECK IF EMAIL EXISTS
    $check_query = "SELECT * FROM users WHERE email='$email'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {

        $message = "Email already registered.";

    } else {

        $sql = "INSERT INTO users(fullname, email, password)
                VALUES('$fullname', '$email', '$password')";

        if (mysqli_query($conn, $sql)) {

            $message = "Registration successful!";

        } else {

            $message = "Error: " . mysqli_error($conn);

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Register - TownTrade SA</title>

    <link rel="icon"
          type="image/png"
          href="<?php echo BASE_URL; ?>/assets/images/logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

</head>

<body style="background:#f5f5f5;">

<?php include 'includes/navbar.php'; ?>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-md-5">

            <div class="card shadow border-0">

                <div class="card-header bg-dark text-white">

                    <h2 class="mb-0">
                        Create Account
                    </h2>

                </div>

                <div class="card-body p-4">

                    <?php if($message != ""): ?>

                        <div class="alert alert-info">
                            <?php echo $message; ?>
                        </div>

                    <?php endif; ?>

                    <form method="POST">

                        <div class="mb-3">

                            <label class="form-label">
                                Full Name
                            </label>

                            <input type="text"
                                   name="fullname"
                                   class="form-control"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Email Address
                            </label>

                            <input type="email"
                                   name="email"
                                   class="form-control"
                                   required>

                        </div>

                        <div class="mb-3">

                            <label class="form-label">
                                Password
                            </label>

                            <input type="password"
                                   name="password"
                                   class="form-control"
                                   required>

                        </div>

                        <button type="submit"
                                name="register"
                                class="btn btn-primary w-100">

                            Register

                        </button>

                    </form>

                    <div class="mt-3">

                        <a href="<?php echo BASE_URL; ?>/login.php">

                            Already have an account? Login

                        </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>