// Call the dataTables jQuery plugin
$(document).ready(function () {
  $("#dataTable").DataTable({
    fixedHeader: true, // Ini akan mengaktifkan fixed header
    scrollX: true, // Ini akan mengaktifkan horizontal scrolling
  });
});
