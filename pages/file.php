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
                <p>data tampilan file</p>
                <button type="button" class="btn btn-outline-primary ms-auto" data-ripple-color="dark"
                    data-toggle="modal" data-target="#tambahFile">
                    <i class="fas fa-plus me-2"></i>
                    File
                </button>

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
                                <th class="text-center">Actions</th>
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
                                        <label class="form-label" for="FileID">ID File : </label>
                                        <div class="form-outline">
                                            <input type="text" id="FileID" name="FileID" class="form-control"
                                                required />
                                        </div>
                                    </div>
                                    <!-- <div class="mb-3">
                            <label class="form-label" for="FileJudul">File Judul : </label>
                            <div class="form-outline">
                                <input type="text" id="FileJudul" name="FileJudul" class="form-control" required />
                            </div>
                        </div> -->
                                    <div class="mb-3">
                                        <label class="form-label" for="file_judul">File Judul : </label>
                                        <div class="form-outline">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                                            <input type="file" id="file_judul" name="file_judul" class="form-control"
                                                required />
                                        </div>
                                    </div>


                                    <div class="mb-3">
                                        <label class="form-label" for="FilePath">File Path : </label>
                                        <div class="form-outline">
                                            <input type="text" id="FilePath" name="FilePath" class="form-control"
                                                required />
                                        </div>
                                    </div>

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
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success">Tambah</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- limit textarea form tambah -->
                    <script>
                        $(document).ready(function () {
                            $('#limittambahDeskripsi').on('input propertychange', function () {
                                charLimitTambah(this, 50);
                            });
                        });

                        function charLimitTambah(input, maxChar) {
                            var len = $(input).val().length;
                            $('#textCounterTambah').text(len + ' dari ' + maxChar + ' karakter');

                            if (len > maxChar) {
                                $(input).val($(input).val().substring(0, maxChar));
                                $('#textCounterTambah').text('0 karakter tersisa');
                            } else {
                                $('#textCounterTambah').text(maxChar - len + ' karakter tersisa');
                            }
                        }
                    </script>

                    <!-- limit textarea form edit -->
                    <script>
                        $(document).ready(function () {
                            $('.editDeskripsi').on('input propertychange', function () {
                                charLimit(this, 50);
                            });
                        });

                        function charLimit(input, maxChar) {
                            var len = $(input).val().length;
                            var counter = $(input).closest('.modal-body').find('.charNum');
                            counter.text(len + ' dari ' + maxChar + ' karakter');

                            if (len > maxChar) {
                                $(input).val($(input).val().substring(0, maxChar));
                                counter.text('0 karakter tersisa');
                            } else {
                                counter.text(maxChar - len + ' karakter tersisa');
                            }
                        }
                    </script>



                </div>
            </div>
        </div>
        <?php include('../includes/footer.php'); ?>