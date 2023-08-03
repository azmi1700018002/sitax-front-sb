<?php
require_once "../config/server.php";

$url1 = $baseUrl . "auth/users";
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
            $currentUserStsUser = $user["StsUser"];
            echo "<tr>";
            echo "<td class='text-center'>" . $nomor . "</td>";
            echo "<td class='text-center'>" . $user["NamaLengkap"] . "</td>";
            echo "<td class='text-center'>" . $user["Email"] . "</td>";
            echo "<td class='text-center'>" . $user["HostIP"] . "</td>";
            echo "<td class='text-center'>" . $user["KdKantor"] . "</td>";
            // Tambahkan kondisi untuk menampilkan keterangan "Ket" berdasarkan "StsUser"
            if (isset($stsUserKet[$user["StsUser"]])) {
                echo "<td class='text-center'>" . $stsUserKet[$user["StsUser"]] . "</td>";
            } else {
                echo "<td class='text-center'>Tidak Diketahui</td>"; // Tampilkan pesan jika tidak ada keterangan yang sesuai
            }
            echo '<td>
            <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-link btn-rounded btn-sm fw-bold text-danger" data-ripple-color="dark" data-toggle="modal" data-target="#deleteUser' .
                $user["Username"] .
                '"><i class="fas fa-trash"></i> </button>
          <div class="modal fade" id="deleteUser' .
                $user["Username"] .
                '" tabindex="-1" aria-labelledby="deleteUser" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteUser">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
        aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">Apakah yakin ingin menghapus data user ini?</div>
     
      <div class="modal-footer">
      <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
      <form method="POST" action="../actions/user/delete_user.php">
      <input type="hidden" name="Username" value="' .
                $user["Username"] .
                '">
            <button type="submit" class="btn btn-danger" >Hapus</button>
        </form>
      </div>
    </div>
    </div>
    </div>  
    
                <button type="submit" class="btn btn-link btn-rounded btn-sm fw-bold text-warning" data-ripple-color="dark" data-toggle="modal" data-target="#editUser' .
                $user["Username"] .
                '">  <i class="fas fa-edit"></i> </button>
                <div class="modal fade" id="editUser' .
                $user["Username"] .
                '" tabindex="-1" aria-labelledby="editUser" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editUser">Edit user</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="../actions/user/put_user.php" enctype="multipart/form-data">
                            <div class="mb-3" style="display:none;">
        <label class="form-label" for="Username">ID :</label>
        <div class="form-outline">
            <input type="hidden" id="Username" name="Username" class="form-control" value="' .
                $user["Username"] .
                '">
            </div>
        </div>
                                    <div class="mb-3">
                                    <label class="form-label" for="NamaLengkap">Nama Lengkap : </label>
                                    <div class="form-outline">
                                        <input type="text" id="NamaLengkap" name="NamaLengkap" class="form-control"
                                        value="' .
                $user["NamaLengkap"] .
                '">
                                    </div>
                                </div>
                                <div class="mb-3">
                                <label class="form-label" for="Email">Email : </label>
                                <div class="form-outline">
                                    <input type="text" id="Email" name="Email" class="form-control"
                                    value="' .
                $user["Email"] .
                '">
                                </div>
                            </div>
        
                                <div class="mb-3">
                                    <label class="form-label" for="HostIP">HostIP : </label>
                                    <div class="form-outline">
                                        <input type="text" id="HostIP" name="HostIP" class="form-control"
                                        value="' .
                $user["HostIP"] .
                '">
                                    </div>
                                </div>
                                <div class="mb-3">
                                <div class="form-group">
                                <label for="StsUser">Status :</label>
                                <div class="input-group">
                                    <select class="form-control" name="StsUser"
                                        aria-label="Default select example">';
            // Assuming $stsUserByUsername is already populated with data from the server
            foreach ($data2["data"] as $referensi) {
                if ($referensi["GrpID"] === "StsUser") {
                    $stsUser = $referensi["Ref"];
                    $keterangan = $referensi["Ket"];
                    // Check if the current option's "GrpID" matches the user's "StsUser"
                    $selected = ($stsUser === $currentUserStsUser) ? 'selected' : '';
                    echo "<option value='$stsUser' $selected>$keterangan</option>";
                }
            }
            echo '</select>
                        </div>
                        </div>
                            </div>                    
                                <div class="mb-3">
                                <label class="form-label" for="KdKantor">KdKantor : </label>
                                <div class="form-outline">
                                    <input type="text" id="KdKantor" name="KdKantor" class="form-control"
                                    value="' .
                $user["KdKantor"] .
                '">
                                </div>
                            </div>
                                
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-success">Edit</button>
                        </div>
                        </form>
                    </div>
                </div>
            </div>
        </td>';
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