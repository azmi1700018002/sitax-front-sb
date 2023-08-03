<?php
require_once "../config/server.php";

$url = $baseUrl . "auth/pajak-detail";
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
    foreach ($data["data"] as $pajak_detail) {
        echo "<tr>";
        echo "<td class='text-center'>" . $nomor . "</td>"; // add the number column
        echo "<td class='text-center'>" . $pajak_detail["PajakDetailID"] . "</td>";
        echo "<td class='text-center'>" . $pajak_detail["PajakID"] . "</td>";
        echo "<td class='text-center'>" . $pajak_detail["Ppn"] . "</td>";
        echo "<td class='text-center'>" . $pajak_detail["Pasal23"] . "</td>";
        echo "<td class='text-center'>" . $pajak_detail["PphFinal"] . "</td>";
        echo "<td class='text-center'>" . $pajak_detail["PajakLain"] . "</td>";
        echo "<td class='text-center'>" . $pajak_detail["Keterangan"] . "</td>";
        echo '<td>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-danger btn-circle btn-sm mr-2" data-ripple-color="dark" data-toggle="modal" data-target="#deletePajakDetail' .
            $pajak_detail["PajakDetailID"] .
            '"><i class="fas fa-trash"></i> </button>
            <div class="modal fade" id="deletePajakDetail' .
            $pajak_detail["PajakDetailID"] .
            '" tabindex="-1" aria-labelledby="deletePajakDetail" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deletePajakDetail">Delete Detail Pajak</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
        aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">Apakah yakin ingin menghapus data pajak-detail ini?</div>
     
      <div class="modal-footer">
      <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
      <form method="POST" action="../actions/pajak-detail/delete_pajak_detail.php">
      <input type="hidden" name="PajakDetailID" value="' .
            $pajak_detail["PajakDetailID"] .
            '">
            <button type="submit" class="btn btn-danger" >Hapus</button>
        </form>
      </div>
    </div>
    </div>
    </div>
            <button type="submit" class="btn btn-warning btn-circle btn-sm" data-ripple-color="dark" data-toggle="modal" data-target="#editPajakDetail' .
            $pajak_detail["PajakDetailID"] .
            '">  <i class="fas fa-edit"></i> </button>
            <div class="modal fade" id="editPajakDetail' .
            $pajak_detail["PajakDetailID"] .
            '" tabindex="-1" aria-labelledby="editPajakDetail" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPajakDetail">Edit pajak-detail</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="../actions/pajak-detail/put_pajak_detail.php" enctype="multipart/form-data">
                        <div class="mb-3" style="display:none;">
    <label class="form-label" for="PajakDetailID">ID :</label>
    <div class="form-outline">
        <input type="hidden" id="PajakDetailID" name="PajakDetailID" class="form-control" value="' .
            $pajak_detail["PajakDetailID"] .
            '">
        </div>
    </div>
                                <div class="mb-3">
                                <label class="form-label" for="PajakID">ID Pajak : </label>
                                <div class="form-outline">
                                    <input type="text" id="PajakID" name="PajakID" class="form-control"
                                    value="' .
            $pajak_detail["PajakID"] .
            '">
                                </div>
                            </div>
                            <div class="mb-3">
                            <label class="form-label" for="Ppn">Ppn : </label>
                            <div class="form-outline">
                                <input type="text" id="Ppn" name="Ppn" class="form-control"
                                value="' .
            $pajak_detail["Ppn"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="Pasal23">Pasal23 : </label>
                        <div class="form-outline">
                            <input type="text" id="Pasal23" name="Pasal23" class="form-control"
                            value="' .
            $pajak_detail["Pasal23"] .
            '">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="PphFinal">Pph Final : </label>
                        <div class="form-outline">
                            <input type="text" id="PphFinal" name="PphFinal" class="form-control"
                            value="' .
            $pajak_detail["PphFinal"] .
            '">
                        </div>
                    </div>
    
                    <div class="mb-3">
                        <label class="form-label" for="PajakLain">Pajak Lain : </label>
                        <div class="form-outline">
                            <input type="text" id="PajakLain" name="PajakLain" class="form-control"
                            value="' .
            $pajak_detail["PajakLain"] .
            '">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="Keterangan">Keterangan : </label>
                        <div class="form-outline">
                            <input type="text" id="Keterangan" name="Keterangan" class="form-control"
                            value="' .
            $pajak_detail["Keterangan"] .
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
    </td>';
        echo "</tr>";
        $nomor++; // increment the variable
    }
} else {
    echo '<div class="alert alert-warning" role="alert">Tidak ada data pajak-detail</div>';
}
?>