<?php
require_once "../config/server.php";

$url = $baseUrl . "menu";
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

// Check if the API request was successful and data is available
if (isset($data['data']) && is_array($data['data'])) {
    $menuData = $data['data'];
} else {
    // Handle the error or set $menuData to an empty array if the API request failed
    $menuData = [];
}

// Urutkan $menuData berdasarkan NoUrut
usort($menuData, function ($a, $b) {
    return $a['NoUrut'] - $b['NoUrut'];
});

// Fungsi untuk menambahkan kelas "active" pada menu-item yang aktif
function isActive($link)
{
    return (basename($_SERVER['PHP_SELF']) == $link) ? 'active' : '';
}
?>

<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">

    <!-- Sidebar - Brand -->
    <a class="sidebar-brand d-flex align-items-center justify-content-center" href="index.html">
        <div class="sidebar-brand-icon rotate-n-15">
            <img src="../assets/img/logo.png" height="25" alt="" loading="lazy" /></img>
        </div>
        <div class="sidebar-brand-text mx-3">Sitax</div>
    </a>

    <!-- Heading -->
    <div class="sidebar-heading">Interface</div>

    <?php foreach ($menuData as $menuItem): ?>
    <?php
        // Check if the menu item has 'AllowedGroupIDs' information and if it is allowed for the user's GroupID
        if (isset($menuItem['MenuIDfk']) && is_array($menuItem['MenuIDfk'])) {
            $allowedGroupIDs = array_column($menuItem['MenuIDfk'], 'GroupID');
            if (in_array($_SESSION["GroupID"], $allowedGroupIDs)):
                $menuID = $menuItem['MenuID'];
                ?>
    <!-- Nav Item - Pages Collapse Menu -->
    <li class="nav-item <?php echo isActive($menuItem['MenuLink']); ?>">
        <form method="post" action="<?php echo $menuItem['MenuLink']; ?>">
            <input type="hidden" name="menuID" value="<?php echo $menuID; ?>">
            <a class="nav-link" href="javascript:void(0);" onclick="this.parentNode.submit(); return false;">
                <i class="<?php echo $menuItem['MenuIcon']; ?>"></i>
                <span>
                    <?php echo $menuItem['MenuNama']; ?>
                </span>
            </a>
            <input type="submit" style="display:none;">
        </form>
    </li>

    <?php endif; ?>
    <?php } ?>
    <?php endforeach; ?>
    <!-- Sidebar Toggler (Sidebar) -->
    <div class=" text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div>
</ul>
<!-- End of Sidebar -->