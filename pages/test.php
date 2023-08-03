<?php
session_start();
require_once "../config/server.php";

$url1 = $baseUrl . "users";
$url2 = $baseUrl . "referensi"; // URL kedua

$token = $_SESSION["token"];
$headers = ["Authorization: Bearer " . $token];

// Permintaan pertama menggunakan $url1
$curl1 = curl_init();
curl_setopt_array($curl1, [
    CURLOPT_URL => $url1,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => $headers,
]);
$response1 = curl_exec($curl1);
curl_close($curl1);
$data1 = json_decode($response1, true);

// Permintaan kedua menggunakan $url2
$curl2 = curl_init();
curl_setopt_array($curl2, [
    CURLOPT_URL => $url2,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => $headers,
]);
$response2 = curl_exec($curl2);
curl_close($curl2);
$data2 = json_decode($response2, true);

// Periksa apakah ada data valid dalam $data2["data"]
if (isset($data2["data"])) {
    // Buat array asosiatif untuk menyimpan keterangan "Ket" berdasarkan "GrpID" dari $data2
    $stsUserKet = [];
    foreach ($data2["data"] as $referensi) {
        if ($referensi["GrpID"] === "StsUser") {
            $stsUserKet[$referensi["Ref"]] = $referensi["Ket"];
        }
    }

    // Tampilkan data dari $data1["data"] dalam tabel
    if (isset($data1["data"])) {
        $nomor = 1;
        foreach ($data1["data"] as $user) {
            echo "<tr>";
            echo "<td class='text-center'>" . $nomor . "</td>";
            echo "<td class='text-center'>" . $user["NamaLengkap"] . "</td>";
            echo "<td class='text-center'>" . $user["Email"] . "</td>";
            echo "<td class='text-center'>" . $user["HostIP"] . "</td>";

            // Tambahkan kondisi untuk menampilkan keterangan "Ket" berdasarkan "StsUser"
            if (isset($stsUserKet[$user["StsUser"]])) {
                echo "<td class='text-center'>" . $stsUserKet[$user["StsUser"]] . "</td>";
            } else {
                echo "<td class='text-center'>Tidak Diketahui</td>"; // Tampilkan pesan jika tidak ada keterangan yang sesuai
            }

            echo "</tr>";
            $nomor++;
        }
    } else {
        echo '<div class="alert alert-warning" role="alert">Tidak ada data user</div>';
    }
} else {
    echo '<div class="alert alert-warning" role="alert">Tidak ada data referensi</div>';
}



?>