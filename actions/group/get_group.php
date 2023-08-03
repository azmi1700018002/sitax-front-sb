<?php
require_once "../config/server.php";

$url = $baseUrl . "group";
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
    foreach ($data["data"] as $group) {
        echo "<tr>";
        echo "<td class='text-center'>" . $nomor . "</td>"; // add the number column
        echo "<td class='text-center'>" . $group["GroupID"] . "</td>";
        echo "<td class='text-center'>" . $group["GroupNama"] . "</td>";
        echo "<td class='text-center'>" . $group["GroupDeskripsi"] . "</td>";
        echo '<td>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-link btn-rounded btn-sm fw-bold text-danger" data-ripple-color="dark" data-toggle="modal" data-target="#deleteGroup' .
            $group["GroupID"] .
            '"><i class="fas fa-trash"></i> </button>
            <div class="modal fade" id="deleteGroup' .
            $group["GroupID"] .
            '" tabindex="-1" aria-labelledby="deleteGroup" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteGroup">Modal title</h5>
        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">Apakah yakin ingin menghapus data group ini?</div>
     
      <div class="modal-footer">
      <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
      <form method="POST" action="../actions/group/delete_group.php">
      <input type="hidden" name="GroupID" value="' .
            $group["GroupID"] .
            '">
            <button type="submit" class="btn btn-danger" >Hapus</button>
        </form>
      </div>
    </div>
    </div>
    </div>
            <button type="submit" class="btn btn-link btn-rounded btn-sm fw-bold text-warning" data-ripple-color="dark" data-toggle="modal" data-target="#editGroup' .
            $group["GroupID"] .
            '">  <i class="fas fa-edit"></i> </button>
            <div class="modal fade" id="editGroup' .
            $group["GroupID"] .
            '" tabindex="-1" aria-labelledby="editGroup" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editGroup">Edit group</h5>
                        <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" action="../actions/group/put_group.php" enctype="multipart/form-data">
                        <div class="mb-3" style="display:none;">
    <label class="form-label" for="GroupID">ID :</label>
    <div class="form-outline">
        <input type="hidden" id="GroupID" name="GroupID" class="form-control" value="' .
            $group["GroupID"] .
            '">
        </div>
    </div>
                                <div class="mb-3">
                                <label class="form-label" for="GroupNama">Nama Group : </label>
                                <div class="form-outline">
                                    <input type="text" id="GroupNama" name="GroupNama" class="form-control"
                                    value="' .
            $group["GroupNama"] .
            '">
                                </div>
                            </div>
                            <div class="mb-3">
                            <label class="form-label" for="GroupDeskripsi">Deskripsi Group : </label>
                            <div class="form-outline">
                                <input type="text" id="GroupDeskripsi" name="GroupDeskripsi" class="form-control"
                                value="' .
            $group["GroupDeskripsi"] .
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
    echo '<div class="alert alert-warning" role="alert">Tidak ada data group</div>';
}
?>