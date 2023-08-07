<?php include "../helpers/token_session.php"; ?>
<?php include "../includes/header.php"; ?>

<!-- Sweet Alert Success Pajak -->
<?php if (isset($_SESSION["message_pajak_success"])) { ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: '<?php echo $_SESSION["message_pajak_success"]; ?>',
            showConfirmButton: false,
            timer: 8000
        });
    </script>
    <?php unset($_SESSION["message_pajak_success"]); ?>
<?php } ?>

<!-- Sweet Alert Faield Pajak -->
<?php if (isset($_SESSION["message_pajak_failed"])) { ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: '<?php echo $_SESSION["message_pajak_failed"]; ?>',
            showConfirmButton: false,
            timer: 8000
        });
    </script>
    <?php unset($_SESSION["message_pajak_failed"]); ?>
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
                <h3>Pajak</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Data yang ditampilkan</li>
                    </ol>
                </nav>
                <button type="button" class="btn btn-outline-primary ms-auto" data-ripple-color="dark"
                    data-toggle="modal" data-target="#tambahPajak">
                    <i class="fas fa-plus me-2"></i>
                    Pajak
                </button>

                <div class="my-4 table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">ID Pajak</th>
                                <th class="text-center">Nama Pajak</th>
                                <th class="text-center">Parent Pajak</th>
                                <th class="text-center">Sts Pajak</th>
                                <th class="text-center">Ket Pajak</th>
                                <th class="text-center">Sts Parent</th>
                                <th class="text-center">ID File</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include "../actions/pajak/get_pajak.php"; ?>
                        </tbody>
                    </table>
                </div>


                <!-- Modal Add -->
                <div class="modal fade" id="tambahPajak" tabindex="-1" aria-labelledby="tambahPajak" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah Pajak</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <form action="../actions/pajak/add_pajak.php" method="POST"
                                    enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="FileID">File :</label>
                                            <div class="input-group">
                                                <select class="form-control" name="FileID"
                                                    aria-label="Default select example">
                                                    <?php
                                                    require_once "../config/server.php";

                                                    $url = $baseUrl . "auth/file";
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
                                                        foreach ($data["data"] as $file) {
                                                            $selected = ($file["FileID"] == $selectedFileID) ? "selected" : ""; // Menentukan apakah opsi ini dipilih
                                                            $optionValue = $file["FileID"]; // Menggunakan FileID sebagai nilai opsi
                                                            $optionText = $file["FileID"] . " - " . $file["FileJudul"]; // Menggabungkan FileID dan FileJudul sebagai teks opsi
                                                            echo "<option value='" . $optionValue . "' data-filejudul='" . $file["FileJudul"] . "' $selected>" . $optionText . "</option>";
                                                        }
                                                    } else {
                                                        echo '<option value="" disabled selected>Tidak ada data file</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="PajakID">ID Pajak : </label>
                                            <div class="form-outline">
                                                <input type="text" id="PajakID" name="PajakID" class="form-control"
                                                    required />
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="NamaHeader">Nama Header : </label>
                                                    <div class="form-outline">
                                                        <input type="text" id="NamaHeader" name="NamaHeader"
                                                            class="form-control" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="NamaPajak">Nama Pajak : </label>
                                                    <div class="form-outline">
                                                        <input type="text" id="NamaPajak" name="NamaPajak"
                                                            class="form-control" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="ParentPajak">Parent Pajak :
                                                    </label>
                                                    <div class="form-outline">
                                                        <input type="text" id="ParentPajak" name="ParentPajak"
                                                            class="form-control" required />
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="StsPajak">Sts Pajak : </label>
                                                    <div class="form-outline">
                                                        <input type="text" id="StsPajak" name="StsPajak"
                                                            class="form-control" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="KetPajak">Ket Pajak : </label>
                                            <div class="form-outline">
                                                <textarea id="limittambahDeskripsi" name="KetPajak" class="form-control"
                                                    required></textarea>
                                            </div>
                                            <div id="textCounterTambah">50 Karakter Tersisa</div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="StsParent">Sts Parent : </label>
                                            <div class="form-outline">
                                                <input type="text" id="StsParent" name="StsParent" class="form-control"
                                                    required />
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
                    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
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

                    <!-- Di bagian head atau sebelum akhir </body> -->
                    <script>
                        $(document).ready(function () {
                            // Fungsi untuk menampilkan detail pajak berdasarkan ID
                            function showPajakDetail(pajak_id) {
                                // Ganti localhost:3000 dengan URL API yang sesuai jika perlu
                                var api_url = "http://localhost:3000/pubpajak_detail/" + pajak_id;
                                $.ajax({
                                    url: api_url,
                                    method: "GET",
                                    crossDomain: true,
                                    dataType: "json",
                                    success: function (data) {
                                        // Tampilkan data detail pajak di sini, misalnya dengan SweetAlert atau modal
                                        // Contoh menggunakan SweetAlert:
                                        Swal.fire({
                                            icon: 'info',
                                            title: 'Detail Pajak',
                                            html: `
                        <pre>
                        <div class="row">
                            <div class="col-sm-4"><strong>Nama Pajak</strong></div>
                            <div class="col-sm-4"><strong>:</strong></div>
                            <div class="col-sm-4">${data.NamaPajak}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Ket Pajak</strong></div>
                            <div class="col-sm-4"><strong>:</strong></div>
                            <div class="col-sm-4">${data.KetPajak}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>ppn</strong></div>
                            <div class="col-sm-4"><strong>:</strong></div>
                            <div class="col-sm-4">${data.PajakIDfk[0].Ppn}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Pasal23</strong></div>
                            <div class="col-sm-4"><strong>:</strong></div>
                            <div class="col-sm-4">${data.PajakIDfk[0].Pasal23}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>PphFinal</strong></div>
                            <div class="col-sm-4"><strong>:</strong></div>
                            <div class="col-sm-4">${data.PajakIDfk[0].PphFinal}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>PajakLain</strong></div>
                            <div class="col-sm-4"><strong>:</strong></div>
                            <div class="col-sm-4">${data.PajakIDfk[0].PajakLain}</div>
                        </div>
                        <div class="row">
                            <div class="col-sm-4"><strong>Keterangan</strong></div>
                            <div class="col-sm-4"><strong>:</strong></div>
                            <div class="col-sm-4 align-left">${data.PajakIDfk[0].Keterangan}</div>
                        </div>
                    </pre>
                `,
                                        });
                                    },
                                    error: function () {
                                        Swal.fire({
                                            icon: 'error',
                                            title: 'Oops...',
                                            text: 'Terjadi kesalahan saat mengambil data pajak.',
                                        });
                                    },
                                });
                            }

                            // Fungsi untuk menangani klik pada NamaPajak
                            $(document).on('click', '.namaPajakLink', function (e) {
                                e.preventDefault();
                                var pajak_id = $(this).data('pajakid');
                                showPajakDetail(pajak_id);
                            });
                        });
                    </script>


                </div>
            </div>
        </div>
        <?php include('../includes/footer.php'); ?>