<?php
require_once("../config/server.php");

$url = $baseUrl . "kantor";
$token = $_SESSION["token"];
$headers = ["Authorization: Bearer " . $token];
$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => $headers,
]);
$response = curl_exec($curl);
curl_close($curl);
$data = json_decode($response, true);
if (isset($data["data"])) {
    $nomor = 1; // initialize the variable
    foreach ($data["data"] as $kantor) {
        echo "<tr>";
        echo "<td class='text-center'>" . $nomor . "</td>"; // add the number column
        echo "<td class='text-center'>" . $kantor["KdKantor"] . "</td>";
        echo "<td class='text-center'>" . $kantor["KdInduk"] . "</td>";
        echo "<td class='text-center'>" . $kantor["AlamatKantor"] . "</td>";
        echo "<td class='text-center'>" . $kantor["KotaAlamat"] . "</td>";
        echo "<td class='text-center'>" . $kantor["NoTlp"] . "</td>";
        echo "<td class='text-center'>" . $kantor["NoFaks"] . "</td>";
        echo "<td class='text-center'>" . $kantor["KdStsKantor"] . "</td>";
        echo "<td class='text-center'>" . $kantor["KdJnsKantor"] . "</td>";
        echo "<td class='text-center'>" . $kantor["KdBank1"] . "</td>";
        echo "<td class='text-center'>" . $kantor["KdBank2"] . "</td>";
        echo "<td class='text-center'>" . $kantor["NPWP"] . "</td>";
        echo "<td class='text-center'>" . $kantor["KdPejabat1"] . "</td>";
        echo "<td class='text-center'>" . $kantor["KdPejabat2"] . "</td>";
        echo "<td class='text-center'>" . $kantor["FlgData"] . "</td>";
        echo "<td class='text-center'>" . $kantor["KdKantorLama"] . "</td>";
        echo "<td class='text-center'>" . $kantor["KdLokasi"] . "</td>";
        echo '<td>
        <div class="d-flex justify-content-center">

        <button type="submit" class="btn btn-link btn-rounded btn-sm fw-bold text-danger" data-ripple-color="dark" data-toggle="modal" data-target="#deleteKantor' .
            $kantor["KdKantor"] .
            '"><i class="fas fa-trash"></i> </button>
          <div class="modal fade" id="deleteKantor' .
            $kantor["KdKantor"] .
            '" tabindex="-1" aria-labelledby="deleteKantor" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteKantor">Modal title</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">Apakah yakin ingin menghapus data kantor ini ?</div>
     
      <div class="modal-footer">
      <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
      <form method="POST" action="../actions/kantor/delete_kantor.php">
      <input type="hidden" name="KdKantor" value="' .
            $kantor["KdKantor"] .
            '">
      <button type="submit" class="btn btn-danger" >Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>  
        <button type="submit" class="btn btn-link btn-rounded btn-sm fw-bold text-warning" data-ripple-color="dark" data-toggle="modal" data-target="#editKantor' .
            $kantor["KdKantor"] .
            '">  <i class="fas fa-edit"></i> </button>
        <div class="modal fade" id="editKantor' .
            $kantor["KdKantor"] .
            '" tabindex="-1" aria-labelledby="ediMenu" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ediMenu">Edit kantor</h5>
                    <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="../actions/kantor/put_kantor.php" enctype="multipart/form-data">
                    <div class="mb-3" style="display:none;">
<label class="form-label" for="GroupID">ID :</label>
<div class="form-outline">
    <input type="hidden" id="KdKantor" name="KdKantor" class="form-control" value="' .
            $kantor["KdKantor"] .
            '">
    </div>
</div>
                            <div class="mb-3">
                            <label class="form-label" for="KdInduk">Kd Induk : </label>
                            <div class="form-outline">
                                <input type="text" id="KdInduk" name="KdInduk" class="form-control"
                                value="' .
            $kantor["KdInduk"] .
            '">
                            </div>
                        </div>
                            <div class="mb-3">
                            <label class="form-label" for="AlamatKantor">Alamat kantor : </label>
                            <div class="form-outline">
                                <input type="text" id="AlamatKantor" name="AlamatKantor" class="form-control"
                                value="' .
            $kantor["AlamatKantor"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="KotaAlamat">Kota : </label>
                        <div class="form-outline">
                                <input type="text" id="KotaAlamat" name="KotaAlamat" class="form-control"
                                value="' .
            $kantor["KotaAlamat"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="NoTlp">Telp : </label>
                        <div class="form-outline">
                                <input type="text" id="NoTlp" name="NoTlp" class="form-control"
                                value="' .
            $kantor["NoTlp"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="NoFaks">Faks : </label>
                        <div class="form-outline">
                                <input type="text" id="NoFaks" name="NoFaks" class="form-control"
                                value="' .
            $kantor["NoFaks"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="KdStsKantor">Kd Sts Kantor : </label>
                        <div class="form-outline">
                                <input type="text" id="KdStsKantor" name="KdStsKantor" class="form-control"
                                value="' .
            $kantor["KdStsKantor"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="KdJnsKantor">Kd Jns Kantor : </label>
                        <div class="form-outline">
                                <input type="text" id="KdJnsKantor" name="KdJnsKantor" class="form-control"
                                value="' .
            $kantor["KdJnsKantor"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="KdBank1">KdBank1 : </label>
                        <div class="form-outline">
                                <input type="text" id="KdBank1" name="KdBank1" class="form-control"
                                value="' .
            $kantor["KdBank1"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="KdBank2">KdBank2 : </label>
                        <div class="form-outline">
                                <input type="text" id="KdBank2" name="KdBank2" class="form-control"
                                value="' .
            $kantor["KdBank2"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="NPWP">NPWP : </label>
                        <div class="form-outline">
                                <input type="text" id="NPWP" name="NPWP" class="form-control"
                                value="' .
            $kantor["NPWP"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="KdPejabat1">KdPejabat1 : </label>
                        <div class="form-outline">
                                <input type="text" id="KdPejabat1" name="KdPejabat1" class="form-control"
                                value="' .
            $kantor["KdPejabat1"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="KdPejabat2">KdPejabat2 : </label>
                        <div class="form-outline">
                                <input type="text" id="KdPejabat2" name="KdPejabat2" class="form-control"
                                value="' .
            $kantor["KdPejabat2"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="FlgData">FlgData : </label>
                        <div class="form-outline">
                                <input type="text" id="FlgData" name="FlgData" class="form-control"
                                value="' .
            $kantor["FlgData"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="KdKantorLama">KdKantorLama : </label>
                        <div class="form-outline">
                                <input type="text" id="KdKantorLama" name="KdKantorLama" class="form-control"
                                value="' .
            $kantor["KdKantorLama"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="KdLokasi">KdLokasi : </label>
                        <div class="form-outline">
                                <input type="text" id="KdLokasi" name="KdLokasi" class="form-control"
                                value="' .
            $kantor["KdLokasi"] .
            '">
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
    <button type="submit" class="btn btn-link btn-rounded btn-sm fw-bold text-danger" data-ripple-color="dark" data-toggle="modal" data-target="#deleteKantor' .
            $kantor["KdKantor"] .
            '"><i class="fas fa-trash"></i> </button>
                    <div class="modal fade" id="deleteKantor' .
            $kantor["KdKantor"] .
            '" tabindex="-1" aria-labelledby="deleteKantor" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteKantor">Modal title</h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">Apakah yakin ingin menghapus data kantor ini?</div>
        
        <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
        <form method="POST" action="../actions/kantor/delete_kantor.php">
        <input type="hidden" name="KdKantor" value="' .
            $kantor["KdKantor"] .
            '">
                <button type="submit" class="btn btn-danger" >Hapus</button>
            </form>
        </div>
        </div>
    </div>

    </td>';
        echo "</tr>";
        $nomor++; // increment the variable
    }
} else {
    echo '<div class="alert alert-warning" role="alert">Tidak ada data kantor</div>';
}
?>