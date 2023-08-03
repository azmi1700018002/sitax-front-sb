<?php
require_once "../config/server.php";

$url = $baseUrl . "auth/panduan_pajak";
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

// Sorting data berdasarkan nomor urut (PanduanPajakID)
if (isset($data['data']) && is_array($data['data'])) {
    usort($data['data'], function ($a, $b) {
        return $a['PanduanPajakID'] - $b['PanduanPajakID'];
    });
}

// Menampilkan data dalam list-group
if (isset($data['data']) && is_array($data['data'])) {
    echo '<ul class="list-group">';

    foreach ($data['data'] as $panduanPjk) {
        echo '<div class="list-group-item d-flex justify-content-between align-items-center">';
        // Menampilkan nama panduan pajak
        echo $panduanPjk['NamaPanduanPajak'];
        echo '<div class="btn-group" role="group">';

        // Eye Icon for "Buka"
        echo '<a href="#" class="btn btn-primary btn-sm" data-fileid="' . $panduanPjk['FileID'] . '" data-filejudul=\'' . $panduanPjk['NamaPanduanPajak'] . '\'>';
        echo '<i class="far fa-eye"></i>';
        echo '</a>';

        // Edit Button
        echo '<button type="button" class="btn btn-warning btn-sm" data-ripple-color="dark" data-toggle="modal" data-target="#editPanduanPajak' . $panduanPjk['PanduanPajakID'] . '">';
        echo '<i class="fas fa-edit"></i>';
        echo '</button>';
        echo '<div class="modal fade" id="editPanduanPajak' . $panduanPjk['PanduanPajakID'] . '" tabindex="-1" aria-labelledby="editPanduanPajak" aria-hidden="true">';
        // Edit Modal Content
        echo '   <div class="modal-dialog">
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
                <label class="form-label" for="StsPanduanPajak">Sts Panduan Pajak : </label>
                <div class="form-outline">
                    <input type="text" id="StsPanduanPajak" name="StsPanduanPajak" class="form-control"
                    value="' .
            $panduanPjk["StsPanduanPajak"] .
            '">
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
                <label class="form-label" for="FileID">Id File Pajak : </label>
                <div class="form-outline">
                    <input type="text" id="FileID" name="FileID" class="form-control"
                    value="' .
            $panduanPjk["FileID"] .
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
</div>';

        echo '</div>';
        // Delete Button
        echo '<button type="button" class="btn btn-danger btn-sm" data-ripple-color="dark" data-toggle="modal" data-target="#deletePanduanPajak' . $panduanPjk['PanduanPajakID'] . '">';
        echo '<i class="fas fa-trash"></i>';
        echo '</button>';
        echo '<div class="modal fade" id="deletePanduanPajak' . $panduanPjk['PanduanPajakID'] . '" tabindex="-1" aria-labelledby="deletePanduanPajak" aria-hidden="true">';
        // Delete Modal Content
        echo '<div class="modal-dialog">
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

    echo '</ul>';
} else {
    echo '<p>Data tidak ditemukan.</p>';
}
?>