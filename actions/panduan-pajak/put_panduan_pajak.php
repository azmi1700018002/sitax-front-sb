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
    $panduan_pajak_id = $_POST["PanduanPajakID"];
    $NamaPanduanPajak = $_POST["NamaPanduanPajak"];
    $ParentPanduanPajak = $_POST["ParentPanduanPajak"];
    $StsPanduanPajak = $_POST["StsPanduanPajak"];
    $StsParent = $_POST["StsParent"];
    $FileID = $_POST["FileID"];

    $post_data = [
        "PanduanPajakID" => $panduan_pajak_id,
        "NamaPanduanPajak" => $NamaPanduanPajak,
        "ParentPanduanPajak" => $ParentPanduanPajak,
        "StsPanduanPajak" => $StsPanduanPajak,
        "StsParent" => $StsParent,
        "FileID" => $FileID,
    ];

    $ch = curl_init($baseUrl . "auth/panduan_pajak/" . $panduan_pajak_id); // Gunakan URL dengan pajak_id yang akan diupdate
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // Menggunakan metode PUT untuk update data
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($http_code === 200) {
        $_SESSION["message_panduan_pajak_success"] = "panduan pajak berhasil diedit";
    } else {
        $_SESSION["message_panduan_pajak_failed"] = "gagal edit panduan pajak";
    }

    // Redirect to pajak.php using header
    header("Location: ../../pages/panduan-pajak.php");
    exit();
}
?>