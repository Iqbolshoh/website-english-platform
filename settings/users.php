<?php include '../check.php'; ?>
<?php include '../last_page.php';

if ($_SESSION['username'] !== 'iqbolshoh') {
    header("Location: ../");
    exit;
}

$user = $query->select('users');
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User List</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon.ico">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .table {
            background-color: #fff;
            border-radius: 0.5rem;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .table th {
            background-color: #2f4f4f;
            color: white;
        }

        .table td {
            vertical-align: middle;
        }

        .profile-img {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            border: 2px solid #2f4f4f;
        }

        .header-title {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        .container {
            margin-top: 50px;
        }
    </style>
</head>

<body>

    <?php include '../includes/header.php'; ?>

    <div class="container">
        <h2 class="header-title">User List</h2>

        <div class="table-responsive">
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Username</th>
                        <th>Profile Image</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($user as $u): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($u['id']); ?></td>
                            <td><?php echo htmlspecialchars($u['fullname']); ?></td>
                            <td><?php echo htmlspecialchars($u['email']); ?></td>
                            <td><?php echo htmlspecialchars($u['username']); ?></td>
                            <td class="text-center">
                                <img src="../images/profile-image/<?php echo htmlspecialchars($u['profile_image']); ?>" alt="Profile Image" class="profile-img">
                            </td>
                            <td><?php echo htmlspecialchars($u['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?php include '../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.3/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>

</html>