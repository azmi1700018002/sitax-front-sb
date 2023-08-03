<?php include('../helpers/token_session.php'); ?>
<?php include "../includes/header.php"; ?>

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

                <div style="width: 100%; height: 700px" id="tree"></div>
                <script>
                    fetch(
                        'http://localhost:3000/pubpajak'
                    ) // Assuming the API endpoint is at http://localhost:3000/api/v1/pubpajak
                        .then((response) => response.json())
                        .then((data) => {
                            processAndDisplayData(data);
                        })
                        .catch((error) => {
                            console.error('Error fetching data:', error);
                        });

                    function processAndDisplayData(apiData) {
                        const nodes = {}; // Objek untuk menyimpan node berdasarkan PajakID
                        const rootNode = {}; // Simpan node root di sini

                        // Pertama, buat node untuk setiap entri dalam data API
                        apiData.data.forEach((entry) => {
                            const {
                                PajakID,
                                NamaPajak,
                                ParentPajak,
                                StsParent,
                                KetPajak
                            } = entry;
                            nodes[PajakID] = {
                                id: PajakID,
                                name: NamaPajak,
                                pid: ParentPajak,
                                stsParent: StsParent,
                                ketPajak: KetPajak,
                            };

                            // Jika ini adalah node root (StsParent = 0), simpan ke rootNode
                            if (StsParent === 0) {
                                rootNode[PajakID] = nodes[PajakID];
                            }
                        });

                        // Kedua, buat hubungan parent-child antara node-node tersebut
                        apiData.data.forEach((entry) => {
                            const {
                                PajakID,
                                StsParent
                            } = entry;
                            if (StsParent !== 0) {
                                const parent = nodes[StsParent];
                                if (parent) {
                                    // Jika parent ditemukan, tambahkan node saat ini sebagai anaknya
                                    if (!parent.children) {
                                        parent.children = [];
                                    }
                                    parent.children.push(nodes[PajakID]);
                                }
                            }
                        });

                        // Terakhir, tampilkan sebagai tree menggunakan OrgChart.js
                        const chart = new OrgChart(document.getElementById('tree'), {
                            nodeBinding: {
                                field_0: 'name',
                            },
                            nodes: Object.values(rootNode),
                        });
                    }
                </script>
            </div>
        </div>
    </div>
    <?php include('../includes/footer.php'); ?>