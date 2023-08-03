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
    $GroupID = $_POST["GroupID"];
    $username = $_POST["Username"];
    $NamaLengkap = $_POST["NamaLengkap"];
    $Email = $_POST["Email"];
    $HostIP = $_POST["HostIP"];
    $StsUser = $_POST["StsUser"];
    $KdKantor = $_POST["KdKantor"];

    $post_data = [
        "GroupID" => $GroupID,
        "Username" => $username,
        "NamaLengkap" => $NamaLengkap,
        "Email" => $Email,
        "HostIP" => $HostIP,
        "StsUser" => $StsUser,
        "KdKantor" => $KdKantor,
    ];

    $ch = curl_init($baseUrl . "auth/users/" . $username); // Gunakan URL dengan IdProduk yang akan diupdate
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // Menggunakan metode PUT untuk update data
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($http_code === 200) {
        $_SESSION["message_user_success"] = "user berhasil diedit";
    } else {
        $_SESSION["message_user_failed"] = "gagal edit user";
    }

    // Redirect to product.php using header
    header("Location: ../../pages/user.php");
    exit();
}
?>