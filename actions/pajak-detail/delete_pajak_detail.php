<?php
require_once("../../config/server.php");

session_start();

// Check if user is logged in
if (!isset($_SESSION["token"])) {
    header("Location: login.php");
}

// Extract token from session
$token = "Bearer " . $_SESSION["token"];

$headers = ["Authorization: " . $token, "Content-Type: application/json"];

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pajak_detail_id = $_POST["PajakDetailID"];

    $ch = curl_init($baseUrl . "auth/pajak-detail/" . $pajak_detail_id);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);

    
    if ($http_code === 200) {
        $_SESSION["message_pajak_detail_success"] = "detail pajak berhasil dihapus";
    } else {
        $_SESSION["message_pajak_detail_failed"] = "gagal hapus detail pajak";
    }

    header("Location: ../../pages/pajak-detail.php");
    exit();
}
?>