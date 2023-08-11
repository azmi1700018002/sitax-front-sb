<?php
// require_once "../config/server.php";

// $url = $baseUrl . "menu";
// $token = $_SESSION["token"];
// $headers = ["Authorization: Bearer " . $token];
// $curl = curl_init();
// curl_setopt_array($curl, [
//     CURLOPT_URL => $url,
//     CURLOPT_RETURNTRANSFER => true,
//     CURLOPT_ENCODING => "",
//     CURLOPT_MAXREDIRS => 10,
//     CURLOPT_TIMEOUT => 0,
//     CURLOPT_FOLLOWLOCATION => true,
//     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//     CURLOPT_CUSTOMREQUEST => "GET",
//     CURLOPT_HTTPHEADER => $headers,
// ]);
// $response = curl_exec($curl);
// curl_close($curl);
// $data = json_decode($response, true);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menuID'])) {
    $menuID = $_POST['menuID'];
    // var_dump($menuID);

    // Loop through each menu item in the data
    foreach ($data["data"] as $menuItem) {
        foreach ($menuItem["MenuIDfk"] as $menuIDfk) {
            // Check if IsCreated is 1 and MenuID matches current page's MenuID
            if ($menuIDfk["IsCreated"] === "1" && $menuIDfk["MenuID"] === $menuID) {
                // Add the button HTML
                echo '
                <button type="button" class="btn btn-outline-primary ms-auto" data-ripple-color="dark"
                data-toggle="modal" data-target="#tambahGroup">
                <i class="fas fa-plus me-2"></i>
                Group
                </button>';
                break; // No need to continue checking other MenuIDfk entries for this menu item
            }
        }
    }

}
?>