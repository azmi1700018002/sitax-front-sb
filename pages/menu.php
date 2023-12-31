<?php include "../helpers/token_session.php"; ?>
<?php include "../includes/header.php"; ?>

<!-- Sweet Alert Success Menu -->
<?php if (isset($_SESSION["message_menu_success"])) { ?>
    <script>
        Swal.fire({
            icon: 'success',
            title: '<?php echo $_SESSION["message_menu_success"]; ?>',
            showConfirmButton: false,
            timer: 8000
        });
    </script>
    <?php unset($_SESSION["message_menu_success"]); ?>
<?php } ?>

<!-- Sweet Alert Faield Menu -->
<?php if (isset($_SESSION["message_menu_failed"])) { ?>
    <script>
        Swal.fire({
            icon: 'error',
            title: '<?php echo $_SESSION["message_menu_failed"]; ?>',
            showConfirmButton: false,
            timer: 8000
        });
    </script>
    <?php unset($_SESSION["message_menu_failed"]); ?>
<?php } ?>

<div id="wrapper">
    <?php include('../includes/sidebar.php'); ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <?php include('../includes/navbar.php'); ?>
            <!-- Begin Page Content -->
            <div class="container-fluid">
                <!-- Page Heading -->

                <h3>Menu</h3>
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
                                data-toggle="modal" data-target="#tambahMenu">
                                <i class="fas fa-plus me-2"></i>
                                Menu
                            </button>';
                                break; // No need to continue checking other MenuIDfk entries for this menu item
                            }
                        }
                    }
                } else {
                    echo "No active menu selected.";
                }
                ?>

                <!--                 
                <button type="button" class="btn btn-outline-primary ms-auto" data-ripple-color="dark"
                    data-toggle="modal" data-target="#tambahMenu">
                    <i class="fas fa-plus me-2"></i>
                    Menu
                </button> -->

                <div class="my-4 table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th class="text-center">No</th>
                                <th class="text-center">ID</th>
                                <th class="text-center">Nama</th>
                                <th class="text-center">Link</th>
                                <th class="text-center">Deskripsi</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Icon</th>
                                <th class="text-center">Kategori</th>
                                <th class="text-center">Parent ID</th>
                                <th class="text-center">Parent Sts</th>
                                <th class="text-center">No Urut</th>
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
                            <?php include "../actions/menu/get_menu.php"; ?>
                        </tbody>
                    </table>
                </div>


                <!-- Modal Add -->
                <div class="modal fade" id="tambahMenu" tabindex="-1" aria-labelledby="tambahMenu" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah Menu</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <form action="../actions/menu/add_menu.php" method="POST" enctype="multipart/form-data">

                                    <div class="mb-3">
                                        <label class="form-label" for="MenuID">ID Menu : </label>
                                        <div class="form-outline">
                                            <input type="text" id="MenuID" name="MenuID" class="form-control"
                                                required />
                                        </div>
                                        <small class="form-text text-muted">
                                            contoh : <span>1001</span> <strong>harus 4 character !!</strong>
                                        </small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="MenuNama">Nama Menu : </label>
                                        <div class="form-outline">
                                            <input type="text" id="MenuNama" name="MenuNama" class="form-control"
                                                required />
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="MenuLink">Link Menu : </label>
                                        <div class="form-outline">
                                            <input type="text" id="MenuLink" name="MenuLink" class="form-control" />
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="MenuDeskripsi"> Deskripsi Menu : </label>
                                        <div class="form-outline">
                                            <input type="text" id="MenuDeskripsi" name="MenuDeskripsi"
                                                class="form-control" />
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="MenuStatus">Status Menu : </label>
                                        <div class="form-outline">
                                            <input type="text" id="MenuStatus" name="MenuStatus" class="form-control"
                                                required />
                                        </div>
                                    </div>


                                    <div class="mb-3">
                                        <label class="form-label" for="MenuIcon">Icon Menu : </label>
                                        <div class="form-outline">
                                            <input type="text" id="MenuIcon" name="MenuIcon" class="form-control"
                                                required />
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="MenuKategori">Kategori Menu : </label>
                                        <div class="form-outline">
                                            <input type="text" id="MenuKategori" name="MenuKategori"
                                                class="form-control" required />
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="ParentID">ID Parent : </label>
                                        <div class="form-outline">
                                            <input type="text" id="ParentID" name="ParentID" class="form-control"
                                                required />
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="ParentSts">Parent Sts : </label>
                                        <div class="form-outline">
                                            <input type="text" id="ParentSts" name="ParentSts" class="form-control"
                                                required />
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="NoUrut">No Urut : </label>
                                        <div class="form-outline">
                                            <input type="text" id="NoUrut" name="NoUrut" class="form-control"
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
            </div>
        </div>
        <?php include('../includes/footer.php'); ?>
    </div>
</div>
</body>

</html>