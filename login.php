<?php
session_start();

include 'config/database.php';

$message = "";

/* LOGIN USER */
if (isset($_POST['login'])) {

    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email='$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) > 0) {

        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {

            $_SESSION['user_id'] = $user['id'];
            $_SESSION['fullname'] = $user['fullname'];
            $_SESSION['role'] = $user['role'];

            /* ADMIN REDIRECT */
            if ($user['role'] == 'admin') {

                header("Location: admin/dashboard.php");

            } else {

                header("Location: profile.php");

            }

            exit();

        } else {

            $message = "Invalid password.";

        }

    } else {

        $message = "User not found.";

    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - TownTrade SA</title>

    <link rel="stylesheet" href="assets/css/style.css">

    <style>
        body{
            margin:0;
            font-family:Arial, sans-serif;
            background:#f4f4f4;
        }

        .login-container{
            width:100%;
            min-height:80vh;
            display:flex;
            justify-content:center;
            align-items:center;
            padding:40px 20px;
        }

        .login-card{
            background:#fff;
            width:100%;
            max-width:450px;
            border-radius:10px;
            box-shadow:0 4px 15px rgba(0,0,0,0.1);
            overflow:hidden;
        }

        .login-header{
            background:#1f2937;
            color:white;
            padding:25px;
        }

        .login-header h2{
            margin:0;
        }

        .login-body{
            padding:30px;
        }

        .form-group{
            margin-bottom:20px;
        }

        .form-group label{
            display:block;
            margin-bottom:8px;
            font-weight:bold;
        }

        .form-group input{
            width:100%;
            padding:12px;
            border:1px solid #ccc;
            border-radius:5px;
            font-size:16px;
            box-sizing:border-box;
        }

        .btn-login{
            width:100%;
            background:#2563eb;
            color:white;
            border:none;
            padding:12px;
            font-size:16px;
            border-radius:5px;
            cursor:pointer;
            transition:0.3s;
        }

        .btn-login:hover{
            background:#1d4ed8;
        }

        .message{
            background:#fee2e2;
            color:#991b1b;
            padding:12px;
            border-radius:5px;
            margin-bottom:20px;
        }

        .register-link{
            margin-top:20px;
            text-align:center;
        }

        .register-link a{
            color:#2563eb;
            text-decoration:none;
        }

        .register-link a:hover{
            text-decoration:underline;
        }
    </style>
</head>
<body>

<?php include 'includes/navbar.php'; ?>

<div class="login-container">

    <div class="login-card">

        <div class="login-header">
            <h2>Login</h2>
        </div>

        <div class="login-body">

            <?php if(!empty($message)) { ?>
                <div class="message">
                    <?php echo $message; ?>
                </div>
            <?php } ?>

            <form method="POST">

                <div class="form-group">
                    <label>Email Address</label>
                    <input type="email" name="email" required>
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" required>
                </div>

                <button type="submit" name="login" class="btn-login">
                    Login
                </button>

            </form>

            <div class="register-link">
                Don't have an account?
                <a href="register.php">Register</a>
            </div>

        </div>

    </div>

</div>

<?php include 'includes/footer.php'; ?>

</body>
</html>
