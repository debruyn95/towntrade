<?php
session_start();

require_once '../config/app.php';
require_once '../config/database.php';

/* CHECK ADMIN ACCESS */
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

/* DELETE USER */
if (isset($_GET['delete'])) {

    $delete_id = (int) $_GET['delete'];

    /* PREVENT ADMIN FROM DELETING THEMSELF */
    if ($delete_id != $_SESSION['user_id']) {

        mysqli_query(
            $conn,
            "DELETE FROM users WHERE id = '$delete_id'"
        );
    }

    header("Location: users.php");
    exit();
}

/* GET USERS */
$sql = "SELECT * FROM users ORDER BY id DESC";

$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">

    <title>Manage Users - TownTrade SA</title>

    <link rel="icon"
          type="image/png"
          href="<?php echo BASE_URL; ?>/assets/images/logo.png">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
          rel="stylesheet">

    <style>

        body {
            background: #f5f5f5;
        }

        .card-custom {
            border: none;
            border-radius: 14px;
            box-shadow: 0 4px 14px rgba(0,0,0,0.08);
        }

    </style>

</head>

<body>

<?php include '../includes/navbar.php'; ?>

<div class="container my-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h1 class="fw-bold">
            Manage Users
        </h1>

        <a href="<?php echo BASE_URL; ?>/admin/dashboard.php"
           class="btn btn-dark">

           Back to Dashboard

        </a>

    </div>

    <div class="card card-custom p-4">

        <div class="table-responsive">

            <table class="table table-hover align-middle">

                <thead class="table-dark">

                    <tr>

                        <th>ID</th>

                        <th>Full Name</th>

                        <th>Email</th>

                        <th>Role</th>

                        <th>Joined</th>

                        <th>Actions</th>

                    </tr>

                </thead>

                <tbody>

                    <?php if (mysqli_num_rows($result) > 0): ?>

                        <?php while ($user = mysqli_fetch_assoc($result)): ?>

                        <tr>

                            <td>
                                <?php echo $user['id']; ?>
                            </td>

                            <td>
                                <?php echo $user['fullname']; ?>
                            </td>

                            <td>
                                <?php echo $user['email']; ?>
                            </td>

                            <td>

                                <?php if ($user['role'] == 'admin'): ?>

                                    <span class="badge bg-danger">
                                        Admin
                                    </span>

                                <?php else: ?>

                                    <span class="badge bg-primary">
                                        User
                                    </span>

                                <?php endif; ?>

                            </td>

                            <td>

                                <?php echo $user['created_at']; ?>

                            </td>

                            <td>

                                <?php if ($user['id'] != $_SESSION['user_id']): ?>

                                    <a href="users.php?delete=<?php echo $user['id']; ?>"
                                       class="btn btn-danger btn-sm"
                                       onclick="return confirm('Delete this user?')">

                                       Delete

                                    </a>

                                <?php else: ?>

                                    <span class="text-muted">
                                        Current User
                                    </span>

                                <?php endif; ?>

                            </td>

                        </tr>

                        <?php endwhile; ?>

                    <?php else: ?>

                        <tr>

                            <td colspan="6" class="text-center">

                                No users found.

                            </td>

                        </tr>

                    <?php endif; ?>

                </tbody>

            </table>

        </div>

    </div>

</div>

<?php include '../includes/footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>