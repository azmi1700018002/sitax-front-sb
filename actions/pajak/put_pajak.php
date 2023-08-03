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
    $pajak_id = $_POST["PajakID"];
    $NamaPajak = $_POST["NamaPajak"];
    $ParentPajak = $_POST["ParentPajak"];
    $StsPajak = $_POST["StsPajak"];
    $KetPajak = $_POST["KetPajak"];
    $StsParent = $_POST["StsParent"];
    $FileID = $_POST["FileID"];

    $post_data = [
        "PajakID" => $pajak_id,
        "NamaPajak" => $NamaPajak,
        "ParentPajak" => $ParentPajak,
        "StsPajak" => $StsPajak,
        "KetPajak" => $KetPajak,
        "StsParent" => $StsParent,
        "FileID" => $FileID,
    ];

    $ch = curl_init($baseUrl . "auth/pajak/" . $pajak_id); // Gunakan URL dengan pajak_id yang akan diupdate
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // Menggunakan metode PUT untuk update data
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($http_code === 200) {
        $_SESSION["message_pajak_success"] = "pajak berhasil diedit";
    } else {
        $_SESSION["message_pajak_failed"] = "gagal edit pajak";
    }

    // Redirect to pajak.php using header
    header("Location: ../../pages/pajak.php");
    exit();
}
?>