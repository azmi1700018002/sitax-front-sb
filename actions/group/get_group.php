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
$data1 = json_decode($response, true);
if (isset($data1["data"])) {
    $nomor = 1; // initialize the variable
    foreach ($data1["data"] as $group) {
        echo "<tr>";
        echo "<td class='text-center'>" . $nomor . "</td>"; // add the number column
        echo "<td class='text-center'>" . $group["GroupID"] . "</td>";
        echo "<td class='text-center'>" . $group["GroupNama"] . "</td>";
        echo "<td class='text-center'>" . $group["GroupDeskripsi"] . "</td>";
        // Loop through each menu item in the data
        foreach ($data["data"] as $menuItem) {
            $showButtons = false; // Flag to determine whether to show buttons or not

            foreach ($menuItem["MenuIDfk"] as $menuIDfk) {
                // Check if MenuID, GroupID, and IsDeleted condition matches
                if ($menuIDfk["MenuID"] === $activeMenuID && $menuIDfk["GroupID"] === $groupIDToCheck) {
                    if ($menuIDfk["IsDeleted"] === "1" || $menuIDfk["IsUpdated"] === "1") {
                        $showButtons = true;
                    }
                    break; // No need to continue checking other MenuIDfk entries for this menu item
                }
            }

            if ($showButtons) {
                echo '<td>
    <div class="d-flex justify-content-center">';

                if ($menuIDfk["IsDeleted"] === "1") {
                    echo '<button type="submit" class="btn btn-danger btn-circle btn-sm mr-2" data-ripple-color="dark" data-toggle="modal" data-target="#deleteGroup' .
                        $group["GroupID"] .
                        '"><i class="fas fa-trash"></i></button>';
                }

                if ($menuIDfk["IsUpdated"] === "1") {
                    echo '<button type="submit" class="btn btn-warning btn-circle btn-sm mr-2" data-ripple-color="dark" data-toggle="modal" data-target="#editGroup' .
                        $group["GroupID"] .
                        '"><i class="fas fa-edit"></i></button>';
                }

                echo '</div></td>';
            }
        }

        echo '<div class="modal fade" id="deleteGroup' .
            $group["GroupID"] .
            '" tabindex="-1" aria-labelledby="deleteGroup" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteGroup">Delete Group</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
        aria-hidden="true">×</span></button>
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
           
            <div class="modal fade" id="editGroup' .
            $group["GroupID"] .
            '" tabindex="-1" aria-labelledby="editGroup" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editGroup">Edit group</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                        aria-hidden="true">×</span></button>
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
                        <button type="submit" class="btn btn-primary">Edit</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>';
        echo "</tr>";
        $nomor++; // increment the variable
    }
} else {
    echo '<div class="alert alert-warning" role="alert">Tidak ada data group</div>';
}
?>