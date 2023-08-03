<?php
require_once "../config/server.php";

$url = $baseUrl . "auth/pajak";
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
    foreach ($data["data"] as $pajak) {
        echo "<tr>";
        echo "<td class='text-center'>" . $nomor . "</td>"; // add the number column
        echo "<td class='text-center'>" . $pajak["PajakID"] . "</td>";
        echo "<td class='text-center'><a href='#' class='namaPajakLink' data-pajakid='" . $pajak["PajakID"] . "'>" . $pajak["NamaPajak"] . "</a></td>";
        echo "<td class='text-center'>" . $pajak["ParentPajak"] . "</td>";
        echo "<td class='text-center'>" . $pajak["StsPajak"] . "</td>";
        echo "<td class='text-center'>" . $pajak["KetPajak"] . "</td>";
        echo "<td class='text-center'>" . $pajak["StsParent"] . "</td>";
        echo "<td class='text-center'>" . $pajak["FileID"] . "</td>";
        echo '<td>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-link btn-rounded btn-sm fw-bold text-danger" data-ripple-color="dark" data-toggle="modal" data-target="#deletePajak' .
            $pajak["PajakID"] .
            '"><i class="fas fa-trash"></i> </button>
            <div class="modal fade" id="deletePajak' .
            $pajak["PajakID"] .
            '" tabindex="-1" aria-labelledby="deletePajak" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deletePajak">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
        aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">Apakah yakin ingin menghapus data pajak ini?</div>
     
      <div class="modal-footer">
      <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
      <form method="POST" action="../actions/pajak/delete_pajak.php">
      <input type="hidden" name="PajakID" value="' .
            $pajak["PajakID"] .
            '">
            <button type="submit" class="btn btn-danger" >Hapus</button>
        </form>
      </div>
    </div>
    </div>
    </div>
            <button type="submit" class="btn btn-link btn-rounded btn-sm fw-bold text-warning" data-ripple-color="dark" data-toggle="modal" data-target="#editPajak' .
            $pajak["PajakID"] .
            '">  <i class="fas fa-edit"></i> </button>
            <div class="modal fade" id="editPajak' .
            $pajak["PajakID"] .
            '" tabindex="-1" aria-labelledby="editPajak" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPajak">Edit pajak</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="../actions/pajak/put_pajak.php" enctype="multipart/form-data">
                        <div class="mb-3" style="display:none;">
    <label class="form-label" for="PajakID">ID :</label>
    <div class="form-outline">
        <input type="hidden" id="PajakID" name="PajakID" class="form-control" value="' .
            $pajak["PajakID"] .
            '">
        </div>
    </div>
                                <div class="mb-3">
                                <label class="form-label" for="NamaPajak">Nama pajak : </label>
                                <div class="form-outline">
                                    <input type="text" id="NamaPajak" name="NamaPajak" class="form-control"
                                    value="' .
            $pajak["NamaPajak"] .
            '">
                                </div>
                            </div>
                            <div class="mb-3">
                            <label class="form-label" for="ParentPajak">Parent pajak : </label>
                            <div class="form-outline">
                                <input type="text" id="ParentPajak" name="ParentPajak" class="form-control"
                                value="' .
            $pajak["ParentPajak"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="StsPajak">Sts pajak : </label>
                        <div class="form-outline">
                            <input type="text" id="StsPajak" name="StsPajak" class="form-control"
                            value="' .
            $pajak["StsPajak"] .
            '">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="KetPajak">Ket pajak : </label>
                        <div class="form-outline">
                            <input type="text" id="KetPajak" name="KetPajak" class="form-control"
                            value="' .
            $pajak["KetPajak"] .
            '">
                        </div>
                    </div>
    
                    <div class="mb-3">
                        <label class="form-label" for="StsParent">Sts parent : </label>
                        <div class="form-outline">
                            <input type="text" id="StsParent" name="StsParent" class="form-control"
                            value="' .
            $pajak["StsParent"] .
            '">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label" for="FileID">Id File Pajak : </label>
                        <div class="form-outline">
                            <input type="text" id="FileID" name="FileID" class="form-control"
                            value="' .
            $pajak["FileID"] .
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
    echo '<div class="alert alert-warning" role="alert">Tidak ada data pajak</div>';
}
?>