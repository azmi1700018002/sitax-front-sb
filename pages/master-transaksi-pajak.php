<?php
include '../helpers/token_session.php';
include '../includes/header.php';
?>

<div id="wrapper">
    <?php include '../includes/sidebar.php'; ?>
    <div id="content-wrapper" class="d-flex flex-column">
        <div id="content">
            <?php include '../includes/navbar.php'; ?>
            <div class="container-fluid">
                <h3>Master Transaksi Pajak</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Data Yang ditampilkan</li>
                    </ol>
                </nav>
                <button type="button" class="btn btn-outline-primary ms-2" data-ripple-color="dark" data-toggle="modal"
                    data-target="#tambahFile">
                    <i class="fas fa-plus me-2"></i>
                    File
                </button>

                <div style="width: 100%; height: 500px; margin-top: 20px;" id="tree"></div>
                <script>
                    // Di bagian fetch API yang sudah ada
                    const token = "<?php echo $_SESSION['token']; ?>"; // Mengambil token dari session PHP
                    let fileOptions = []; // Definisikan variabel fileOptions di luar fungsi

                    // Mengambil data dari API pubpajak
                    fetch('http://localhost:3000/pubpajak')
                        .then(response => response.json())
                        .then(pubpajakData => {
                            // Mengambil data dari API pajak-detail
                            fetch('http://localhost:3000/auth/pajak-detail', {
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Authorization': `Bearer ${token}`
                                }
                            })
                                .then(response => response.json())
                                .then(pajakDetailData => {
                                    // Mengambil data dari API file
                                    fetch('http://localhost:3000/auth/file', {
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Authorization': `Bearer ${token}`
                                        }
                                    })
                                        .then(response => response.json())
                                        .then(fileData => {
                                            fileOptions = fileData.data.map(file => ({
                                                value: file.FileID,
                                                text: file.FileJudul
                                            }));

                                            processAndDisplayData(pubpajakData, pajakDetailData);
                                        })
                                        .catch(error => console.error('Error fetching file data:', error));
                                })
                                .catch(error => console.error('Error fetching pajak-detail data:', error));
                        })
                        .catch(error => console.error('Error fetching pubpajak data:', error));

                    function processAndDisplayData(apiData, pajakDetailData) {
                        const nodes = {};
                        const rootNode = {};
                        const pajakDetailInfo = {};

                        // Loop melalui data dari API pajak-detail dan simpan informasi sesuai PajakID
                        pajakDetailData.data.forEach(detail => {
                            const {
                                PajakDetailID,
                                PajakID,
                                Ppn,
                                Pasal23,
                                PphFinal,
                                PajakLain,
                                Keterangan,
                            } = detail;
                            pajakDetailInfo[PajakID] = {
                                PajakDetailID,
                                PajakID,
                                Ppn,
                                Pasal23,
                                PphFinal,
                                PajakLain,
                                Keterangan,
                            };
                        });

                        apiData.data.forEach(entry => {
                            const {
                                PajakID,
                                NamaHeader,
                                NamaPajak,
                                ParentPajak,
                                StsPajak,
                                KetPajak,
                                StsParent,
                                FileID,
                            } = entry;
                            nodes[PajakID] = {
                                id: PajakID,
                                NamaHeader: NamaHeader,
                                NamaPajak: NamaPajak,
                                pid: ParentPajak,
                                KetPajak: KetPajak,
                                StsPajak: StsPajak,
                                FileID: FileID,
                                PajakDetailID: pajakDetailInfo[PajakID] ? pajakDetailInfo[PajakID]
                                    .PajakDetailID : '-',
                                Ppn: pajakDetailInfo[PajakID] ? pajakDetailInfo[PajakID].Ppn : '-',
                                Pasal23: pajakDetailInfo[PajakID] ? pajakDetailInfo[PajakID].Pasal23 : '-',
                                PphFinal: pajakDetailInfo[PajakID] ? pajakDetailInfo[PajakID].PphFinal : '-',
                                PajakLain: pajakDetailInfo[PajakID] ? pajakDetailInfo[PajakID].PajakLain : '-',
                                Keterangan: pajakDetailInfo[PajakID] ? pajakDetailInfo[PajakID].Keterangan :
                                    '-',
                            };
                            if (StsPajak === 1) {
                                rootNode[PajakID] = nodes[PajakID];
                            }
                        });

                        apiData.data.forEach(entry => {
                            const {
                                PajakID,
                                StsParent
                            } = entry;
                            if (StsParent !== 0) {
                                const parent = nodes[StsParent];
                                if (parent) {
                                    if (!parent.children) parent.children = [];
                                    parent.children.push(nodes[PajakID]);
                                }
                            }
                        });

                        OrgChart.templates.blueTemplate = {
                            ...OrgChart.templates.ula
                        };
                        OrgChart.templates.blueTemplate.size = [180, 90];
                        OrgChart.templates.blueTemplate.node =
                            '<rect x="0" y="5" height="85" width="{w}" fill="#ffffff" stroke-width="1" stroke="#4e73df" rx="5" ry="5"></rect>' +
                            '<rect x="0" y="5" height="35" width="{w}" fill="#4e73df" stroke-width="1" stroke="#4e73df" rx="5" ry="5"></rect>' +
                            '<line x1="0" y1="40" x2="180" y2="40" stroke-width="5" stroke="#4e73df "></line>';
                        OrgChart.templates.blueTemplate.field_0 =
                            '<text data-width="120" style="font-size: 15px;" fill="white" x="90" y="30" text-anchor="middle">{val}</text>';
                        OrgChart.templates.blueTemplate.field_1 =
                            '<text data-width="130" style="font-size: 12px;" font-weight="bold" fill="#64696b" x="90" y="65" text-anchor="middle">{val}</text>';

                        const chart = new OrgChart(document.getElementById('tree'), {
                            enableDragDrop: false,
                            template: "blueTemplate",
                            nodeBinding: {
                                field_0: 'NamaHeader',
                                field_1: 'NamaPajak',
                            },
                            collapse: {
                                level: 1
                            },
                            editForm: {
                                readOnly: false, // Allow editing
                                titleBinding: "NamaPajak",
                                saveAndCloseBtn: "Save and close",
                                cancelBtn: "Cancel",
                                generateElementsFromFields: true,
                                buttons: {
                                    edit: {
                                        icon: OrgChart.icon.edit(24, 24, "#fff"),
                                        text: "Edit",
                                        hideIfEditMode: true,
                                        hideIfDetailsMode: false,
                                    },
                                    share: null,
                                    pdf: null,
                                },
                                elements: [{
                                    type: 'textbox',
                                    label: 'NamaHeader',
                                    binding: 'NamaHeader',
                                },
                                {
                                    type: 'textbox',
                                    label: 'NamaPajak',
                                    binding: 'NamaPajak',
                                },
                                {
                                    type: 'textbox',
                                    label: 'ParentPajak',
                                    binding: 'pid',
                                },
                                {
                                    type: 'textbox',
                                    label: 'KetPajak',
                                    binding: 'KetPajak',
                                },
                                {
                                    type: 'textbox',
                                    label: 'StsPajak',
                                    binding: 'StsPajak',
                                },
                                {
                                    type: 'select',
                                    label: 'FileID',
                                    binding: 'FileID',
                                    options: fileOptions,
                                    events: {
                                        change: function (event, sender) {
                                            const selectedFileId = event.target
                                                .value; // Mendapatkan nilai file.id yang dipilih
                                            sender.updateData('FileID',
                                                selectedFileId); // Memperbarui data 'FileID' pada chart
                                        }
                                    }
                                }
                                ],
                            },

                            tags: {
                                blue: {
                                    template: "blueTemplate"
                                }
                            },
                            nodes: Object.values(rootNode),
                        });

                        chart.editUI.on('save', function (sender, args) {
                            const dataToSave = args.data; // Data yang akan disimpan
                            const pajak_id = dataToSave.id; // Mengambil PajakID dari data yang akan disimpan
                            const fileID = dataToSave.FileID; // Mengambil nilai 'FileID' yang diperbarui

                            dataToSave.StsPajak = parseInt(dataToSave.StsPajak); // Mengubah nilai menjadi integer
                            dataToSave.ParentPajak = parseInt(dataToSave.pid); // Mengubah nilai menjadi integer

                            // Menambahkan PajakID ke dalam data yang akan dikirim
                            dataToSave.PajakID = pajak_id;
                            dataToSave.Ppn = parseFloat(dataToSave.Ppn);
                            dataToSave.Pasal23 = parseFloat(dataToSave.Pasal23);
                            dataToSave.PphFinal = parseFloat(dataToSave.PphFinal);
                            dataToSave.PajakLain = parseFloat(dataToSave.PajakLain);

                            const token = "<?php echo $_SESSION['token']; ?>"; // Mengambil token dari session PHP

                            // Menggunakan Promise.all untuk menunggu kedua permintaan fetch selesai
                            Promise.all([
                                fetch(`http://localhost:3000/auth/pajak/${pajak_id}`, {
                                    method: 'PUT', // Metode HTTP untuk menyimpan data
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Authorization': `Bearer ${token}` // Menambahkan header Authorization dengan bearer token
                                    },
                                    body: JSON.stringify(
                                        dataToSave) // Mengubah data menjadi JSON string
                                }).then(response => response.json()),

                                // Jika ada informasi pajak detail yang sesuai, lakukan update ke API
                                dataToSave.PajakDetailID ?
                                    fetch(
                                        `http://localhost:3000/auth/pajak-detail/${dataToSave.PajakDetailID}`, {
                                        method: 'PUT',
                                        headers: {
                                            'Content-Type': 'application/json',
                                            'Authorization': `Bearer ${token}`
                                        },
                                        body: JSON.stringify({
                                            PajakID: dataToSave.PajakID,
                                            Ppn: dataToSave.Ppn,
                                            Pasal23: dataToSave.Pasal23,
                                            PphFinal: dataToSave.PphFinal,
                                            PajakLain: dataToSave.PajakLain,
                                            Keterangan: dataToSave.Keterangan,
                                        })
                                    }).then(response => response.json()) :
                                    Promise.resolve(null)
                            ])
                                .then(([savedData, updatedData]) => {
                                    console.log('Data saved:', savedData);
                                    if (updatedData) {
                                        console.log('Data updated:', updatedData);
                                    }
                                    // Lakukan reload halaman setelah penyimpanan/update berhasil
                                    location.reload();
                                })
                                .catch(error => {
                                    console.error('Error saving/updating data:', error);
                                    // Lakukan tindakan lain jika penyimpanan/updating gagal
                                });
                        });

                    }
                </script>

                <!-- Modal Add -->
                <div class="modal fade" id="tambahFile" tabindex="-1" aria-labelledby="tambahFile" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="exampleModalLabel">Tambah File</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span
                                        aria-hidden="true">×</span></button>
                            </div>
                            <div class="modal-body">
                                <form action="../actions/file/add_file.php" method="POST" enctype="multipart/form-data">

                                    <div class="mb-3">
                                        <div class="form-group">
                                            <label for="FileJenis">File Jenis :</label>
                                            <div class="input-group">
                                                <select class="form-control" name="FileJenis"
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
                                                            if ($referensi["GrpID"] === "FILEJNS") {
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
                                        <label class="form-label" for="FileID">ID File : </label>
                                        <div class="form-outline">
                                            <input type="text" id="FileID" name="FileID" class="form-control"
                                                required />
                                        </div>
                                        <small class="form-text text-muted">
                                            contoh : <span>FL0000000001</span> <strong>harus 12 character !!</strong>
                                        </small>
                                    </div>

                                    <div class="form-group mb-3">
                                        <label for="file_judul">File Judul :</label>
                                        <div class="custom-file">
                                            <input type="hidden" name="MAX_FILE_SIZE" value="1048576">
                                            <input type="file" class="custom-file-input" id="file_judul"
                                                name="file_judul" required>
                                            <label class="custom-file-label add" for="file_judul">choose file</label>
                                        </div>
                                        <small class="form-text text-muted">
                                            Selected file: <span id="selectedFileName">No file chosen</span><button
                                                type="button" class="btn btn-link p-0 ml-2" id="clearFileSelection"
                                                style="display: none;">&times;</button>
                                        </small>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label" for="FilePath">File Path : </label>
                                        <div class="form-outline">
                                            <input type="text" id="FilePath" name="FilePath" class="form-control"
                                                required />
                                        </div>
                                        <small class="form-text text-muted">
                                            path : <span>../../SitaxUpdate/file/</span>
                                        </small>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-success">Tambah</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <script>
                        // Ambil elemen input file, label, dan tombol silang
                        const inputJudul = document.getElementById("file_judul");
                        const labelJudul = document.querySelector(".custom-file-label.add");
                        const clearButton = document.getElementById("clearFileSelection");
                        const selectedFileName = document.getElementById("selectedFileName");

                        // Tambahkan event listener untuk tombol silang
                        clearButton.addEventListener("click", function () {
                            // Hapus pilihan file dengan mereset nilai input file
                            inputJudul.value = "";
                            // Perbarui teks label dan selectedFileName
                            labelJudul.textContent = "choose file";
                            selectedFileName.textContent = "No file chosen";
                            // Sembunyikan kembali tombol silang
                            clearButton.style.display = "none";
                        });

                        // Tambahkan event listener untuk mendeteksi perubahan pada input file
                        inputJudul.addEventListener("change", function () {
                            // Perbarui teks label dengan nama file yang dipilih
                            labelJudul.textContent = inputJudul.files[0].name;
                            // Perbarui teks selectedFileName juga jika perlu
                            selectedFileName.textContent = inputJudul.files[0].name;
                            // Tampilkan tombol silang setelah file dipilih dan diunggah
                            clearButton.style.display = "inline";
                        });
                    </script>
                </div>

            </div>
        </div>
        <?php include '../includes/footer.php'; ?>
    </div>
</div>
</body>

</html>