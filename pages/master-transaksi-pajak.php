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
                <div style="width: 100%; height: 500px" id="tree"></div>
                <script>
                    // Di bagian fetch API yang sudah ada
                    const token = "<?php echo $_SESSION['token']; ?>"; // Mengambil token dari session PHP
                    let fileOptions = []; // Definisikan variabel fileOptions di luar fungsi

                    // Menggunakan Promise.all untuk menunggu kedua permintaan fetch selesai
                    Promise.all([
                        fetch('http://localhost:3000/pubpajak').then(response => response.json()),
                        fetch('http://localhost:3000/auth/file', {
                            headers: {
                                'Content-Type': 'application/json',
                                'Authorization': `Bearer ${token}`
                            }
                        }).then(response => response.json())
                    ])
                        .then(([pubpajakData, fileData]) => {
                            fileOptions = fileData.data.map(file => ({
                                value: file.id,
                                text: file.FileJudul
                            }));

                            processAndDisplayData(pubpajakData);
                        })
                        .catch(error => console.error('Error fetching data:', error));

                    function processAndDisplayData(apiData) {
                        const nodes = {};
                        const rootNode = {};

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
                                // PajakIDfk
                            } = entry;
                            nodes[PajakID] = {
                                id: PajakID,
                                NamaHeader: NamaHeader,
                                NamaPajak: NamaPajak,
                                pid: ParentPajak,
                                KetPajak: KetPajak,
                                StsPajak: StsPajak,
                                FileID: FileID,
                                // ppn: PajakIDfk.length > 0 ? PajakIDfk[0].Ppn : '-',
                                // pasal23: PajakIDfk.length > 0 ? PajakIDfk[0].Pasal23 : '-',
                                // pphfinal: PajakIDfk.length > 0 ? PajakIDfk[0].PphFinal : '-',
                                // pajaklain: PajakIDfk.length > 0 ? PajakIDfk[0].PajakLain : '-',
                                // keterangan: PajakIDfk.length > 0 ? PajakIDfk[0].Keterangan : '-'
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
                                generateElementsFromFields: false,
                                focusBinding: null,
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
                                // {
                                //     type: 'select',
                                //     label: 'FileID',
                                //     binding: 'FileID',
                                //     options: Object.values(nodes).map(node => ({
                                //         value: node.id,
                                //         text: node.FileID,
                                //     }))
                                // },
                                {
                                    type: 'select',
                                    label: 'FileID',
                                    binding: 'FileID',
                                    options: fileOptions,
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

                        // chart.on('init', function(sender) {
                        //     sender.editUI.show(1, true);

                        // });

                        chart.editUI.on('save', function (sender, args) {
                            const dataToSave = args.data; // Data yang akan disimpan
                            const pajak_id = dataToSave.id; // Mengambil PajakID dari data yang akan disimpan
                            dataToSave.StsPajak = parseInt(dataToSave.StsPajak); // Mengubah nilai menjadi integer
                            dataToSave.ParentPajak = parseInt(dataToSave.pid); // Mengubah nilai menjadi integer

                            // const token =
                            //     "eyJhbGciOiJIUzI1NiIsInR5cCI6IkpXVCJ9.eyJQcm9maWxlUGljdHVyZSI6ImRlZmF1bHRfcHJvZmlsZS5wbmciLCJVc2VybmFtZSI6IkFkbWluIiwiZXhwIjoxNjkyMTU0MTE1LCJpZCI6IkFkbWluIn0.U_4VBv6mOg2Pl5OkLqy9ZgyK3uJKFDMgqq055Re_GlY"; // Ganti dengan token Anda
                            const token = "<?php echo $_SESSION['token']; ?>"; // Mengambil token dari session PHP

                            fetch(`http://localhost:3000/auth/pajak/${pajak_id}`, {
                                method: 'PUT', // Metode HTTP untuk menyimpan data
                                headers: {
                                    'Content-Type': 'application/json',
                                    'Authorization': `Bearer ${token}` // Menambahkan header Authorization dengan bearer token
                                },
                                body: JSON.stringify(dataToSave) // Mengubah data menjadi JSON string
                            })
                                .then(response => response.json())
                                .then(savedData => {
                                    console.log('Data saved:', savedData);
                                    // Lakukan tindakan lain jika diperlukan setelah penyimpanan berhasil
                                })
                                .catch(error => {
                                    console.error('Error saving data:', error);
                                    // Lakukan tindakan lain jika penyimpanan gagal
                                });
                        });
                    }
                </script>
            </div>
        </div>
        <?php include '../includes/footer.php'; ?>
    </div>
</div>
</body>

</html>