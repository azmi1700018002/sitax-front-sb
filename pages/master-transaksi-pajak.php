<h3>Master Transaksi Pajak</h3>

<div id="chart" class="orgChart"></div>

<link rel="stylesheet" href="jquery.OrgChart.css" />
<link href="prettify.css" type="text/css" rel="stylesheet" />

<script type="text/javascript" src="prettify.js"></script>

<!-- jQuery includes -->
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.8.16/jquery-ui.min.js">
</script>

<script src="jquery.jOrgChart.js"></script>

<script>
    jQuery(document).ready(function () {
        // Fetch data from the API
        $.ajax({
            url: 'http://localhost:3000/pubpajak',
            method: 'GET',
            dataType: 'json',
            success: function (response) {
                if (!Array.isArray(response.data)) {
                    console.error('Data returned from the API is not an array:', response.data);
                    return;
                }

                var data = response.data;

                // Build the complete hierarchy
                var hierarchy = buildHierarchy(data);

                // Generate the jOrgChart
                generateOrgChart(hierarchy);
            },
            error: function (error) {
                console.error('Error fetching data from the API:', error);
            }
        });

        function buildHierarchy(data) {
            // Helper function to group nodes by ParentPajak
            function groupByParent(nodes, parentPajak) {
                return nodes.filter(function (item) {
                    return item.ParentPajak === parentPajak;
                });
            }

            // Recursive function to build the hierarchy
            function buildNodeHierarchy(parentNode) {
                var parentId = parentNode.PajakID;
                parentNode.children = groupByParent(data, parentId);
                parentNode.children.forEach(buildNodeHierarchy);
            }

            // Find root nodes with ParentPajak = 0 (top-level nodes)
            var rootNodes = groupByParent(data, 0);

            // Build hierarchy for each root node
            rootNodes.forEach(buildNodeHierarchy);

            return rootNodes;
        }

        function generateOrgChart(nodes) {
            // Function to generate the jOrgChart from the hierarchy
            var $org = $("<ul>").attr("id", "org").css("display", "none");
            nodes.forEach(function (node) {
                buildOrgChart(node, $org);
            });
            $("#chart").html($org);
            $("#org").jOrgChart({
                chartElement: '#chart',
                dragAndDrop: true
            });
        }

        function buildOrgChart(node, $parent) {
            // Recursive function to build the jOrgChart nodes
            var $li = $("<li>").text(node.NamaPajak).append($("<ul>"));
            $parent.append($li);
            node.children.forEach(function (child) {
                buildOrgChart(child, $li.children("ul"));
            });
        }
    });
</script>