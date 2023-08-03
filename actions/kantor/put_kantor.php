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
    $kd_kantor = $_POST["KdKantor"];
    $KdInduk = $_POST["KdInduk"];
    $AlamatKantor = $_POST["AlamatKantor"];
    $KotaAlamat = $_POST["KotaAlamat"];
    $NoTlp = $_POST["NoTlp"];
    $NoFaks = $_POST["NoFaks"];
    $KdStsKantor = $_POST["KdStsKantor"];
    $KdJnsKantor = $_POST["KdJnsKantor"];
    $KdBank1 = $_POST["KdBank1"];
    $KdBank2 = $_POST["KdBank2"];
    $NPWP = $_POST["NPWP"];
    $KdPejabat1 = $_POST["KdPejabat1"];
    $KdPejabat2 = $_POST["KdPejabat2"];
    $FlgData = $_POST["FlgData"];
    $KdKantorLama = $_POST["KdKantorLama"];
    $KdLokasi = $_POST["KdLokasi"];

    $post_data = [
        "KdKantor" => $kd_kantor,
        "KdInduk" => $KdInduk,
        "AlamatKantor" => $AlamatKantor,
        "KotaAlamat" => $KotaAlamat,
        "NoTlp" => $NoTlp,
        "NoFaks" => $NoFaks,
        "KdStsKantor" => $KdStsKantor,
        "KdJnsKantor" => $KdJnsKantor,
        "KdBank1" => $KdBank1,
        "KdBank2" => $KdBank2,
        "NPWP" => $NPWP,
        "KdPejabat1" => $KdPejabat1,
        "KdPejabat2" => $KdPejabat2,
        "FlgData" => $FlgData,
        "KdKantorLama" => $KdKantorLama,
        "KdLokasi" => $KdLokasi,
    ];

    $ch = curl_init($baseUrl . "kantor/" . $kd_kantor); // Gunakan URL dengan KdKantor yang akan diupdate
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT"); // Menggunakan metode PUT untuk update data
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    
    curl_close($ch);

    if ($http_code === 200) {
        $_SESSION["message_kantor_success"] = "kantor berhasil diedit";
    } else {
        $_SESSION["message_kantor_failed"] = "gagal edit kantor";
    }

    // Redirect to kantor.php using header
    header("Location: ../../pages/kantor.php");
    exit();
}
?>