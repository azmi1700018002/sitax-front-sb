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
    $GroupID = $_POST["GroupID"];
    $Username = $_POST["Username"];
    $NamaLengkap = $_POST["NamaLengkap"];
    $Email = $_POST["Email"];
    $Password = $_POST["Password"];
    $HostIP = $_POST["HostIP"];
    $StsUser = $_POST["StsUser"];
    $KdKantor = $_POST["KdKantor"];
    $ProfilePicture = $_FILES["profile_picture"];

    $post_data = [
        "GroupID" => $GroupID,
        "Username" => $Username,
        "NamaLengkap" => $NamaLengkap,
        "Email" => $Email,
        "Password" => $Password,
        "HostIP" => $HostIP,
        "StsUser" => $StsUser,
        "KdKantor" => $KdKantor,
        "profile_picture" => $ProfilePicture,
    ];

    // Append file data to the post data
    // Check if profile picture is uploaded or not
    if (empty($ProfilePicture["name"])) {
        // Set the default profile picture value from the API response
        $defaultProfilePicture = "default_profile.png"; // Change this to the default profile picture filename you expect from the API

        // Add the default profile picture value to the post data
        $post_data["profile_picture"] = $defaultProfilePicture;
    } else {
        // Append file data to the post data
        $post_data["profile_picture"] = new CURLFile($ProfilePicture["tmp_name"], $ProfilePicture["type"], $ProfilePicture["name"]);
    }


    $ch = curl_init($baseUrl . "register");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    if ($http_code === 201) {
        $_SESSION["message_user_success"] = "user berhasil ditambahkan";
    } else {
        $_SESSION["message_user_failed"] = "gagal menambahkan user";
    }

    // Redirect to product.php using header
    header("Location: ../../pages/user.php");
    exit();
}
?>