<?php include "../helpers/token_session.php"; ?>
<?php include "../includes/header.php"; ?>

<div id="wrapper">
    <?php include('../includes/sidebar.php'); ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <!-- Main Content -->
        <div id="content">
            <?php include('../includes/navbar.php'); ?>
            <!-- Begin Page Content -->
            <div class="container-fluid">
                <?php include("../actions/my-profile/form-edit-profile.php"); ?>
            </div>
        </div>
    </div>
    <?php include('../includes/footer.php'); ?>
    </body>

    </html>