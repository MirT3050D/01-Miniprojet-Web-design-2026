<?php
session_start();
include_once 'login.php';
$conn = getConnection();
$user_info = login($_POST["identifier"], $_POST["password"], $conn);
if ($user_info == null) {
    session_destroy();
    header("Location: ../pages/login.php?error=1");
    exit;
} else {
    $_SESSION['user'] = $user_info;
    header("Location: ../pages/index_back_office.php");
    exit;
}
