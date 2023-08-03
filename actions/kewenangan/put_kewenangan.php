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
    $group_id = $_POST["GroupID"];
    $menu_id = $_POST["MenuID"];
    $IsCreated = $_POST["IsCreated"];
    $IsUpdated = $_POST["IsUpdated"];
    $IsDeleted = $_POST["IsDeleted"];

    $post_data = [
        "GroupID" => $group_id,
        "MenuID" => $menu_id,
        "IsCreated" => $IsCreated,
        "IsUpdated" => $IsUpdated,
        "IsDeleted" => $IsDeleted,
    ];

    $ch = curl_init($baseUrl . "kewenangan/" . $group_id . "/" . $menu_id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($http_code === 200) {
        $_SESSION["message_kewenangan_success"] = "kewenangan berhasil diedit";
    } else {
        $_SESSION["message_kewenangan_failed"] = "gagal edit kewenangan";
    }

    header("Location: ../../pages/kewenangan.php");
    exit();
}

?>