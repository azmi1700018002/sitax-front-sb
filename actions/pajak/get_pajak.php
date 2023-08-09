<?php
require_once "../config/server.php";

$url1 = $baseUrl . "auth/pajak";
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

    // Tampilkan data dari $data1["data"] dalam tabel
    if (isset($data1["data"])) {
        $nomor = 1;
        foreach ($data1["data"] as $pajak) {
            $currentFileUser = $pajak["FileID"];
            echo "<tr>";
            echo "<td class='text-center'>" . $nomor . "</td>"; // add the number column
            echo "<td class='text-center'>" . $pajak["PajakID"] . "</td>";
            echo "<td class='text-center'><a href='#' class='namaPajakLink' data-pajakid='" . $pajak["PajakID"] . "'>" . $pajak["NamaPajak"] . "</a></td>";
            echo "<td class='text-center'>" . $pajak["ParentPajak"] . "</td>";
            echo "<td class='text-center'>" . ($pajak["StsPajak"] == 1 ? "Ditampilkan" : "Tidak Ditampilkan") . "</td>";
            echo "<td class='text-center'>" . $pajak["KetPajak"] . "</td>";
            echo "<td class='text-center'>" . $pajak["StsParent"] . "</td>";
            echo "<td class='text-center'>" . $pajak["FileID"] . "</td>";
            echo '<td>
        <div class="d-flex justify-content-center">
            <button type="submit" class="btn btn-danger btn-circle btn-sm mr-2" data-ripple-color="dark" data-toggle="modal" data-target="#deletePajak' .
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
            <button type="submit" class="btn btn-warning btn-circle btn-sm" data-ripple-color="dark" data-toggle="modal" data-target="#editPajak' .
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
                        <div class="form-group">
                            <label for="StsPajak">Status Pajak :</label>
                            <div class="input-group">
                                <select class="form-control" name="StsPajak" aria-label="Default select example">
                                    <option value="1" ' .
                ($pajak["StsPajak"] == 1 ? "selected" : "") .
                '>Ditampilkan</option>
                                    <option value="0" ' .
                ($pajak["StsPajak"] == 0 ? "selected" : "") .
                '>Tidak Ditampilkan</option>
                                </select>
                            </div>
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
        echo '<div class="alert alert-warning" role="alert">Tidak ada data pajak</div>';
    }
} else {
    echo '<div class="alert alert-warning" role="alert">Tidak ada data file</div>';
}
?>