<?php
require_once "../config/server.php";

$url1 = $baseUrl . "kewenangan";
$url2 = $baseUrl . "menu"; // URL kedua

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
    // Buat array asosiatif untuk menyimpan keterangan "Ket" berdasarkan "MenuID" dari $data2
    $menuNameArr = [];
    foreach ($data2["data"] as $menu) {
        $menuNameArr[$menu["MenuID"]] = $menu["MenuNama"];
    }

    // Tampilkan data dari $data1["data"] dalam tabel
    if (isset($data1["data"])) {
        $nomor = 1;
        foreach ($data1["data"] as $kewenangan) {
            $currentUserStsUser = $kewenangan["MenuID"];
            echo "<tr>";
            echo "<td class='text-center'>" . $nomor . "</td>";
            echo "<td class='text-center'>" . $kewenangan["GroupID"] . "</td>";
            // Tampilkan MenuNama berdasarkan MenuID
            if (isset($menuNameArr[$kewenangan["MenuID"]])) {
                echo "<td class='text-center'>" .
                    $menuNameArr[$kewenangan["MenuID"]] .
                    "</td>";
            } else {
                echo "<td class='text-center'>Tidak Diketahui</td>"; // Tampilkan pesan jika tidak ada keterangan yang sesuai
            }
            echo "<td class='text-center'>" .
                $kewenangan["IsCreated"] .
                "</td>";
            echo "<td class='text-center'>" .
                $kewenangan["IsUpdated"] .
                "</td>";
            echo "<td class='text-center'>" .
                $kewenangan["IsDeleted"] .
                "</td>";

            echo '<td>
            <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-danger btn-circle btn-sm mr-2" data-ripple-color="dark" data-toggle="modal" data-target="#deleteKewenangan' .
                $kewenangan["GroupID"] . '-' . $kewenangan["MenuID"] .
                '"><i class="fas fa-trash"></i> </button>
          <div class="modal fade" id="deleteKewenangan' .
                $kewenangan["GroupID"] . '-' . $kewenangan["MenuID"] .
                '" tabindex="-1" aria-labelledby="deleteKewenangan" aria-hidden="true">
    <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteKewenangan">Delete Kewenangan</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
        aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">Apakah yakin ingin menghapus data kewenangan ini?</div>
     
      <div class="modal-footer">
      <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
      <form method="POST" action="../actions/kewenangan/delete_kewenangan.php">
      <input type="hidden" name="GroupID" value="' .
                $kewenangan["GroupID"] .
                '">
                <input type="hidden" name="MenuID" value="' .
                $kewenangan["MenuID"] .
                '">
            <button type="submit" class="btn btn-danger" >Hapus</button>
        </form>
      </div>
    </div>
    </div>
    </div>  
    
                <button type="submit" class="btn btn-warning btn-circle btn-sm" data-ripple-color="dark" data-toggle="modal" data-target="#editKewenangan' .
                $kewenangan["GroupID"] . '-' . $kewenangan["MenuID"] .
                '">  <i class="fas fa-edit"></i> </button>
                <div class="modal fade" id="editKewenangan' .
                $kewenangan["GroupID"] . '-' . $kewenangan["MenuID"] .
                '" tabindex="-1" aria-labelledby="editKewenangan" aria-hidden="true">
                <div class="modal-dialog">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="editKewenangan">Edit kewenangan</h5>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                            aria-hidden="true">×</span></button>
                        </div>
                        <div class="modal-body">
                            <form method="POST" action="../actions/kewenangan/put_kewenangan.php" enctype="multipart/form-data">
                            <div class="mb-3" style="display:none;">
        <label class="form-label" for="GroupID">ID :</label>
        <div class="form-outline">
            <input type="hidden" id="GroupID" name="GroupID" class="form-control" value="' .
                $kewenangan["GroupID"] .
                '">
            </div>
        </div>
                                <div class="mb-3">
                                <div class="form-group">
                                            <label for="GrupID">Group :</label>
                                            <div class="input-group">
                                                <select class="form-control" name="GroupID"
                                                    aria-label="Default select example">';
            // Assuming $stsUserByUsername is already populated with data from the server
            foreach ($data2["data"] as $menu) {
                $menuID = $menu["MenuID"];
                $menuName = $menu["MenuNama"];
                $selected = $menuID === $currentUserStsUser ? "selected" : "";
                echo "<option value='$menuID' $selected>$menuName</option>";
            }
            echo '</select>
                                </div>
                                </div>
                                </div>
                        
            <div class="mb-3">
            <div class="form-group">
                                            <label class="form-label" for="IsCreated">IsCreated :</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="IsCreated"
                                                    id="IsCreated1" value="1" ' .
                ($kewenangan["IsCreated"] == 1 ? "checked" : "") .
                '/>
                                                <label class="form-check-label" for="IsCreated1"> Aktif </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="IsCreated"
                                                    id="IsCreated0" value="0" ' .
                ($kewenangan["IsCreated"] == 0 ? "checked" : "") .
                '/>
                                                <label class="form-check-label" for="IsCreated0"> Tidak Aktif </label>
                                            </div>
                                        </div>
        </div>
        <div class="mb-3">
        <div class="form-group">
                                            <label class="form-label" for="IsUpdated">IsUpdated :</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="IsUpdated"
                                                    id="IsUpdated1"  value="1" ' .
                ($kewenangan["IsCreated"] == 1 ? "checked" : "") .
                '/>
                                                <label class="form-check-label" for="IsUpdated1"> Aktif </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="IsUpdated"
                                                    id="IsUpdated0"  value="0" ' .
                ($kewenangan["IsCreated"] == 0 ? "checked" : "") .
                '/>
                                                <label class="form-check-label" for="IsUpdated0"> Tidak Aktif </label>
                                            </div>
                                        </div>
    </div>
    
    <div class="mb-3">
    <label class="form-label" for="IsDeleted">IsDeleted :</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="IsDeleted"
                                                id="IsDeleted1" value="1" ' .
                ($kewenangan["IsCreated"] == 1 ? "checked" : "") .
                '/>
                                            <label class="form-check-label" for="IsDeleted1"> Aktif </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="IsDeleted"
                                                id="IsDeleted0"  value="0" ' .
                ($kewenangan["IsCreated"] == 0 ? "checked" : "") .
                '/>
                                            <label class="form-check-label" for="IsDeleted0"> Tidak Aktif </label>
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
        echo '<div class="alert alert-warning" role="alert">Tidak ada data kewenangan</div>';
    }
} else {
    echo '<div class="alert alert-warning" role="alert">Tidak ada data referensi</div>';
}
?>