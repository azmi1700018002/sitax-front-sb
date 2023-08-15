<?php include "../helpers/token_session.php"; ?>
<?php include "../includes/header.php"; ?>

<!-- Sweet Alert Success PanduanPajak -->
<?php if (isset($_SESSION["message_panduan_pajak_success"])) { ?>
<script>
Swal.fire({
    icon: 'success',
    title: '<?php echo $_SESSION["message_panduan_pajak_success"]; ?>',
    showConfirmButton: false,
    timer: 8000
});
</script>
<?php unset($_SESSION["message_panduan_pajak_success"]); ?>
<?php } ?>

<!-- Sweet Alert Faield PanduanPajak -->
<?php if (isset($_SESSION["message_panduan_pajak_failed"])) { ?>
<script>
Swal.fire({
    icon: 'error',
    title: '<?php echo $_SESSION["message_panduan_pajak_failed"]; ?>',
    showConfirmButton: false,
    timer: 8000
});
</script>
<?php unset($_SESSION["message_panduan_pajak_failed"]); ?>
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
                <h3 class="mb-3">Panduan Pajak PT. Bank BPD DIY</h3>
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
                                data-toggle="modal" data-target="#tambahPanduanPajak">
                                <i class="fas fa-plus me-2"></i>
                                Panduan Pajak
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
                    data-toggle="modal" data-target="#tambahPanduanPajak">
                    <i class="fas fa-plus me-2"></i>
                    Panduan Pajak
                </button> -->

                <div class="container mt-4">
                    <form class="form-inline mb-2" action="" method="GET">
                        <div class="input-group">
                            <!-- <input type="text" class="form-control" name="search"
                                placeholder="Cari Nama Panduan Pajak..."> -->
                            <input type="text" class="form-control" name="search" id="searchInput"
                                placeholder="Cari Nama Panduan Pajak..."
                                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>">

                            <div class="input-group-append">
                                <!-- <button class="btn btn-primary" type="submit">Cari</button> -->
                                <button class="btn btn-success" type="reset">Reset</button>
                            </div>
                        </div>
                    </form>
                    <div id="searchResults"></div>
                    <?php include "../actions/panduan-pajak/get_panduan_pajak.php"; ?>
                    <!-- Pagination Display -->
                    <div class="pagination justify-content-end">
                        <ul class="pagination">
                            <?php
                            $totalPages = ceil($totalFilteredItems / $itemsPerPage);

                            for ($page = 1; $page <= $totalPages; $page++) {
                                echo '<li class="page-item' . ($page === $currentPage ? ' active' : '') . '">';
                                echo '<a class="page-link" href="?page=' . $page . '">' . $page . '</a>';
                                echo '</li>';
                            }
                            ?>
                        </ul>
                    </div>
                </div>


                <!-- Modal PDF -->
                <div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-lg">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="pdfModalLabel"></h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body" id="pdfModalBody">
                                <!-- Konten PDF akan ditampilkan di sini -->
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Modal Add -->
                <div class="modal fade" id="tambahPanduanPajak" tabindex="-1" aria-labelledby="tambahPanduanPajak"
                    aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah Panduan</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <form action="../actions/panduan-pajak/add_panduan_pajak.php" method="POST"
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

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="PanduanPajakID">ID Panduan Pajak
                                                        :
                                                    </label>
                                                    <div class="form-outline">
                                                        <input type="text" id="PanduanPajakID" name="PanduanPajakID"
                                                            class="form-control" required />
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        contoh : <span>1</span>
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="NamaPanduanPajak">Nama Panduan
                                                        Pajak :
                                                    </label>
                                                    <div class="form-outline">
                                                        <input type="text" id="NamaPanduanPajak" name="NamaPanduanPajak"
                                                            class="form-control" required />
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <label class="form-label" for="ParentPanduanPajak">Parent
                                                        Panduan Pajak
                                                        : </label>
                                                    <div class="form-outline">
                                                        <input type="text" id="ParentPanduanPajak"
                                                            name="ParentPanduanPajak" class="form-control" />
                                                    </div>
                                                    <small class="form-text text-muted">
                                                        contoh : <span>1</span> <strong>Boleh dikosongkan</strong>
                                                    </small>
                                                </div>
                                            </div>

                                            <div class="col-md-6">
                                                <div class="mb-3">
                                                    <div class="form-group">
                                                        <label for="StsPanduanPajak">Status Panduan Pajak :</label>
                                                        <div class="input-group">
                                                            <select class="form-control" name="StsPanduanPajak"
                                                                aria-label="Default select example">
                                                                <option value='0'>Tidak Ditampilkan</option>
                                                                <option value='1'>Ditampilkan</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label" for="StsParent">Sts Parent : </label>
                                            <div class="form-outline">
                                                <input type="text" id="StsParent" name="StsParent"
                                                    class="form-control" />
                                            </div>
                                            <small class="form-text text-muted">
                                                contoh : <span>1</span> <strong>Boleh dikosongkan</strong>
                                            </small>
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
                    <script>
                    $(document).ready(function() {
                        // Fungsi untuk menampilkan modal dengan PDF
                        function showPDFModal(pdfURL, namaPanduanPajak) {
                            var modalTitle = document.getElementById('pdfModalLabel');
                            var modalBody = document.getElementById('pdfModalBody');

                            // Set the title of the modal to include the "NamaPanduanPajak"
                            modalTitle.textContent = 'File PDF: ' + namaPanduanPajak;

                            var pdfIframe = document.createElement('iframe');
                            pdfIframe.src = pdfURL;
                            pdfIframe.width = '100%';
                            pdfIframe.height = '550';
                            pdfIframe.frameBorder = '0';

                            modalBody.innerHTML = '';
                            modalBody.appendChild(pdfIframe);

                            $('#pdfModal').modal('show');
                        }

                        // Menggunakan AJAX untuk mencari file saat tombol "Buka" diklik
                        $('.pdfopen').click(function(e) {
                            e.preventDefault();
                            var fileID = $(this).data('fileid');
                            var namaPanduanPajak = $(this).data(
                                'filejudul'); // Correctly retrieve "NamaPanduanPajak" value
                            var pdfURL = getPDFURL(fileID);
                            showPDFModal(pdfURL,
                                namaPanduanPajak); // Pass the "NamaPanduanPajak" value to the function
                        });

                        // Fungsi untuk mendapatkan URL PDF berdasarkan FileID
                        function getPDFURL(fileID) {
                            var fileData = <?php echo json_encode($data['data']); ?>;
                            var pdfURL = '';
                            for (var i = 0; i < fileData.length; i++) {
                                if (fileData[i]['FileID'] === fileID) {
                                    pdfURL = '../../SitaxUpdate/file/' + fileData[i]['FileJudul'];
                                    break;
                                }
                            }
                            return pdfURL;
                        }
                    });
                    </script>
                    <script>
                    document.addEventListener("DOMContentLoaded", function() {
                        const searchInput = document.getElementById("searchInput");
                        const form = document.querySelector("form");

                        // Gantikan kode performSearchAjax dengan kode berikut
                        function performSearchAjax(searchTerm) {
                            $.ajax({
                                url: '/get_panduan_pajak.php', // Ganti dengan URL sesuai kebutuhan
                                type: 'GET',
                                data: {
                                    search: searchTerm
                                },
                                success: function(data) {
                                    $('#searchResults').html(data);
                                },
                                error: function(error) {
                                    console.error("Error performing search:", error);
                                }
                            });
                        }


                        // Gantikan dengan kode berikut
                        searchInput.addEventListener("input", function() {
                            const searchTerm = searchInput.value;
                            performSearchAjax(searchTerm);
                        });


                        // Event listener for form reset
                        form.addEventListener("reset", function() {
                            const queryParams = new URLSearchParams(window.location.search);
                            queryParams.delete("search");
                            queryParams.delete("page");
                            window.location.search = queryParams.toString();
                        });
                    });
                    </script>
                </div>
            </div>
        </div>
        <?php include('../includes/footer.php'); ?>
        </body>

        </html>