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
                <h3>Master Transaksi Pajak</h3>
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item active" aria-current="page">Data Yang ditampilkan</li>
                    </ol>
                </nav>
                <div style="width: 100%; height: 500px" id="tree"></div>
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
                            NamaHeader,
                            NamaPajak,
                            ParentPajak,
                            StsPajak,
                            KetPajak,
                            StsParent,
                            PajakIDfk,
                        } = entry;
                        nodes[PajakID] = {
                            id: PajakID,
                            title: NamaHeader,
                            name: NamaPajak,
                            pid: ParentPajak,
                            ketPajak: KetPajak,
                            // Menyimpan nilai Ppn dan Pasal23 dalam node
                            ppn: PajakIDfk.length > 0 ? PajakIDfk[0].Ppn : '-',
                            pasal23: PajakIDfk.length > 0 ? PajakIDfk[0].Pasal23 : '-',
                            pphfinal: PajakIDfk.length > 0 ? PajakIDfk[0].PphFinal : '-',
                            pajaklain: PajakIDfk.length > 0 ? PajakIDfk[0].PajakLain : '-',
                            keterangan: PajakIDfk.length > 0 ? PajakIDfk[0].Keterangan : '-'
                        };

                        // Jika ini adalah node root (StsPajak = 1), simpan ke rootNode
                        if (StsPajak === 1) {
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


                    OrgChart.templates.blueTemplate = Object.assign({},
                        OrgChart.templates.ula
                    );
                    OrgChart.templates.blueTemplate.size = [250, 105];

                    OrgChart.templates.blueTemplate = Object.assign({},
                        OrgChart.templates.blueTemplate
                    );
                    OrgChart.templates.blueTemplate.node =
                        '<rect x="0" y="5" height="100" width="{w}" fill="#ffffff" stroke-width="1" stroke="#4e73df" rx="5" ry="5"></rect>' +
                        '<rect x="0" y="5" height="35" width="{w}" fill="#4e73df" stroke-width="1" stroke="#4e73df" rx="5" ry="5"></rect>' +
                        '<line x1="0" y1="40" x2="250" y2="40" stroke-width="5" stroke="#4e73df "></line>';
                    OrgChart.templates.blueTemplate.field_0 =
                        '<text data-width="230" style="font-size: 18px;" fill="white" x="125" y="30" text-anchor="middle">{val}</text>';
                    OrgChart.templates.blueTemplate.field_1 =
                        '<text data-width="230" style="font-size: 18px;" font-weight="bold" fill="#64696b" x="125" y="80" text-anchor="middle">{val}</text>';

                    // Terakhir, tampilkan sebagai tree menggunakan OrgChart.js
                    const chart = new OrgChart(document.getElementById('tree'), {
                        enableDragDrop: true,
                        template: "blueTemplate",
                        nodeBinding: {
                            field_0: 'title',
                            field_1: 'name',
                        },
                        tags: {
                            blue: {
                                template: "blueTemplate",
                            },
                        },
                        nodes: Object.values(rootNode),
                    });
                }
                </script>

            </div>

        </div>
        <?php include('../includes/footer.php'); ?>
    </div>
</div>
</body>

</html>