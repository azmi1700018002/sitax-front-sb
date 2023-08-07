<?php include "../helpers/token_session.php"; ?>
<?php include "../includes/header.php"; ?>

<!-- Sweet Alert Success User -->
<?php if (isset($_SESSION["message_user_success"])) { ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: '<?php echo $_SESSION["message_user_success"]; ?>',
            showConfirmButton: false,
            timer: 8000
        });
    </script>
    <?php unset($_SESSION["message_user_success"]); ?>
<?php } ?>

<!-- Sweet Alert Faield User -->
<?php if (isset($_SESSION["message_user_failed"])) { ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: '<?php echo $_SESSION["message_user_failed"]; ?>',
            showConfirmButton: false,
            timer: 8000
        });
    </script>
    <?php unset($_SESSION["message_user_failed"]); ?>
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
                <h3>User</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Data yang ditampilkan</li>
                    </ol>
                </nav>
                <button type="button" class="btn btn-outline-primary ms-auto" data-ripple-color="dark"
                    data-toggle="modal" data-target="#tambahUser">
                    <i class="fas fa-plus me-2"></i>
                    User
                </button>

                <div class="my-4 table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Nama Lengkap</th>
                                <th class="text-center">Email</th>
                                <th class="text-center">HostIP</th>
                                <th class="text-center">Kd Kantor</th>
                                <th class="text-center">StsUser</th>
                                <th class="text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php include "../actions/user/get_user.php"; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Modal Add -->
                <div class="modal fade" id="tambahUser" tabindex="-1" aria-labelledby="tambahUser" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah User</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <form action="../actions/user/add_user.php" method="POST" enctype="multipart/form-data">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="form-group">
                                                    <label for="GroupID">Group :</label>
                                                    <div class="input-group">
                                                        <select class="form-control" name="GroupID"
                                                            aria-label="Default select example">
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
                                                            $data = json_decode($response, true);
                                                            if (isset($data["data"])) {
                                                                // Loop untuk menghasilkan opsi dalam elemen select
                                                                foreach ($data["data"] as $group) {
                                                                    $selected = ($group["GroupID"] == $selectedGroupID) ? "selected" : ""; // Menentukan apakah opsi ini dipilih
                                                                    $optionValue = $group["GroupID"]; // Menggunakan GroupID sebagai nilai opsi
                                                                    $optionText = $group["GroupID"] . " - " . $group["GroupNama"]; // Menggabungkan GroupID dan GroupNama sebagai teks opsi
                                                                    echo "<option value='" . $optionValue . "' data-groupnama='" . $group["GroupNama"] . "' $selected>" . $optionText . "</option>";
                                                                }
                                                            } else {
                                                                echo '<option value="" disabled selected>Tidak ada data group</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <div class="form-group">
                                                    <label for="KdKantor">Kantor :</label>
                                                    <div class="input-group">
                                                        <select class="form-control" name="KdKantor"
                                                            aria-label="Default select example">
                                                            <?php
                                                            require_once "../config/server.php";

                                                            $url = $baseUrl . "kantor";
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
                                                                foreach ($data["data"] as $kantor) {
                                                                    $selected = ($kantor["KdKantor"] == $selectedGroupID) ? "selected" : ""; // Menentukan apakah opsi ini dipilih
                                                                    $optionValue = $kantor["KdKantor"]; // Menggunakan KdKantor sebagai nilai opsi
                                                                    $optionText = $kantor["KdKantor"] . " - " . $kantor["AlamatKantor"]; // Menggabungkan KdKanotr dan AlamatKantor sebagai teks opsi
                                                                    echo "<option value='" . $optionValue . "' data-alamatkantor='" . $kantor["AlamatKantor"] . "' $selected>" . $optionText . "</option>";
                                                                }
                                                            } else {
                                                                echo '<option value="" disabled selected>Tidak ada data kantor</option>';
                                                            }
                                                            ?>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="Username">Username : </label>
                                                <div class="form-outline">
                                                    <input type="text" id="Username" name="Username"
                                                        class="form-control" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="NamaLengkap">Nama Lengkap :
                                                </label>
                                                <div class="form-outline">
                                                    <input type="text" id="NamaLengkap" name="NamaLengkap"
                                                        class="form-control" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="Email">Email : </label>
                                                <div class="form-outline">
                                                    <input type="text" id="Email" name="Email" class="form-control"
                                                        required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="Password">Password : </label>
                                                <div class="form-outline">
                                                    <input type="text" id="Password" name="Password"
                                                        class="form-control" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="HostIP">HostIP : </label>
                                        <div class="form-outline">
                                            <input type="text" id="HostIP" name="HostIP" class="form-control"
                                                required />
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="StsUser">Status :</label>
                                            <div class="input-group">
                                                <select class="form-control" name="StsUser"
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
                                                            if ($referensi["GrpID"] === "StsUser") {
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
                                        <label class="form-label" for="profile_picture">Upload Profile :
                                        </label>
                                        <div class="form-outline">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="1048576" />
                                            <input type="file" id="profile_picture" name="profile_picture"
                                                class="form-control" />
                                        </div>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger"
                                            data-mdb-dismiss="modal">Batal</button>
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