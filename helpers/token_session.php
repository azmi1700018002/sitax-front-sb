<?php
session_start();

// Check if user is logged in
if (!isset($_SESSION["token"])) {
    header("Location: ../index.php");
    exit();
}

// Extract username and token from session
$username = $_SESSION["Username"];
$profile_picture = $_SESSION["ProfilePicture"];
$token = $_SESSION["token"];
$expired = $_SESSION["expired"];

// Cek apakah token sudah kadaluwarsa
if (strtotime($expired) < time()) {
    // Token kadaluwarsa, hapus semua data sesi dan redirect ke halaman login
    session_destroy();
    header("Location: ../index.php");
    exit();
}
?>