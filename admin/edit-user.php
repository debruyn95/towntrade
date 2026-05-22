<?php
session_start();

include '../config/database.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if (!isset($_GET['id'])) {
    header("Location: users.php");
    exit();
}

$id = intval($_GET['id']);

$result = mysqli_query($conn, "SELECT * FROM users WHERE id=$id");

if (!$result || mysqli_num_rows($result) == 0) {
    die("User not found.");
}

$user = mysqli_fetch_assoc($result);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $role = mysqli_real_escape_string($conn, $_POST['role']);

    mysqli_query($conn, "
        UPDATE users
        SET
            fullname='$fullname',
            email='$email',
            role='$role'
        WHERE id=$id
    ");

    header("Location: users.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Edit User</title>

<link rel="stylesheet" href="../assets/css/style.css">

<style>

.edit-container{
    max-width:600px;
    margin:50px auto;
    background:#fff;
    padding:30px;
    border-radius:10px;
}

.form-group{
    margin-bottom:20px;
}

.form-group label{
    display:block;
    margin-bottom:8px;
    font-weight:bold;
}

.form-group input,
.form-group select{
    width:100%;
    padding:12px;
}

.btn-update{
    background:#2563eb;
    color:white;
    padding:12px 20px;
    border:none;
    cursor:pointer;
}

</style>

</head>

<body>

<?php include '../includes/navbar.php'; ?>

<div class="edit-container">

    <h1>Edit User</h1>

    <form method="POST">

        <div class="form-group">
            <label>Full Name</label>

            <input
                type="text"
                name="fullname"
                value="<?php echo htmlspecialchars($user['fullname']); ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>Email</label>

            <input
                type="email"
                name="email"
                value="<?php echo htmlspecialchars($user['email']); ?>"
                required
            >
        </div>

        <div class="form-group">
            <label>Role</label>

            <select name="role">

                <option
                    value="user"
                    <?php if($user['role'] == 'user') echo 'selected'; ?>
                >
                    User
                </option>

                <option
                    value="admin"
                    <?php if($user['role'] == 'admin') echo 'selected'; ?>
                >
                    Admin
                </option>

            </select>
        </div>

        <button type="submit" class="btn-update">
            Update User
        </button>

    </form>

</div>

<?php include '../includes/footer.php'; ?>

</body>
</html>