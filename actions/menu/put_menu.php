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
    $menu_id = $_POST["MenuID"];
    $MenuNama = $_POST["MenuNama"];
    $MenuLink = $_POST["MenuLink"];
    $MenuDeskripsi = $_POST["MenuDeskripsi"];
    $MenuStatus = $_POST["MenuStatus"];
    $MenuIcon = $_POST["MenuIcon"];
    $MenuKategori = $_POST["MenuKategori"];
    $ParentID = $_POST["ParentID"];
    $ParentSts = $_POST["ParentSts"];
    $NoUrut = $_POST["NoUrut"];

    $post_data = [
        "MenuID" => $menu_id,
        "MenuNama" => $MenuNama,
        "MenuLink" => $MenuLink,
        "MenuDeskripsi" => $MenuDeskripsi,
        "MenuStatus" => $MenuStatus,
        "MenuIcon" => $MenuIcon,
        "MenuKategori" => $MenuKategori,
        "ParentID" => $ParentID,
        "ParentSts" => $ParentSts,
        "NoUrut" => $NoUrut,
    ];

    $ch = curl_init($baseUrl . "menu/" . $menu_id); // Gunakan URL dengan menu_id yang akan diupdate
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // Menggunakan metode PUT untuk update data
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($http_code === 200) {
        $_SESSION["message_menu_success"] = "menu berhasil diedit";
    } else {
        $_SESSION["message_menu_failed"] = "gagal edit menu";
    }

    // Redirect to menu.php using header
    header("Location: ../../pages/menu.php");
    exit();
}
?>