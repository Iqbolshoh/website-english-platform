<?php
session_start();

include '../modeL/UserModel.php';
$query = new UserModel();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}

$user_id = $_SESSION['user_id'];
$user = $query->getUserById($user_id);

$username = htmlspecialchars($user['username']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    $profile_image = $user['profile_image'];
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $_FILES['profile_image']['tmp_name'];
        $fileName = $_FILES['profile_image']['name'];
        $fileNameCmps = explode(".", $fileName);
        $fileExtension = strtolower(end($fileNameCmps));
        $newFileName = md5(time() . $fileName) . '.' . $fileExtension;
        $uploadFileDir = '../images/profile-image/';
        $dest_path = $uploadFileDir . $newFileName;

        if ($profile_image && $profile_image !== 'default.png') {
            $oldFilePath = $uploadFileDir . $profile_image;
            if (file_exists($oldFilePath)) {
                unlink($oldFilePath);
            }
        }

        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            $profile_image = $newFileName;
        }
    }

    $updateData = [
        'fullname' => $fullname,
        'email' => $email,
        'profile_image' => $profile_image
    ];

    if (!empty($password)) {
        $updateData['password'] = hash_hmac('sha256', $password, 'iqbolshoh-ilhomjonov');
    }

    $query->update('users', $updateData, "id = $user_id");

    header("Location: profile.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile</title>
    <link rel="stylesheet" href="../css/profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>

<body>

    <?php include '../includes/header.php'; ?>

    <div class="profile-container">
        <div class="profile-form-container">
            <div class="profile-header">
                <img class="profile-image"
                    src="../images/profile-image/<?php echo $user['profile_image'] ? $user['profile_image'] : 'default.png'; ?>"
                    alt="Profile Image">
                <h2 class="profile-name"><?php echo htmlspecialchars($user['fullname']); ?></h2>
            </div>
            <form id="profile-form" action="profile.php" method="POST" enctype="multipart/form-data"
                class="profile-form">
                <label for="fullname" class="form-label">Full Name:</label>
                <input type="text" id="fullname" name="fullname" class="form-input"
                    value="<?php echo htmlspecialchars($user['fullname']); ?>" required>

                <label for="username" class="form-label">Username:</label>
                <input type="text" id="username" name="username" class="form-input" value="<?php echo $username; ?>"
                    readonly>

                <label for="email" class="form-label">Email:</label>
                <input type="email" id="email" name="email" class="form-input"
                    value="<?php echo htmlspecialchars($user['email']); ?>" required>

                <label for="profile_image" class="form-label">Profile Image:</label>
                <input type="file" id="profile_image" name="profile_image" class="form-input" accept="image/*">

                <label for="password" class="form-label">New Password:</label>
                <div class="password-container">
                    <input type="password" id="password" name="password" class="password-input"
                        placeholder="No change? Leave blank.">
                    <a type="button" id="toggle-password" class="password-toggle"><i class="fas fa-eye"></i></a>
                </div>

                <button type="submit" class="submit-button">Save Changes</button>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('toggle-password').addEventListener('click', function () {
            const passwordField = document.getElementById('password');
            const toggleIcon = this.querySelector('i');

            if (passwordField.type === 'password') {
                passwordField.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordField.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        });

        document.getElementById('profile-form').addEventListener('submit', function (event) {
            event.preventDefault();

            Swal.fire({
                title: 'Profile Updated',
                text: 'Your profile has been updated successfully!',
                icon: 'success',
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.submit();
                }
            });
        });
    </script>
</body>

</html>