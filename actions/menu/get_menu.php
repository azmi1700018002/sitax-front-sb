<?php
require_once "../config/server.php";

$url = $baseUrl . "menu";
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
    foreach ($data1["data"] as $menu) {
        echo "<tr>";
        echo "<td class='text-center'>" . $nomor . "</td>"; // add the number column
        echo "<td class='text-center'>" . $menu["MenuID"] . "</td>";
        echo "<td class='text-center'>" . $menu["MenuNama"] . "</td>";
        echo "<td class='text-center'>" . $menu["MenuLink"] . "</td>";
        echo "<td class='text-center'>" . $menu["MenuDeskripsi"] . "</td>";
        echo "<td class='text-center'>" . $menu["MenuStatus"] . "</td>";
        echo "<td class='text-center'>" . $menu["MenuIcon"] . "</td>";
        echo "<td class='text-center'>" . $menu["MenuKategori"] . "</td>";
        echo "<td class='text-center'>" . $menu["ParentID"] . "</td>";
        echo "<td class='text-center'>" . $menu["ParentSts"] . "</td>";
        echo "<td class='text-center'>" . $menu["NoUrut"] . "</td>";
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
                    echo '<button type="submit" class="btn btn-danger btn-circle btn-sm mr-2" data-ripple-color="dark" data-toggle="modal" data-target="#deleteMenu' .
                        $menu["MenuID"] .
                        '"><i class="fas fa-trash"></i></button>';
                }

                if ($menuIDfk["IsUpdated"] === "1") {
                    echo '<button type="submit" class="btn btn-warning btn-circle btn-sm mr-2" data-ripple-color="dark" data-toggle="modal" data-target="#editMenu' .
                        $menu["MenuID"] .
                        '"><i class="fas fa-edit"></i></button>';
                }

                echo '</div></td>';
            }
        }


        echo '<div class="modal fade" id="deleteMenu' .
            $menu["MenuID"] .
            '" tabindex="-1" aria-labelledby="deleteMenu" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteMenu">Delete Menu</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
        aria-hidden="true">×</span></button>
      </div>
      <div class="modal-body">Apakah yakin ingin menghapus data menu ini ?</div>
     
      <div class="modal-footer">
      <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
      <form method="POST" action="../actions/menu/delete_menu.php">
      <input type="hidden" name="MenuID" value="' .
            $menu["MenuID"] .
            '">
      <button type="submit" class="btn btn-danger" >Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>  
       
        <div class="modal fade" id="editMenu' .
            $menu["MenuID"] .
            '" tabindex="-1" aria-labelledby="ediMenu" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="ediMenu">Edit menu</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                    aria-hidden="true">×</span></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="../actions/menu/put_menu.php" enctype="multipart/form-data">
                    <div class="mb-3" style="display:none;">
<label class="form-label" for="GroupID">ID :</label>
<div class="form-outline">
    <input type="hidden" id="MenuID" name="MenuID" class="form-control" value="' .
            $menu["MenuID"] .
            '">
    </div>
</div>
                            <div class="mb-3">
                            <label class="form-label" for="MenuNama">Nama Menu : </label>
                            <div class="form-outline">
                                <input type="text" id="MenuNama" name="MenuNama" class="form-control"
                                value="' .
            $menu["MenuNama"] .
            '">
                            </div>
                        </div>
                            <div class="mb-3">
                            <label class="form-label" for="MenuLink">Link Menu : </label>
                            <div class="form-outline">
                                <input type="text" id="MenuLink" name="MenuLink" class="form-control"
                                value="' .
            $menu["MenuLink"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="MenuDeskripsi">Deskripsi Menu : </label>
                        <div class="form-outline">
                                <input type="text" id="MenuDeskripsi" name="MenuDeskripsi" class="form-control"
                                value="' .
            $menu["MenuDeskripsi"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="MenuStatus">Status Menu : </label>
                        <div class="form-outline">
                                <input type="text" id="MenuStatus" name="MenuStatus" class="form-control"
                                value="' .
            $menu["MenuStatus"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="MenuIcon">Icon Menu : </label>
                        <div class="form-outline">
                                <input type="text" id="MenuIcon" name="MenuIcon" class="form-control"
                                value="' .
            $menu["MenuIcon"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="MenuKategori">Kategori Menu : </label>
                        <div class="form-outline">
                                <input type="text" id="MenuKategori" name="MenuKategori" class="form-control"
                                value="' .
            $menu["MenuKategori"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="ParentID">ID Parent : </label>
                        <div class="form-outline">
                                <input type="text" id="ParentID" name="ParentID" class="form-control"
                                value="' .
            $menu["ParentID"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="ParentSts">Sts Parent : </label>
                        <div class="form-outline">
                                <input type="text" id="ParentSts" name="ParentSts" class="form-control"
                                value="' .
            $menu["ParentSts"] .
            '">
                            </div>
                        </div>

                        <div class="mb-3">
                        <label class="form-label" for="NoUrut">No Urut : </label>
                        <div class="form-outline">
                                <input type="text" id="NoUrut" name="NoUrut" class="form-control"
                                value="' .
            $menu["NoUrut"] .
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
    </div>
    <button type="submit" class="btn btn-link btn-rounded btn-sm fw-bold text-danger" data-ripple-color="dark" data-toggle="modal" data-target="#deleteMenu' .
            $menu["MenuID"] .
            '"><i class="fas fa-trash"></i> </button>
                    <div class="modal fade" id="deleteMenu' .
            $menu["MenuID"] .
            '" tabindex="-1" aria-labelledby="deleteMenu" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
        <div class="modal-header">
            <h5 class="modal-title" id="deleteMenu">Delete Menu</h5>
            <button type="button" class="btn-close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">Apakah yakin ingin menghapus data menu ini?</div>
        
        <div class="modal-footer">
        <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
        <form method="POST" action="../actions/menu/delete_menu.php">
        <input type="hidden" name="MenuID" value="' .
            $menu["MenuID"] .
            '">
                <button type="submit" class="btn btn-danger" >Hapus</button>
            </form>
        </div>
        </div>
    </div>';
        echo "</tr>";
        $nomor++; // increment the variable
    }
} else {
    echo '<div class="alert alert-warning" role="alert">Tidak ada data menu</div>';
}
?>