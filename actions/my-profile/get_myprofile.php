<?php
require_once("../config/server.php");

// Mendapatkan Username dari session (asumsikan Username tersimpan dalam $_SESSION['Username'])
$username = $_SESSION["Username"];

// Membentuk URL API dengan Username yang spesifik
$url = $baseUrl . "auth/users/" . $username;

// Menggunakan cURL
$curl = curl_init();
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Menambahkan token sebagai header Authorization
$token = "Bearer " . $_SESSION["token"];
curl_setopt($curl, CURLOPT_HTTPHEADER, ["Authorization: " . $token]);

$data = curl_exec($curl);
curl_close($curl);

// Mengubah data JSON menjadi array
$user = json_decode($data, true);

// Memeriksa apakah pengambilan data berhasil
if ($user === null) {
    echo "Gagal mengambil data pengguna dari API.";
} else {
    // Menampilkan data pengguna
    echo '
    <div class="container">
    <div class="row">
    <div class="col-xs-12 col-sm-9 offset-sm-1">
            <form class="form-horizontal" action="../pages/form-edit-profile.php" method="POST">
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
                
                    <div class="card-body">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Nama Lengkap</span>
                            <input type="text" class="form-control" value="' .
        $user["NamaLengkap"] .
        '" aria-label="NamaLengkap" aria-describedby="basic-addon1" readonly />
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Email</span>
                            <input type="text" class="form-control" value="' .
        $user["Email"] .
        '" aria-label="Email"
        aria-describedby="basic-addon1" readonly />
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-pen me-2"></i>Edit</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
';
}
?>