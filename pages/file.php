<?php include "../helpers/token_session.php"; ?>
<?php include "../includes/header.php"; ?>

<!-- Sweet Alert Success Pajak -->
<?php if (isset($_SESSION["message_file_success"])) { ?>
<script>
Swal.fire({
    icon: 'success',
    title: '<?php echo $_SESSION["message_file_success"]; ?>',
    showConfirmButton: false,
    timer: 8000
});
</script>
<?php unset($_SESSION["message_file_success"]); ?>
<?php } ?>

<!-- Sweet Alert Faield Pajak -->
<?php if (isset($_SESSION["message_file_failed"])) { ?>
<script>
Swal.fire({
    icon: 'error',
    title: '<?php echo $_SESSION["message_file_failed"]; ?>',
    showConfirmButton: false,
    timer: 8000
});
</script>
<?php unset($_SESSION["message_file_failed"]); ?>
<?php } ?>

<!-- Page Wrapper -->
<div id="wrapper">
    <?php include('../includes/sidebar.php'); ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <?php include('../includes/navbar.php'); ?>
            <!-- Begin Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->

                <h3>File</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Data yang ditampilkan</li>
                    </ol>
                </nav>
                <?php
                $groupIDToCheck = $_SESSION["GroupID"];

                // Check if activeMenuID is set in the session
                if (isset($_SESSION['activeMenuID'])) {
                    $activeMenuID = $_SESSION['activeMenuID'];
                    // var_dump($activeMenuID);

                    // Loop through each menu item in the data
                    foreach ($data["data"] as $menuItem) {
                        foreach ($menuItem["MenuIDfk"] as $menuIDfk) {
                            // Check if IsCreated is 1 and MenuID matches current page's MenuID
                            if (
                                isset($menuIDfk["IsCreated"]) &&
                                isset($menuIDfk["MenuID"]) &&
                                isset($menuIDfk["GroupID"]) &&
                                $menuIDfk["IsCreated"] === "1" &&
                                $menuIDfk["MenuID"] === $activeMenuID &&
                                $menuIDfk["GroupID"] === $groupIDToCheck
                            ) {
                                // Add the button HTML
                                echo '
                                <button type="button" class="btn btn-outline-primary ms-auto" data-ripple-color="dark"
                    data-toggle="modal" data-target="#tambahFile">
                    <i class="fas fa-plus me-2"></i>
                    File
                </button>';
                                break; // No need to continue checking other MenuIDfk entries for this menu item
                            }
                        }
                    }
                } else {
                    echo "No active menu selected.";
                }
                ?>
                <!-- <button type="button" class="btn btn-outline-primary ms-auto" data-ripple-color="dark"
                    data-toggle="modal" data-target="#tambahFile">
                    <i class="fas fa-plus me-2"></i>
                    File
                </button> -->

                <div class="my-4 table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">ID File</th>
                                <th class="text-center">File Judul</th>
                                <th class="text-center">File Path</th>
                                <th class="text-center">File Date</th>
                                <th class="text-center">File Jenis</th>
                                <?php
                                // Loop through each menu item in the data
                                foreach ($data["data"] as $menuItem) {
                                    foreach ($menuItem["MenuIDfk"] as $menuIDfk) {
                                        // Check if IsCreated is 1 and MenuID matches current page's MenuID
                                        if ($menuIDfk["IsUpdated"] === "1" && $menuIDfk["MenuID"] === $activeMenuID && $menuIDfk["GroupID"] === $groupIDToCheck || $menuIDfk["IsDeleted"] === "1" && $menuIDfk["MenuID"] === $activeMenuID && $menuIDfk["GroupID"] === $groupIDToCheck) {
                                            // Add the button HTML
                                            echo '<th class="text-center all">Actions</th>';
                                            break; // No need to continue checking other MenuIDfk entries for this menu item
                                        }
                                    }
                                }
                                ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include "../actions/file/get_file.php"; ?>
                        </tbody>
                    </table>
                </div>


                <!-- Modal Add -->
                <div class="modal fade" id="tambahFile" tabindex="-1" aria-labelledby="tambahFile" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah File</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <form action="../actions/file/add_file.php" method="POST" enctype="multipart/form-data">

                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="FileJenis">File Jenis :</label>
                                            <div class="input-group">
                                                <select class="form-control" name="FileJenis"
                                                    aria-label="Default select example">
                                                    <?php
                                                    require_once "../config/server.php";

                                                    $url = $baseUrl . "referensi";
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
                                                        // Loop untuk menghasilkan opsi dalam elemen select
                                                        foreach ($data["data"] as $referensi) {
                                                            if ($referensi["GrpID"] === "FILEJNS") {
                                                                $selected = ($referensi["Ref"] == $selectedReferensiID) ? "selected" : ""; // Menentukan apakah opsi ini dipilih
                                                                $optionValue = $referensi["Ref"]; // Menggunakan FileJenis sebagai nilai opsi
                                                                $optionText = $referensi["Ref"] . " - " . $referensi["Ket"]; // Menggabungkan KdKanotr dan AlamatKantor sebagai teks opsi
                                                                echo "<option value='" . $optionValue . "' data-ket='" . $referensi["Ket"] . "' $selected>" . $optionText . "</option>";
                                                            }
                                                        }
                                                    } else {
                                                        echo '<option value="" disabled selected>Tidak ada data file</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="FileID">ID File : </label>
                                        <div class="form-outline">
                                            <input type="text" id="FileID" name="FileID" class="form-control"
                                                required />
                                        </div>
                                        <small class="form-text text-muted">
                                            contoh : <span>FL0000000001</span> <strong>harus 12 character !!</strong>
                                        </small>
                                    </div>
                                    <!-- <div class="mb-3">
                            <label class="form-label" for="FileJudul">File Judul : </label>
                            <div class="form-outline">
                                <input type="text" id="FileJudul" name="FileJudul" class="form-control" required />
                            </div>
                        </div> -->
                                    <div class="form-group mb-3">
                                        <label for="file_judul">File Judul :</label>
                                        <div class="custom-file">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
                                            <input type="file" class="custom-file-input" id="file_judul"
                                                name="file_judul" required>
                                            <label class="custom-file-label add" for="file_judul">choose file</label>
                                        </div>
                                        <small class="form-text text-muted">
                                            Selected file: <span id="selectedFileName">No file chosen</span><button
                                                type="button" class="btn btn-link p-0 ml-2" id="clearFileSelection"
                                                style="display: none;">&times;</button>
                                        </small>
                                    </div>

                                    <!-- <div class="mb-3">
                                        <div class="custom-file">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                                            <input type="file" class="custom-file-input" id="file_judul"
                                                name="file_judul">
                                            <label class="custom-file-label" for="file_judul">Choose file</label>
                                        </div>
                                    </div> -->

                                    <div class="mb-3">
                                        <label class="form-label" for="FilePath">File Path : </label>
                                        <div class="form-outline">
                                            <input type="text" id="FilePath" name="FilePath" class="form-control"
                                                required />
                                        </div>
                                        <small class="form-text text-muted">
                                            path : <span>../../SitaxUpdate/file/</span>
                                        </small>
                                    </div>


                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success">Tambah</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <script>
                    // Ambil elemen input file, label, dan tombol silang
                    const inputJudul = document.getElementById("file_judul");
                    const labelJudul = document.querySelector(".custom-file-label.add");
                    const clearButton = document.getElementById("clearFileSelection");
                    const selectedFileName = document.getElementById("selectedFileName");

                    // Tambahkan event listener untuk tombol silang
                    clearButton.addEventListener("click", function() {
                        // Hapus pilihan file dengan mereset nilai input file
                        inputJudul.value = "";
                        // Perbarui teks label dan selectedFileName
                        labelJudul.textContent = "choose file";
                        selectedFileName.textContent = "No file chosen";
                        // Sembunyikan kembali tombol silang
                        clearButton.style.display = "none";
                    });

                    // Tambahkan event listener untuk mendeteksi perubahan pada input file
                    inputJudul.addEventListener("change", function() {
                        // Perbarui teks label dengan nama file yang dipilih
                        labelJudul.textContent = inputJudul.files[0].name;
                        // Perbarui teks selectedFileName juga jika perlu
                        selectedFileName.textContent = inputJudul.files[0].name;
                        // Tampilkan tombol silang setelah file dipilih dan diunggah
                        clearButton.style.display = "inline";
                    });

                    // Ambil elemen input file, label, dan tombol silang untuk form kedua
                    const editJudul = document.getElementById("file_edit_judul");
                    const labelEditJudul = document.querySelector(".custom-file-label.edit");
                    const clearEditButton = document.getElementById("clearEditFileSelection");
                    const selectedEditFileName = document.getElementById("selectedEditFileName");

                    // Tambahkan event listener untuk tombol silang pada form kedua
                    clearEditButton.addEventListener("click", function() {
                        // Hapus pilihan file dengan mereset nilai input file
                        editJudul.value = "";
                        // Perbarui teks label dan selectedFileName
                        labelEditJudul.textContent = "Choose file";
                        selectedEditFileName.textContent = "No file chosen";
                        // Sembunyikan kembali tombol silang
                        clearEditButton.style.display = "none";
                    });

                    // Tambahkan event listener untuk mendeteksi perubahan pada input file pada form kedua
                    editJudul.addEventListener("change", function() {
                        // Perbarui teks label dengan nama file yang dipilih
                        labelEditJudul.textContent = editJudul.files[0].name;
                        // Perbarui teks selectedFileName juga jika perlu
                        selectedEditFileName.textContent = editJudul.files[0].name;
                        // Tampilkan tombol silang setelah file dipilih dan diunggah
                        clearEditButton.style.display = "inline";
                    });

                    // Set the value of the file input when the page loads
                    document.addEventListener("DOMContentLoaded", function() {
                        var fileJudulValue = document.getElementById("FileJudulValue").value;
                        if (fileJudulValue !== "") {
                            var fileInput = document.getElementById("file_edit_judul");
                            fileInput.insertAdjacentHTML("beforebegin", "<p>File Sebelumnya: " +
                                fileJudulValue +
                                "</p>");
                        }
                    });
                    </script>
                </div>
            </div>
        </div>
        <?php include('../includes/footer.php'); ?>
        </body>

        </html>