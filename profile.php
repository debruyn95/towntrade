<?php
session_start();
include('config/database.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$user_query = mysqli_query($conn, "
    SELECT *
    FROM users
    WHERE id = '$user_id'
");

$user = mysqli_fetch_assoc($user_query);

$message = "";

if (isset($_POST['update_profile'])) {

    /*
    |--------------------------------------------------------------------------
    | ACCOUNT INFORMATION
    |--------------------------------------------------------------------------
    */

    $fullname = mysqli_real_escape_string(
        $conn,
        trim($_POST['fullname'])
    );

    $email = mysqli_real_escape_string(
        $conn,
        trim($_POST['email'])
    );

    /*
    |--------------------------------------------------------------------------
    | PASSWORD
    |--------------------------------------------------------------------------
    */

    $new_password = trim($_POST['new_password']);
    $confirm_password = trim($_POST['confirm_password']);

    /*
    |--------------------------------------------------------------------------
    | SHIPPING INFORMATION
    |--------------------------------------------------------------------------
    */

    $phone = mysqli_real_escape_string(
        $conn,
        trim($_POST['phone'])
    );

    $address_line = mysqli_real_escape_string(
        $conn,
        trim($_POST['address_line'])
    );

    $suburb = mysqli_real_escape_string(
        $conn,
        trim($_POST['suburb'])
    );

    $city = mysqli_real_escape_string(
        $conn,
        trim($_POST['city'])
    );

    $province = mysqli_real_escape_string(
        $conn,
        trim($_POST['province'])
    );

    $postal_code = mysqli_real_escape_string(
        $conn,
        trim($_POST['postal_code'])
    );

    /*
    |--------------------------------------------------------------------------
    | EFT BANKING INFORMATION
    |--------------------------------------------------------------------------
    */

    $bank_name = mysqli_real_escape_string(
        $conn,
        trim($_POST['bank_name'])
    );

    $account_holder = mysqli_real_escape_string(
        $conn,
        trim($_POST['account_holder'])
    );

    $account_number = mysqli_real_escape_string(
        $conn,
        trim($_POST['account_number'])
    );

    $branch_code = mysqli_real_escape_string(
        $conn,
        trim($_POST['branch_code'])
    );

    $account_type = mysqli_real_escape_string(
        $conn,
        trim($_POST['account_type'])
    );

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

    if (
        empty($fullname) ||
        empty($email)
    ) {

        $message = "
            <div class='alert alert-danger'>
                Full name and email are required.
            </div>
        ";

    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $message = "
            <div class='alert alert-danger'>
                Please enter a valid email address.
            </div>
        ";

    }
    else if (!preg_match('/^[0-9]{10}$/', $phone)) {

        $message = "
            <div class='alert alert-danger'>
                Phone number must contain exactly 10 numbers.
            </div>
        ";

    }
    else if (!preg_match('/^[0-9]{4,10}$/', $postal_code)) {

        $message = "
            <div class='alert alert-danger'>
                Postal code must contain numbers only.
            </div>
        ";

    }
    else if (!preg_match('/^[0-9]{6,20}$/', $account_number)) {

        $message = "
            <div class='alert alert-danger'>
                Account number must contain numbers only.
            </div>
        ";

    }
    else if (!preg_match('/^[0-9]{3,10}$/', $branch_code)) {

        $message = "
            <div class='alert alert-danger'>
                Branch code must contain numbers only.
            </div>
        ";

    }
    else {

        /*
        |--------------------------------------------------------------------------
        | UPDATE QUERY
        |--------------------------------------------------------------------------
        */

        $update_query = "
            UPDATE users SET

            fullname = '$fullname',
            email = '$email',

            phone = '$phone',
            address_line = '$address_line',
            suburb = '$suburb',
            city = '$city',
            province = '$province',
            postal_code = '$postal_code',

            bank_name = '$bank_name',
            account_holder = '$account_holder',
            account_number = '$account_number',
            branch_code = '$branch_code',
            account_type = '$account_type'
        ";

        /*
        |--------------------------------------------------------------------------
        | ONLY VALIDATE PASSWORD IF USER ENTERED ONE
        |--------------------------------------------------------------------------
        */

        if (!empty($new_password) || !empty($confirm_password)) {

            if (
                empty($new_password) ||
                empty($confirm_password)
            ) {

                $message = "
                    <div class='alert alert-danger'>
                        Please complete both password fields.
                    </div>
                ";

            }
            else if (
                strlen($new_password) < 10 ||
                !preg_match('/[A-Z]/', $new_password) ||
                !preg_match('/[a-z]/', $new_password) ||
                !preg_match('/[0-9]/', $new_password) ||
                !preg_match('/[\W]/', $new_password)
            ) {

                $message = "
                    <div class='alert alert-danger'>
                        Password must be at least 10 characters and contain uppercase, lowercase, number and special character.
                    </div>
                ";

            }
            else if ($new_password != $confirm_password) {

                $message = "
                    <div class='alert alert-danger'>
                        Passwords do not match.
                    </div>
                ";

            }
            else {

                $hashed_password =
                    password_hash($new_password, PASSWORD_DEFAULT);

                $update_query .= "
                    , password = '$hashed_password'
                ";
            }
        }

        /*
        |--------------------------------------------------------------------------
        | EXECUTE UPDATE
        |--------------------------------------------------------------------------
        */

        if (empty($message)) {

            $update_query .= "
                WHERE id = '$user_id'
            ";

            if (mysqli_query($conn, $update_query)) {

                $message = "
                    <div class='alert alert-success'>
                        Profile updated successfully.
                    </div>
                ";

                $user_query = mysqli_query($conn, "
                    SELECT *
                    FROM users
                    WHERE id = '$user_id'
                ");

                $user = mysqli_fetch_assoc($user_query);

            } else {

                $message = "
                    <div class='alert alert-danger'>
                        Error updating profile.
                    </div>
                ";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Profile - TownTrade SA</title>

    <link rel="stylesheet" href="assets/css/style.css">

</head>
<body>

<?php include('includes/navbar.php'); ?>

<section class="dashboard-section">

    <div class="container">

        <div class="card p-4">

            <h1>
                Welcome,
                <?php echo $user['fullname']; ?>
            </h1>

            <p>
                You are logged into TownTrade SA.
            </p>

            <p>
                <strong>Role:</strong>
                <?php echo $user['role']; ?>
            </p>

            <?php echo $message; ?>

            <form method="POST">

                <hr class="mb-4">

                <!-- ACCOUNT INFORMATION -->

                <h2 class="mb-3">
                    Account Information
                </h2>

                <div class="form-grid-2">

                    <div class="form-group">
                        <label>Full Name</label>

                        <input
                            type="text"
                            name="fullname"
                            required
                            value="<?php echo htmlspecialchars($user['fullname']); ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label>Email Address</label>

                        <input
                            type="email"
                            name="email"
                            required
                            value="<?php echo htmlspecialchars($user['email']); ?>"
                        >
                    </div>

                </div>

                <hr class="mb-4">

                <!-- PASSWORD -->

                <h2 class="mb-3">
                    Change Password
                </h2>

                <div class="form-grid-2">

                    <div class="form-group">
                        <label>New Password</label>

                        <input
                            type="password"
                            name="new_password"
                            minlength="10"
                            pattern="(?=.*\d)(?=.*[a-z])(?=.*[A-Z])(?=.*[\W]).{10,}"
                            title="Password must be at least 10 characters and include uppercase, lowercase, number and special character."
                        >
                    </div>

                    <div class="form-group">
                        <label>Confirm Password</label>

                        <input
                            type="password"
                            name="confirm_password"
                        >
                    </div>

                </div>

                <hr class="mb-4">

                <!-- SHIPPING -->

                <h2 class="mb-3">
                    Shipping Information
                </h2>

                <div class="form-grid-2">

                    <div class="form-group">
                        <label>Phone Number</label>

                        <input
                            type="text"
                            name="phone"
                            required
                            pattern="[0-9]{10}"
                            maxlength="10"
                            value="<?php echo htmlspecialchars($user['phone']); ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label>Postal Code</label>

                        <input
                            type="text"
                            name="postal_code"
                            required
                            pattern="[0-9]+"
                            value="<?php echo htmlspecialchars($user['postal_code']); ?>"
                        >
                    </div>

                </div>

                <div class="form-group">
                    <label>Street Address</label>

                    <input
                        type="text"
                        name="address_line"
                        required
                        value="<?php echo htmlspecialchars($user['address_line']); ?>"
                    >
                </div>

                <div class="form-grid-3">

                    <div class="form-group">
                        <label>Suburb</label>

                        <input
                            type="text"
                            name="suburb"
                            required
                            value="<?php echo htmlspecialchars($user['suburb']); ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label>City</label>

                        <input
                            type="text"
                            name="city"
                            required
                            value="<?php echo htmlspecialchars($user['city']); ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label>Province</label>

                        <input
                            type="text"
                            name="province"
                            required
                            value="<?php echo htmlspecialchars($user['province']); ?>"
                        >
                    </div>

                </div>

                <hr class="mb-4">

                <!-- EFT -->

                <h2 class="mb-3">
                    EFT Banking Information
                </h2>

                <div class="form-grid-2">

                    <div class="form-group">
                        <label>Bank Name</label>

                        <input
                            type="text"
                            name="bank_name"
                            required
                            value="<?php echo htmlspecialchars($user['bank_name']); ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label>Account Holder</label>

                        <input
                            type="text"
                            name="account_holder"
                            required
                            value="<?php echo htmlspecialchars($user['account_holder']); ?>"
                        >
                    </div>

                </div>

                <div class="form-grid-3">

                    <div class="form-group">
                        <label>Account Number</label>

                        <input
                            type="text"
                            name="account_number"
                            required
                            pattern="[0-9]+"
                            value="<?php echo htmlspecialchars($user['account_number']); ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label>Branch Code</label>

                        <input
                            type="text"
                            name="branch_code"
                            required
                            pattern="[0-9]+"
                            value="<?php echo htmlspecialchars($user['branch_code']); ?>"
                        >
                    </div>

                    <div class="form-group">
                        <label>Account Type</label>

                        <input
                            type="text"
                            name="account_type"
                            required
                            value="<?php echo htmlspecialchars($user['account_type']); ?>"
                        >
                    </div>

                </div>

                <button
                    type="submit"
                    name="update_profile"
                    class="btn btn-primary mt-3"
                >
                    Save Profile Information
                </button>

            </form>

        </div>

    </div>

</section>

<?php include('includes/footer.php'); ?>

</body>
</html>