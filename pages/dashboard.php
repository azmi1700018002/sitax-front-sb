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
                        <div class="container d-flex align-items-center justify-content-center"
                            style="min-height: 100vh;">
                            <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js">
                            </script>
                            <lottie-player
                                src="https://lottie.host/d5297bd2-6280-45b1-b5d0-da07208c978e/I7LKGDZerm.json" speed="1"
                                style="width: 600px; height: 600px" loop autoplay direction="1" mode="normal">
                            </lottie-player>
                        </div>
            </div>
        </div>
        <?php include('../includes/footer.php'); ?>
    </div>
</div>
</body>

</html>