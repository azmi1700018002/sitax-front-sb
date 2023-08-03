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
    $PajakDetailID = $_POST["PajakDetailID"];
    $PajakID = $_POST["PajakID"];
    $Ppn = $_POST["Ppn"];
    $Pasal23 = $_POST["Pasal23"];
    $PphFinal = $_POST["PphFinal"];
    $PajakLain = $_POST["PajakLain"];
    $Keterangan = $_POST["Keterangan"];

    $post_data = [
        "PajakDetailID" => $PajakDetailID,
        "PajakID" => $PajakID,
        "Ppn" => $Ppn,
        "Pasal23" => $Pasal23,
        "PphFinal" => $PphFinal,
        "PajakLain" => $PajakLain,
        "Keterangan" => $Keterangan,
    ];

    $ch = curl_init($baseUrl . "auth/pajak-detail/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($http_code === 200) {
        $_SESSION["message_pajak_detail_success"] = "detail pajak berhasil ditambahkan";
    } else {
        $_SESSION["message_pajak_detail_failed"] = "gagal menambahkan detail pajak";
    }

    // Redirect to pajak.php using header
    header("Location: ../../pages/pajak-detail.php");
    exit();
}
?>