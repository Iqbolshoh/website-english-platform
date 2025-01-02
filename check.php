<?php

session_start();

include '../config.php';
$query = new Database();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: ../login/");
}