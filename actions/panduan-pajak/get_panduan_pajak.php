<?php
require_once "../config/server.php";

$url1 = $baseUrl . "auth/panduan_pajak";
$url2 = $baseUrl . "auth/file"; // URL kedua

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
    // Buat array asosiatif untuk menyimpan keterangan "Ket" berdasarkan "FileID" dari $data2
    $fileJudulArr = [];
    foreach ($data2["data"] as $file) {
        $fileJudulArr[$file["FileID"]] = $file["FileJudul"];
    }

    // Sorting data berdasarkan nomor urut (PanduanPajakID)
    if (isset($data1['data']) && is_array($data1['data'])) {
        usort($data1['data'], function ($a, $b) {
            return $a['PanduanPajakID'] - $b['PanduanPajakID'];
        });
    }

    // Search keyword from query parameter
    $searchKeyword = isset($_GET['search']) ? $_GET['search'] : '';

    // Filter data based on search keyword
    $filteredData = [];
    if (isset($data1['data']) && is_array($data1['data'])) {
        foreach ($data1['data'] as $panduanPjk) {
            if (stripos($panduanPjk['NamaPanduanPajak'], $searchKeyword) !== false) {
                $filteredData[] = $panduanPjk;
            }
        }
    }

    // Number of items per page
    $itemsPerPage = 10;

    // Current page number
    $currentPage = isset($_GET['page']) ? intval($_GET['page']) : 1;

    // Number of filtered items
    $totalFilteredItems = count($filteredData);

    // Update startIndex and endIndex based on filtered data
    $startIndex = ($currentPage - 1) * $itemsPerPage;
    $endIndex = min($startIndex + $itemsPerPage, $totalFilteredItems);

    // Menampilkan data dalam list-group
    if (!empty($filteredData)) {
        echo '<ul class="list-group mb-2">';
        for ($i = $startIndex; $i < $endIndex; $i++) {
            if (isset($filteredData[$i])) {
                $panduanPjk = $filteredData[$i];
                $currentFileUser = $panduanPjk["FileID"];
                echo '<div class="list-group-item d-flex justify-content-between align-items-center">';
                // Menampilkan nama panduan pajak
                echo $panduanPjk['NamaPanduanPajak'];
                echo '<div class="btn-group" role="group">';

                // Eye Icon for "Buka"
                echo '<a href="#" class="pdfopen btn btn-primary btn-sm" data-fileid="' . $panduanPjk['FileID'] . '" data-filejudul=\'' . $panduanPjk['NamaPanduanPajak'] . '\'>';
                echo '<i class="far fa-eye"></i>';
                echo '</a>';

                // Loop through each menu item in the data
                foreach ($data["data"] as $menuItem) {
                    $showButtons = false; // Flag to determine whether to show buttons or not

                    foreach ($menuItem["MenuIDfk"] as $menuIDfk) {
                        // Check if MenuID, GroupID, and IsDeleted condition matches
                        if ($menuIDfk["MenuID"] === $menuID && $menuIDfk["GroupID"] === $groupIDToCheck) {
                            if ($menuIDfk["IsDeleted"] === "1" || $menuIDfk["IsUpdated"] === "1") {
                                $showButtons = true;
                            }
                            break; // No need to continue checking other MenuIDfk entries for this menu item
                        }
                    }

                    if ($showButtons) {

                        if ($menuIDfk["IsDeleted"] === "1") {
                            echo '<button type="submit" class="btn btn-danger btn-sm" data-ripple-color="dark" data-toggle="modal" data-target="#deletePanduanPajak' .
                                $panduanPjk["PanduanPajakID"] .
                                '"><i class="fas fa-trash"></i></button>';
                        }

                        if ($menuIDfk["IsUpdated"] === "1") {
                            echo '<button type="submit" class="btn btn-warning btn-sm" data-ripple-color="dark" data-toggle="modal" data-target="#editPanduanPajak' .
                                $panduanPjk["PanduanPajakID"] .
                                '"><i class="fas fa-edit"></i></button>';
                        }

                    }
                }

                // Edit Button
                // echo '<button type="button" class="btn btn-warning btn-sm" data-ripple-color="dark" data-toggle="modal" data-target="#editPanduanPajak' . $panduanPjk['PanduanPajakID'] . '">';
                // echo '<i class="fas fa-edit"></i>';
                // echo '</button>';

                // Edit Modal Content
                echo '<div class="modal fade" id="editPanduanPajak' . $panduanPjk['PanduanPajakID'] . '" tabindex="-1" aria-labelledby="editPanduanPajak" aria-hidden="true">
               <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editPanduanPajak">Edit pajak</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="../actions/panduan-pajak/put_panduan_pajak.php" enctype="multipart/form-data">
                <div class="mb-3" style="display:none;">
<label class="form-label" for="PanduanPajakID">ID :</label>
<div class="form-outline">
<input type="hidden" id="PanduanPajakID" name="PanduanPajakID" class="form-control" value="' .
                    $panduanPjk["PanduanPajakID"] .
                    '">
</div>
</div>
                        <div class="mb-3">
                        <label class="form-label" for="NamaPanduanPajak">Nama Panduan Pajak : </label>
                        <div class="form-outline">
                            <input type="text" id="NamaPanduanPajak" name="NamaPanduanPajak" class="form-control"
                            value="' .
                    $panduanPjk["NamaPanduanPajak"] .
                    '">
                        </div>
                    </div>
                    <div class="mb-3">
                    <label class="form-label" for="ParentPanduanPajak">Parent Panduan Pajak : </label>
                    <div class="form-outline">
                        <input type="text" id="ParentPanduanPajak" name="ParentPanduanPajak" class="form-control"
                        value="' .
                    $panduanPjk["ParentPanduanPajak"] .
                    '">
                    </div>
                </div>

                <div class="mb-3">
                <div class="form-group">
                    <label for="StsPanduanPajak">Status Panduan Pajak :</label>
                    <div class="input-group">
                        <select class="form-control" name="StsPanduanPajak" aria-label="Default select example">
                            <option value="1" ' .
                    ($panduanPjk["StsPanduanPajak"] == 1 ? "selected" : "") .
                    '>Ditampilkan</option>
                            <option value="0" ' .
                    ($panduanPjk["StsPanduanPajak"] == 0 ? "selected" : "") .
                    '>Tidak Ditampilkan</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label" for="StsParent">Sts parent : </label>
                <div class="form-outline">
                    <input type="text" id="StsParent" name="StsParent" class="form-control"
                    value="' .
                    $panduanPjk["StsParent"] .
                    '">
                </div>
            </div>

            <div class="mb-3">
            <div class="form-group">
                        <label for="FileID">File :</label>
                        <div class="input-group">
                            <select class="form-control" name="FileID"
                                aria-label="Default select example">';
                // Assuming $stsUserByUsername is already populated with data from the server
                foreach ($data2["data"] as $file) {
                    $fileID = $file["FileID"];
                    $fileJudul = $file["FileJudul"];
                    $selected = $fileID === $currentFileUser ? "selected" : "";
                    echo "<option value='$fileID' $selected>$fileJudul</option>";
                }
                echo '</select>
            </div>
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

                echo '</div>';
                // Delete Button
                // echo '<button type="button" class="btn btn-danger btn-sm" data-ripple-color="dark" data-toggle="modal" data-target="#deletePanduanPajak' . $panduanPjk['PanduanPajakID'] . '">';
                // echo '<i class="fas fa-trash"></i>';
                // echo '</button>';

                // Delete Modal Content
                echo '<div class="modal fade" id="deletePanduanPajak' . $panduanPjk['PanduanPajakID'] . '" tabindex="-1" aria-labelledby="deletePanduanPajak" aria-hidden="true">
               <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="deletePanduanPajak">Delete Panduan Pajak</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
            aria-hidden="true">×</span></button>
          </div>
          <div class="modal-body">Apakah yakin ingin menghapus data panduan pajak ini?</div>
         
          <div class="modal-footer">
          <button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
          <form method="POST" action="../actions/panduan-pajak/delete_panduan_pajak.php">
          <input type="hidden" name="PanduanPajakID" value="' .
                    $panduanPjk["PanduanPajakID"] .
                    '">
                <button type="submit" class="btn btn-danger" >Hapus</button>
            </form>
          </div>
        </div>
        </div>
        </div>';

                echo '</div>';
                echo '</div>';
            }
        }
        echo '</ul>';
    } else {
        echo '<p>Data tidak ditemukan.</p>';
    }
}
?>