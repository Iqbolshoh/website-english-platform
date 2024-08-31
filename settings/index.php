<?php

session_start();

include '../config.php';
$query = new Query();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Settings</title>
    <link rel="icon" type="image/png" sizes="16x16" href="../favicon.ico">
    <link rel="stylesheet" href="../css/link-menu.css">
</head>

<body>

    <?php include '../includes/header.php' ?>

    <div class="container">
        <h1>Settings</h1>

        <div class="settings">

            <a href="profile.php" class="link">
                <i class="fa-solid fa-user"></i>
                <span>Profile</span>
            </a>

            <a href="dictionary-pdf.php" class="link">
                <i class="fa-solid fa-download"></i>
                <span>Save dictionary .pdf</span>
            </a>

            <a href="sentences-pdf.php" class="link">
                <i class="fa-solid fa-download"></i>
                <span>Save sentences .pdf</span>
            </a>

            <a href="text-pdf.php" class="link">
                <i class="fa-solid fa-download"></i>
                <span>Save Text .pdf</span>
            </a>

            <a href="#" class="link" onclick="Logout(); return false;">
                <i class="fa-solid fa-right-from-bracket link-icon"></i>
                <span class="link-text">Logout</span>
            </a>

        </div>
        
    </div>
    
    <?php include '../includes/footer.php' ?>
    
    <script>
        function Logout() {
            const menuToggle = document.querySelector('.header-menu-toggle');
            const links = document.querySelector('.header-links');
            const icon = menuToggle.querySelector('i');
            links.classList.remove('active');
            icon.classList.remove('fa-times');
            icon.classList.add('fa-bars');
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this action!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Yes, log out!',
                cancelButtonText: 'No, cancel!',
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {

                    window.location.href = '../logout/';
                }
            });
        }
    </script>
</body>

</html>