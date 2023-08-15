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

$headers = ["Authorization: " . $token];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $group_id = $_POST["GroupID"];
    $GroupNama = $_POST["GroupNama"];
    $GroupDeskripsi = $_POST["GroupDeskripsi"];

    $post_data = [
        "GroupID" => $group_id,
        "GroupNama" => $GroupNama,
        "GroupDeskripsi" => $GroupDeskripsi,
    ];

    $ch = curl_init($baseUrl . "group/" . $group_id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // Menggunakan metode PUT untuk update data
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($http_code === 200) {
        $_SESSION["message_group_success"] = "group berhasil diedit";
    } else {
        $_SESSION["message_group_failed"] = "Gagal edit group";
    }

    header("Location: ../../pages/group.php");
    exit();

}
?>