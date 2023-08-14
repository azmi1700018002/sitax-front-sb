<?php include "../helpers/token_session.php"; ?>
<?php include "../includes/header.php"; ?>

<!-- Sweet Alert Success Kantor -->
<?php if (isset($_SESSION["message_kantor_success"])) { ?>
<script>
Swal.fire({
    icon: 'success',
    title: '<?php echo $_SESSION["message_kantor_success"]; ?>',
    showConfirmButton: false,
    timer: 8000
});
</script>
<?php unset($_SESSION["message_kantor_success"]); ?>
<?php } ?>

<!-- Sweet Alert Faield Kantor -->
<?php if (isset($_SESSION["message_kantor_failed"])) { ?>
<script>
Swal.fire({
    icon: 'error',
    title: '<?php echo $_SESSION["message_kantor_failed"]; ?>',
    showConfirmButton: false,
    timer: 8000
});
</script>
<?php unset($_SESSION["message_kantor_failed"]); ?>
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
                <h3>Kantor</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Data yang ditampilkan</li>
                    </ol>
                </nav>
                <?php
                $groupIDToCheck = $_SESSION["GroupID"];

                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['menuID'])) {
                    $menuID = $_POST['menuID'];

                    // Loop through each menu item in the data
                    foreach ($data["data"] as $menuItem) {
                        foreach ($menuItem["MenuIDfk"] as $menuIDfk) {
                            // Check if IsCreated is 1 and MenuID matches current page's MenuID
                            if ($menuIDfk["IsCreated"] === "1" && $menuIDfk["MenuID"] === $menuID && $menuIDfk["GroupID"] === $groupIDToCheck) {
                                // Add the button HTML
                                echo '
                                <button type="button" class="btn btn-outline-primary ms-auto" data-ripple-color="dark"
                                data-toggle="modal" data-target="#tambahKantor">
                                <i class="fas fa-plus me-2"></i>
                                Kantor
                            </button>';
                                break; // No need to continue checking other MenuIDfk entries for this menu item
                            }
                        }
                    }
                }
                ?>
                <!-- <button type="button" class="btn btn-outline-primary ms-auto" data-ripple-color="dark"
                    data-toggle="modal" data-target="#tambahKantor">
                    <i class="fas fa-plus me-2"></i>
                    Kantor
                </button> -->

                <div class="my-4 table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <!-- Tambahkan class "all" pada setiap <th> -->
                                <th class="text-center all">No</th>
                                <th class="text-center all">Kd Kantor</th>
                                <th class="text-center all">Kd Induk</th>
                                <th class="text-center all">Alamat Kantor</th>
                                <th class="text-center all">Kota Alamat</th>
                                <th class="text-center all">No Telp</th>
                                <th class="text-center all">No Faks</th>
                                <th class="text-center all">Kd Status Kantor</th>
                                <th class="text-center all">Kd Jns Kantor</th>
                                <th class="text-center all">Kd Bank1</th>
                                <th class="text-center all">Kd Bank2</th>
                                <th class="text-center all">NPWP</th>
                                <th class="text-center all">Kd Pejabat1</th>
                                <th class="text-center all">Kd Pejabat2</th>
                                <th class="text-center all">Flg Data</th>
                                <th class="text-center all">Kd Kantor Lama</th>
                                <th class="text-center all">Kd Lokasi</th>
                                <?php
                                // Loop through each menu item in the data
                                foreach ($data["data"] as $menuItem) {
                                    foreach ($menuItem["MenuIDfk"] as $menuIDfk) {
                                        // Check if IsCreated is 1 and MenuID matches current page's MenuID
                                        if ($menuIDfk["IsUpdated"] === "1" && $menuIDfk["MenuID"] === $menuID && $menuIDfk["GroupID"] === $groupIDToCheck || $menuIDfk["IsDeleted"] === "1" && $menuIDfk["MenuID"] === $menuID && $menuIDfk["GroupID"] === $groupIDToCheck) {
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
                            <?php include "../actions/kantor/get_kantor.php"; ?>
                        </tbody>
                    </table>
                </div>



                <!-- Modal Add -->
                <div class="modal fade" id="tambahKantor" tabindex="-1" aria-labelledby="tambahKantor"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah Kantor</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <form action="../actions/kantor/add_kantor.php" method="POST"
                                    enctype="multipart/form-data">

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="KdKantor">KdKantor:</label>
                                                <div class="form-outline">
                                                    <input type="text" id="KdKantor" name="KdKantor"
                                                        class="form-control" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="KdInduk">KdInduk:</label>
                                                <div class="form-outline">
                                                    <input type="text" id="KdInduk" name="KdInduk" class="form-control"
                                                        required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="AlamatKantor">AlamatKantor:</label>
                                                <div class="form-outline">
                                                    <input type="text" id="AlamatKantor" name="AlamatKantor"
                                                        class="form-control" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="KotaAlamat">KotaAlamat:</label>
                                                <div class="form-outline">
                                                    <input type="text" id="KotaAlamat" name="KotaAlamat"
                                                        class="form-control" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="NoTlp">NoTlp:</label>
                                                <div class="form-outline">
                                                    <input type="text" id="NoTlp" name="NoTlp" class="form-control"
                                                        required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="NoFaks">NoFaks:</label>
                                                <div class="form-outline">
                                                    <input type="text" id="NoFaks" name="NoFaks" class="form-control"
                                                        required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="KdStsKantor">KdStsKantor:</label>
                                                <div class="form-outline">
                                                    <input type="text" id="KdStsKantor" name="KdStsKantor"
                                                        class="form-control" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="KdJnsKantor">KdJnsKantor:</label>
                                                <div class="form-outline">
                                                    <input type="text" id="KdJnsKantor" name="KdJnsKantor"
                                                        class="form-control" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="KdBank1">KdBank1:</label>
                                                <div class="form-outline">
                                                    <input type="text" id="KdBank1" name="KdBank1" class="form-control"
                                                        required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="KdBank2">KdBank2:</label>
                                                <div class="form-outline">
                                                    <input type="text" id="KdBank2" name="KdBank2" class="form-control"
                                                        required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="NPWP">NPWP:</label>
                                        <div class="form-outline">
                                            <input type="text" id="NPWP" name="NPWP" class="form-control long-input"
                                                required />
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="KdPejabat1">KdPejabat1:</label>
                                                <div class="form-outline">
                                                    <input type="text" id="KdPejabat1" name="KdPejabat1"
                                                        class="form-control" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="KdPejabat2">KdPejabat2:</label>
                                                <div class="form-outline">
                                                    <input type="text" id="KdPejabat2" name="KdPejabat2"
                                                        class="form-control" required />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="FlgData">FlgData:</label>
                                        <div class="form-outline">
                                            <input type="text" id="FlgData" name="FlgData" class="form-control"
                                                required />
                                        </div>
                                    </div>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="KdKantorLama">KdKantorLama:</label>
                                                <div class="form-outline">
                                                    <input type="text" id="KdKantorLama" name="KdKantorLama"
                                                        class="form-control" required />
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label class="form-label" for="KdLokasi">KdLokasi:</label>
                                                <div class="form-outline">
                                                    <input type="text" id="KdLokasi" name="KdLokasi"
                                                        class="form-control" required />
                                                </div>
                                            </div>
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
        <?php include('../includes/footer.php'); ?>
    </div>
</div>
</body>

</html>