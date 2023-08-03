<?php
require_once("config/server.php");

function validateLogin($email, $password)
{
    // validasi email dan password
    if (empty($email) || empty($password)) {
        return false;
    } else {
        return true;
    }
}

session_start();
if (isset($_POST["submit"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    if (!validateLogin($email, $password)) {
        $_SESSION["login_error"] = "Email dan password tidak boleh kosong";
        header("Location: index.php");
        exit();
    }

    // Melakukan request ke server
    $url = $baseUrl . "login";
    $data = ["email" => $email, "password" => $password];

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);

    $result = curl_exec($ch);
    curl_close($ch);

    if ($result === false) {
        $_SESSION["login_error"] = "Error logging in";
    } else {
        $response = json_decode($result, true);
        if (isset($response["error"])) {
            $_SESSION["login_error"] = $response["error"];
        } else {
            $stsUser = $response["StsUser"];

            if ($stsUser == 0) {
                $_SESSION["login_error"] = "Anda tidak bisa login karena status user tidak aktif.";
            } elseif ($stsUser == 1) {
                $_SESSION["token"] = $response["token"];
                $_SESSION["expired"] = $response["expired"];
                $_SESSION["ProfilePicture"] = $response["ProfilePicture"];
                $_SESSION["Username"] = $response["Username"];
                $_SESSION["StsUser"] = $response["StsUser"];
                $_SESSION["GroupID"] = $response["GroupID"];
                $_SESSION["login_success"] = "Berhasil login";
                header("Location: pages/dashboard.php");
                exit();
            } else {
                $_SESSION["login_error"] = "Status user tidak valid.";
            }

            header("Location: index.php");
            exit();
        }
    }
}
?>