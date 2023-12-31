<?php include('../helpers/token_session.php'); ?>
<?php include('../includes/header.php'); ?>

<!-- Sweet Alert Success kewenangan -->
<?php if (isset($_SESSION["message_kewenangan_success"])) { ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: '<?php echo $_SESSION["message_kewenangan_success"]; ?>',
            showConfirmButton: false,
            timer: 8000
        });
    </script>
    <?php unset($_SESSION["message_kewenangan_success"]); ?>

<?php } ?>

<!-- Sweet Alert Faield kewenangan -->
<?php if (isset($_SESSION["message_kewenangan_failed"])) { ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: '<?php echo $_SESSION["message_kewenangan_failed"]; ?>',
            showConfirmButton: false,
            timer: 8000
        });
    </script>
    <?php unset($_SESSION["message_kewenangan_failed"]); ?>
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

                <h3>Kewenangan</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Data Yang ditampilkan</li>
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
                            data-toggle="modal" data-target="#tambahKewenangan">
                            <i class="fas fa-plus me-2"></i>
                            Kewenangan
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
                    data-toggle="modal" data-target="#tambahKewenangan">
                    <i class="fas fa-plus me-2"></i>
                    Kewenangan
                </button> -->

                <div class="my-4 table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">Group</th>
                                <th class="text-center">Menu</th>
                                <th class="text-center">IsCreated</th>
                                <th class="text-center">IsUpdated</th>
                                <th class="text-center">IsDeleted</th>
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
                            <?php include "../actions/kewenangan/get_kewenangan.php"; ?>
                        </tbody>
                    </table>
                </div>


                <!-- Modal Add -->
                <div class="modal fade" id="tambahKewenangan" tabindex="-1" aria-labelledby="tambahKewenangan"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah Kewenangan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <form action="../actions/kewenangan/add_kewenangan.php" method="POST"
                                    enctype="multipart/form-data">
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="GrupID">Group :</label>
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
                                                        // Loop to generate options within the select element
                                                        foreach ($data["data"] as $group) {
                                                            $selected = ($group["GroupID"] == $selectedGroupID) ? "selected" : ""; // Determine if this option is selected
                                                            $optionValue = $group["GroupID"]; // Use GroupID as the option value
                                                            $optionText = $group["GroupID"] . " - " . $group["GroupNama"]; // Combine GroupID and GroupNama as the option text
                                                            echo "<option value='" . $optionValue . "' data-groupnama='" . $group["GroupNama"] . "' $selected>" . $optionText . "</option>";
                                                        }
                                                    } else {
                                                        echo '<option value="" disabled selected>No group data available</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="MenuID">Menu :</label>
                                            <div class="input-group">
                                                <select class="form-control" name="MenuID"
                                                    aria-label="Default select example">
                                                    <?php
                                                    require_once "../config/server.php";

                                                    $url = $baseUrl . "menu";
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
                                                        // Loop to generate options within the select element
                                                        foreach ($data["data"] as $menu) {
                                                            $selected = ($menu["MenuID"] == $selectedGroupID) ? "selected" : ""; // Determine if this option is selected
                                                            $optionValue = $menu["MenuID"]; // Use MenuID as the option value
                                                            $optionText = $menu["MenuID"] . " - " . $menu["MenuNama"]; // Combine MenuID and MenuNama as the option text
                                                            echo "<option value='" . $optionValue . "' data-menunama='" . $menu["MenuNama"] . "' $selected>" . $optionText . "</option>";
                                                        }
                                                    } else {
                                                        echo '<option value="" disabled selected>No menu data available</option>';
                                                    }
                                                    ?>
                                                </select>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="IsCreated">IsCreated :</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="IsCreated"
                                                    id="IsCreated1" value="1" />
                                                <label class="form-check-label" for="IsCreated1"> Aktif </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="IsCreated"
                                                    id="IsCreated0" value="0" />
                                                <label class="form-check-label" for="IsCreated0"> Tidak Aktif </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label class="form-label" for="IsUpdated">IsUpdated :</label>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="IsUpdated"
                                                    id="IsUpdated1" value="1" />
                                                <label class="form-check-label" for="IsUpdated1"> Aktif </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="IsUpdated"
                                                    id="IsUpdated0" value="0" />
                                                <label class="form-check-label" for="IsUpdated0"> Tidak Aktif </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <label class="form-label" for="IsDeleted">IsDeleted :</label>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="IsDeleted"
                                                id="IsDeleted1" value="1" />
                                            <label class="form-check-label" for="IsDeleted1"> Aktif </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="IsDeleted"
                                                id="IsDeleted0" value="0" />
                                            <label class="form-check-label" for="IsDeleted0"> Tidak Aktif </label>
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
                </div>
            </div>
        </div>
        <?php include('../includes/footer.php'); ?>
    </div>
</div>
</body>

</html>