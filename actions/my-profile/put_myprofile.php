<?php
require_once("../../config/server.php");

session_start();

if (!isset($_SESSION["UserID"])) {
    echo "UserID tidak tersedia dalam sesi.";
    exit();
}

$id_user = $_SESSION["UserID"];
$url = $baseUrl . "auth/users/" . $id_user;
$token = "Bearer " . $_SESSION["token"];

// Memeriksa apakah data pengguna dikirim melalui formulir POST
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Mengambil nilai-nilai yang diisi oleh pengguna
    $username = $_POST["Username"];
    $email = $_POST["Email"];
    $password = $_POST["Password"];

    // Membuat array data yang akan dikirimkan ke API
    $data = [
        "Username" => $username,
        "Email" => $email,
        "Password" => $password
    ];

    // Mengirim permintaan ke API untuk memperbarui data pengguna
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($curl, CURLOPT_HTTPHEADER, ["Authorization: " . $token]);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

    $response = curl_exec($curl);
    $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
    curl_close($curl);

    // Memeriksa kode respons HTTP
    if ($httpCode === 200) {
        echo "Data pengguna berhasil diperbarui.";
    } else {
        echo "Gagal memperbarui data pengguna.";
    }
    header("Location: ../../index.php");
    exit();
}
?>