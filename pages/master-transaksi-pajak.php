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
                    fetch('http://localhost:3000/pubpajak')
                        .then(response => response.json())
                        .then(data => processAndDisplayData(data))
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
                                PajakIDfk
                            } = entry;
                            nodes[PajakID] = {
                                id: PajakID,
                                title: NamaHeader,
                                name: NamaPajak,
                                pid: ParentPajak,
                                ketPajak: KetPajak,
                                ppn: PajakIDfk.length > 0 ? PajakIDfk[0].Ppn : '-',
                                pasal23: PajakIDfk.length > 0 ? PajakIDfk[0].Pasal23 : '-',
                                pphfinal: PajakIDfk.length > 0 ? PajakIDfk[0].PphFinal : '-',
                                pajaklain: PajakIDfk.length > 0 ? PajakIDfk[0].PajakLain : '-',
                                keterangan: PajakIDfk.length > 0 ? PajakIDfk[0].Keterangan : '-'
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
                            enableDragDrop: true,
                            template: "blueTemplate",
                            nodeBinding: {
                                field_0: 'title',
                                field_1: 'name',
                            },
                            collapse: {
                                level: 1
                            },
                            editForm: {
                                readOnly: false, // Allow editing
                                titleBinding: "name",
                                saveAndCloseBtn: "Save and close",
                                cancelBtn: "Cancel",
                                generateElementsFromFields: true,
                                focusBinding: null,
                                fields: [{
                                    name: 'title',
                                    type: 'textarea',
                                    label: 'Title',
                                },
                                {
                                    name: 'name',
                                    type: 'textarea',
                                    label: 'Name',
                                },
                                {
                                    name: 'pid',
                                    type: 'text',
                                    label: 'Parent ID',
                                },
                                {
                                    name: 'ketPajak',
                                    type: 'textarea',
                                    label: 'Ket Pajak',
                                },
                                ],
                                buttons: {
                                    edit: {
                                        icon: OrgChart.icon.edit(24, 24, "#fff"),
                                        text: "Edit",
                                        hideIfEditMode: true,
                                        hideIfDetailsMode: false,
                                    },
                                    pdf: {
                                        icon: OrgChart.icon.pdf(24, 24, "#fff"),
                                        text: "Save as PDF"
                                    },
                                },
                                elements: [],
                            },

                            tags: {
                                blue: {
                                    template: "blueTemplate"
                                }
                            },
                            nodes: Object.values(rootNode),
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