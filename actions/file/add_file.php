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
    $FileID = $_POST["FileID"];
    $FileJudul = $_FILES["file_judul"];
    $FilePath = $_POST["FilePath"];
    $FileDate = $_POST["FileDate"];
    $FileJenis = $_POST["FileJenis"];

    $post_data = [
        "FileID" => $FileID,
        "file_judul" => $FileJudul,
        "FilePath" => $FilePath,
        "FileDate" => $FileDate,
        "FileJenis" => $FileJenis,
    ];


    // Append file data to the post data
    $post_data["file_judul"] = new CURLFile($FileJudul["tmp_name"], $FileJudul["type"], $FileJudul["name"]);

    $ch = curl_init($baseUrl . "auth/file/");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($http_code === 200) {
        $_SESSION["message_file_success"] = "file berhasil ditambahkan";
    } else {
        $_SESSION["message_file_failed"] = "gagal menambahkan file";
    }

    // Redirect to file.php using header
    header("Location: ../../pages/file.php");
    exit();
}
?>