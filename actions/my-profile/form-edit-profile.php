<?php
require_once("../config/server.php");

if (!isset($_SESSION["Username"])) {
    echo "Username tidak tersedia dalam sesi.";
    exit();
}

$username = $_SESSION["Username"];
$url = $baseUrl . "auth/users/" . $username;
$token = "Bearer " . $_SESSION["token"];

$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_HTTPHEADER, ["Authorization: " . $token]);

$data = curl_exec($curl);
curl_close($curl);

$user = json_decode($data, true);

if ($user === null) {
    echo "Gagal mengambil data pengguna dari API.";
} else {
    echo '
    <div class="col-xs-12 col-sm-9 offset-sm-1">
        <form class="form-horizontal" action="../actions/my-profile/put_myprofile.php" method="POST">
            <div class="card mx-auto" style="max-width: 400px;">
                <div class="card-body text-center">
                    <div class="card-header">
                        <h4 class="card-title text-center">My Profile</h4>
                    </div>
                    <div class="card-body text-center">
                        <img src="../assets/img/profile/' .
        $user["ProfilePicture"] .
        '" class="img-fluid"
                        alt="User avatar" ></img>
                    </div>
        
            <div class="card mx-auto" style="max-width: 400px;">
                <div class="card-body">
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Username</span>
                        <input type="text" class="form-control" name="Username" placeholder="Masukkan username baru" aria-label="Username" aria-describedby="basic-addon1" required />
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Email</span>
                        <input type="email" class="form-control" name="Email" placeholder="Masukkan email baru" aria-label="Email" aria-describedby="basic-addon1" required />
                    </div>
                    <div class="input-group mb-3">
                        <span class="input-group-text" id="basic-addon1">Password</span>
                        <input type="password" class="form-control" name="Password" placeholder="Masukkan password baru" aria-label="Password" aria-describedby="basic-addon1" required />
                    </div>
                    <input type="hidden" name="Username" value="' .
        $user["Username"] .
        '">
    </div>
    <div class="card-footer text-center">
    <button type="submit" class="btn btn-primary"><i class="fas fa-pen me-2"></i>Edit</button>
    </div>
    </div>
    </form>
    </div>
    </div>';
}
?>