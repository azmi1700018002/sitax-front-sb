<?php include "../helpers/token_session.php"; ?>
<?php include "../includes/header.php"; ?>

<!-- Sweet Alert Success User -->
<?php if (isset($_SESSION["message_group_success"])) { ?>
<script>
Swal.fire({
    icon: 'success',
    title: '<?php echo $_SESSION["message_group_success"]; ?>',
    showConfirmButton: false,
    timer: 8000
});
</script>
<?php unset($_SESSION["message_group_success"]); ?>
<?php } ?>

<!-- Sweet Alert Faield User -->
<?php if (isset($_SESSION["message_group_failed"])) { ?>
<script>
Swal.fire({
    icon: 'error',
    title: '<?php echo $_SESSION["message_group_failed"]; ?>',
    showConfirmButton: false,
    timer: 8000
});
</script>
<?php unset($_SESSION["message_group_failed"]); ?>
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
                <h3>Group</h3>
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
                data-toggle="modal" data-target="#tambahGroup">
                <i class="fas fa-plus me-2"></i>
                Group
                </button>';
                                break; // No need to continue checking other MenuIDfk entries for this menu item
                            }
                        }
                    }
                } else {
                    echo "No active menu selected.";
                }
                ?>

                <div class="my-4 table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center all">No</th>
                                <th class="text-center all">ID Group</th>
                                <th class="text-center all">Nama Group</th>
                                <th class="text-center all">Deskripsi Group</th>
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
                            <?php include "../actions/group/get_group.php"; ?>
                        </tbody>
                    </table>
                </div>


                <!-- Modal Add -->
                <div class="modal fade" id="tambahGroup" tabindex="-1" aria-labelledby="tambahGroup" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah Group</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <form action="../actions/group/add_group.php" method="POST"
                                    enctype="multipart/form-data">

                                    <div class="mb-3">
                                        <label class="form-label" for="GroupID">ID Group : </label>
                                        <div class="form-outline">
                                            <input type="text" id="GroupID" name="GroupID" class="form-control"
                                                required />
                                        </div>
                                        <small class="form-text text-muted">
                                            contoh : <span>0001</span> <strong>harus 4 character !!</strong>
                                        </small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="GroupNama">Nama Group : </label>
                                        <div class="form-outline">
                                            <input type="text" id="GroupNama" name="GroupNama" class="form-control"
                                                required />
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="GroupDeskripsi">Deskripsi Group : </label>
                                        <div class="form-outline">
                                            <input type="text" id="GroupDeskripsi" name="GroupDeskripsi"
                                                class="form-control" required />
                                        </div>
                                    </div>

                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary">Tambah</button>
                            </div>
                            </form>
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