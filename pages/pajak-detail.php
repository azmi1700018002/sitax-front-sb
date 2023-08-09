<?php include "../helpers/token_session.php"; ?>
<?php include "../includes/header.php"; ?>

<!-- Sweet Alert Success Pajak Detail -->
<?php if (isset($_SESSION["message_pajak_detail_success"])) { ?>
<script>
Swal.fire({
    icon: 'success',
    title: '<?php echo $_SESSION["message_pajak_detail_success"]; ?>',
    showConfirmButton: false,
    timer: 8000
});
</script>
<?php unset($_SESSION["message_pajak_detail_success"]); ?>
<?php } ?>

<!-- Sweet Alert Faield Pajak Detail -->
<?php if (isset($_SESSION["message_pajak_detail_failed"])) { ?>
<script>
Swal.fire({
    icon: 'error',
    title: '<?php echo $_SESSION["message_pajak_detail_failed"]; ?>',
    showConfirmButton: false,
    timer: 8000
});
</script>
<?php unset($_SESSION["message_pajak_detail_failed"]); ?>
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
                <h3>Detail Pajak</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Data yang ditampilkan</li>
                    </ol>
                </nav>
                <button type="button" class="btn btn-outline-primary ms-auto" data-ripple-color="dark"
                    data-toggle="modal" data-target="#tambahPajakDetail">
                    <i class="fas fa-plus me-2"></i>
                    Detail Pajak
                </button>

                <div class="my-4 table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">ID Detail Pajak</th>
                                <th class="text-center">ID Pajak</th>
                                <th class="text-center">Ppn</th>
                                <th class="text-center">Pasal23</th>
                                <th class="text-center">Pph Final</th>
                                <th class="text-center">Pajak Lain</th>
                                <th class="text-center">Keterangan</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include "../actions/pajak-detail/get_pajak_detail.php"; ?>
                        </tbody>
                    </table>
                </div>


                <!-- Modal Add -->
                <div class="modal fade" id="tambahPajakDetail" tabindex="-1" aria-labelledby="tambahPajakDetail"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah Detail Pajak</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <form action="../actions/pajak-detail/add_pajak_detail.php" method="POST"
                                    enctype="multipart/form-data">

                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="PajakID">Pajak :</label>
                                            <div class="input-group">
                                                <select class="form-control" name="PajakID"
                                                    aria-label="Default select example">
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
                                                        // Loop untuk menghasilkan opsi dalam elemen select
                                                        foreach ($data["data"] as $pajak) {
                                                            $selected = ($pajak["PajakID"] == $selectedPajakID) ? "selected" : ""; // Menentukan apakah opsi ini dipilih
                                                            $optionValue = $pajak["PajakID"]; // Menggunakan PajakID sebagai nilai opsi
                                                            $optionText = $pajak["PajakID"] . " - " . $pajak["NamaPajak"]; // Menggabungkan PajakID dan PajakNama sebagai teks opsi
                                                            echo "<option value='" . $optionValue . "' data-namapajak='" . $pajak["NamaPajak"] . "' $selected>" . $optionText . "</option>";
                                                        }
                                                    } else {
                                                        echo '<option value="" disabled selected>Tidak ada data pajak</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="PajakDetailID">ID Pajak Detail : </label>
                                            <div class="form-outline">
                                                <input type="text" id="PajakDetailID" name="PajakDetailID"
                                                    class="form-control" required />
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="Ppn">PPN : </label>
                                                    <div class="form-outline">
                                                        <input type="text" id="Ppn" name="Ppn" class="form-control"
                                                            required />
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        contoh : <span>1.0</span> <strong>gunakan titik !!</strong>
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="PphFinal">PPH : </label>
                                                    <div class="form-outline">
                                                        <input type="text" id="PphFinal" name="PphFinal"
                                                            class="form-control" required />
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        contoh : <span>1.0</span> <strong>gunakan titik !!</strong>
                                                    </small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="Pasal23">Pasal23 : </label>
                                            <div class="form-outline">
                                                <input type="text" id="Pasal23" name="Pasal23" class="form-control"
                                                    required />
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="PajakLain">Pajak Lain : </label>
                                            <div class="form-outline">
                                                <input type="text" id="PajakLain" name="PajakLain" class="form-control"
                                                    required />
                                            </div>
                                            <small class="form-text text-muted">
                                                contoh : <span>1.0</span> <strong>gunakan titik !!</strong>
                                            </small>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="Keterangan">Keterangan : </label>
                                            <div class="form-outline">
                                                <textarea id="limittambahDeskripsi" name="Keterangan"
                                                    class="form-control" required></textarea>
                                            </div>
                                            <div id="textCounterTambah">50 Karakter Tersisa</div>
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
                    $(document).ready(function() {
                        $('#limittambahDeskripsi').on('input propertychange', function() {
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
                    $(document).ready(function() {
                        $('.editDeskripsi').on('input propertychange', function() {
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
        </body>

        </html>