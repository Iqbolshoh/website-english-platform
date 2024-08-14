<?php
include '../config.php';
$query = new Query();
$query->checkAuthentication();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sentences</title>
    <link rel="icon" type="image/png" sizes="32x32" href="../images/favicon.png">
</head>

<body>
    <?php include '../includes/header.php' ?>


    <?php include '../includes/footer.php' ?>

</body>

</html>