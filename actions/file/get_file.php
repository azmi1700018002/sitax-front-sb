<?php
require_once "../config/server.php";

$url1 = $baseUrl . "auth/file";
$url2 = $baseUrl . "referensi"; // URL kedua

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
  // Buat array asosiatif untuk menyimpan keterangan "Ket" berdasarkan "GrpID" dari $data2
  $jnsFile = [];
  foreach ($data2["data"] as $referensi) {
    if ($referensi["GrpID"] === "FILEJNS") {
      $jnsFile[$referensi["Ref"]] = $referensi["Ket"];
    }
  }

  // Tampilkan data dari $data1["data"] dalam tabel
  if (isset($data1["data"])) {
    $nomor = 1;
    foreach ($data1["data"] as $file) {
      $currentUserStsUser = $file["FileJenis"];
      echo "<tr>";
      echo "<td class='text-center'>" . $nomor . "</td>";
      echo "<td class='text-center'>" . $file["FileID"] . "</td>";
      echo "<td class='text-center'>" . $file["FileJudul"] . "</td>";
      echo "<td class='text-center'>" . $file["FilePath"] . "</td>";
      echo "<td class='text-center'>" . $file["FileDate"] . "</td>";
      // Tambahkan kondisi untuk menampilkan keterangan "Ket" berdasarkan "StsUser"
      if (isset($jnsFile[$file["FileJenis"]])) {
        echo "<td class='text-center'>" .
          $jnsFile[$file["FileJenis"]] .
          "</td>";
      } else {
        echo "<td class='text-center'>Tidak Diketahui</td>"; // Tampilkan pesan jika tidak ada keterangan yang sesuai
      }

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
            echo '<button type="submit" class="btn btn-danger btn-circle btn-sm mr-2" data-ripple-color="dark" data-toggle="modal" data-target="#deleteFile' .
              $file["FileID"] .
              '"><i class="fas fa-trash"></i></button>';
          }

          if ($menuIDfk["IsUpdated"] === "1") {
            echo '<button type="submit" class="btn btn-warning btn-circle btn-sm mr-2" data-ripple-color="dark" data-toggle="modal" data-target="#editFile' .
              $file["FileID"] .
              '"><i class="fas fa-edit"></i></button>';
          }

          echo '</div></td>';
        }
      }
      echo '<div class="modal fade" id="deleteFile' .
        $file["FileID"] .
        '" tabindex="-1" aria-labelledby="deleteFile" aria-hidden="true">
<div class="modal-dialog">
<div class="modal-content">
<div class="modal-header">
<h5 class="modal-title" id="deleteFile">Delete File</h5>
<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
</div>
<div class="modal-body">Apakah yakin ingin menghapus data file ini?</div>

<div class="modal-footer">
<button type="button" class="btn btn-warning" data-dismiss="modal">Batal</button>
<form method="POST" action="../actions/file/delete_file.php">
<input type="hidden" name="FileID" value="' .
        $file["FileID"] .
        '">
    <button type="submit" class="btn btn-danger" >Hapus</button>
</form>
</div>
</div>
</div>
</div> 

    <div class="modal fade" id="editFile' .
        $file["FileID"] .
        '" tabindex="-1" aria-labelledby="editFile" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editFile">Edit file</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
            </div>
            <div class="modal-body">
                <form method="POST" action="../actions/file/put_file.php" enctype="multipart/form-data">
                <div class="mb-3" style="display:none;">
<label class="form-label" for="FileID">ID :</label>
<div class="form-outline">
<input type="hidden" id="FileID" name="FileID" class="form-control" value="' .
        $file["FileID"] .
        '">
</div>
</div>

<div class="form-group mb-3">
        <label for="file_edit_judul">File Judul :</label>
        <div class="custom-file">
            <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
            <input type="file" class="custom-file-input" id="file_edit_judul" name="file_judul" required>
            <label class="custom-file-label edit" for="file_edit_judul">Choose file</label>
        </div>
        <small class="form-text text-muted">
            Selected file: <span id="selectedEditFileName">No file chosen</span>
            <button type="button" class="btn btn-link p-0 ml-2" id="clearEditFileSelection" style="display: none;">&times;</button>
        </small>
    </div>

      
                    <div class="mb-3">
                    <label class="form-label" for="FilePath">File Path : </label>
                    <div class="form-outline">
                        <input type="text" id="FilePath" name="FilePath" class="form-control"
                        value="' .
        $file["FilePath"] .
        '">
                    </div>
                </div>

                <div class="mb-3">
                <div class="form-group">
                <label for="FileJenis">File Jenis :</label>
                <div class="input-group">
                    <select class="form-control" name="FileJenis"
                        aria-label="Default select example">';
      // Assuming $stsUserByUsername is already populated with data from the server
      foreach ($data2["data"] as $referensi) {
        if ($referensi["GrpID"] === "FILEJNS") {
          $ref = $referensi["Ref"];
          $keterangan = $referensi["Ket"];
          // Check if the current option's "GrpID" matches the user's "ref"
          $selected = $ref === $currentUserStsUser ? "selected" : "";
          echo "<option value='$ref' $selected>$keterangan</option>";
        }
      }
      echo '</select>
        </div>
        </div>
            </div>      

            <div class="modal-footer">
            <input class="btn btn-primary" type="reset" value="Reset">
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
    echo '<div class="alert alert-warning" role="alert">Tidak ada data file</div>';
  }
}
?>