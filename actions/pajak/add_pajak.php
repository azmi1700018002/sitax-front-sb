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
    $PajakID = $_POST["PajakID"];
    $NamaHeader = $_POST["NamaHeader"];
    $NamaPajak = $_POST["NamaPajak"];
    $ParentPajak = $_POST["ParentPajak"];
    $StsPajak = $_POST["StsPajak"];
    $KetPajak = $_POST["KetPajak"];
    $StsParent = $_POST["StsParent"];
    $FileID = $_POST["FileID"];

    $post_data = [
        "PajakID" => $PajakID,
        "NamaHeader" => $NamaHeader,
        "NamaPajak" => $NamaPajak,
        "ParentPajak" => $ParentPajak,
        "StsPajak" => $StsPajak,
        "KetPajak" => $KetPajak,
        "FileID" => $FileID,
    ];

    $ch = curl_init($baseUrl . "auth/pajak/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($http_code === 200) {
        $_SESSION["message_pajak_success"] = "pajak berhasil ditambahkan";
    } else {
        $_SESSION["message_pajak_failed"] = "gagal menambahkan pajak";
    }

    // Redirect to pajak.php using header
    header("Location: ../../pages/pajak.php");
    exit();
}
?>