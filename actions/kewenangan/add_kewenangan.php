<?php
require_once("../../config/server.php");

session_start();

// Check if user is logged in
if (!isset($_SESSION["token"])) {
    header("Location: login.php");
    exit();
}

// Extract username and token from session
$username = $_SESSION["Username"];
$token = "Bearer " . $_SESSION["token"];

$headers = ["Authorization: " . $token, "Content-Type: multipart/form-data"];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $GroupID = $_POST["GroupID"];
    $MenuID = $_POST["MenuID"];
    $IsCreated = $_POST["IsCreated"];
    $IsUpdated = $_POST["IsUpdated"];
    $IsDeleted = $_POST["IsDeleted"];

    $post_data = [
        "GroupID" => $GroupID,
        "MenuID" => $MenuID,
        "IsCreated" => $IsCreated,
        "IsUpdated" => $IsUpdated,
        "IsDeleted" => $IsDeleted,
    ];
     
    $ch = curl_init($baseUrl . "kewenangan/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);

    if ($http_code === 200) {
        $_SESSION["message_kewenangan_success"] = "kewenangan berhasil ditambahkan";
    } else {
        $_SESSION["message_kewenangan_failed"] = "gagal menambahkan kewenangan";
    }

    header("Location: ../../pages/kewenangan.php");
    exit();
}

?>