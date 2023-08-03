<?php include('../helpers/token_session.php'); ?>
<?php include "../includes/header.php"; ?>

<?php if (isset($_SESSION["login_success"])) { ?>
<script>
Swal.fire({
    icon: 'success',
    title: '<?php echo $_SESSION['login_success']; ?>',
    showConfirmButton: false,
    timer: 8000
});
</script>
<?php unset($_SESSION["login_success"]); ?>
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
                <h3>Selamat Datang <h3>

                        <!-- isi disini  -->
            </div>
        </div>
    </div>
    <?php include('../includes/footer.php'); ?>