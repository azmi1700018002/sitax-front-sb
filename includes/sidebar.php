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

    <?php
    $parentItems = [];
    $childItems = [];

    foreach ($menuData as $menuItem) {
        if ($menuItem['ParentID'] === "0000") {
            $parentItems[] = $menuItem;
        } else {
            $childItems[] = $menuItem;
        }
    }

    $childItemsByParent = [];
    foreach ($childItems as $childItem) {
        if (!isset($childItemsByParent[$childItem['ParentID']])) {
            $childItemsByParent[$childItem['ParentID']] = [];
        }
        $childItemsByParent[$childItem['ParentID']][] = $childItem;
    }
    foreach ($parentItems as $parentItem) {
        if (isset($parentItem['MenuIDfk']) && is_array($parentItem['MenuIDfk'])) {
            $allowedGroupIDs = array_column($parentItem['MenuIDfk'], 'GroupID');
            if (in_array($_SESSION["GroupID"], $allowedGroupIDs)) {
                $parentMenuID = $parentItem['MenuID'];
                $hasChildItems = isset($childItemsByParent[$parentMenuID]);
                $isActiveParent = isActive($parentItem['MenuLink']);
                $expandCollapse = false;

                if ($isActiveParent) {
                    $_SESSION['activeMenuID'] = $parentMenuID;
                }

                if (isset($childItemsByParent[$parentMenuID]) && is_array($childItemsByParent[$parentMenuID])) {
                    foreach ($childItemsByParent[$parentMenuID] as $childItem) {
                        if (isActive($childItem['MenuLink'])) {
                            $expandCollapse = true;
                            $_SESSION['activeMenuID'] = $childItem['MenuID'];
                            break;
                        }
                    }
                }
                ?>
                <li class="nav-item <?php echo $isActiveParent; ?>">
                    <a class="nav-link <?php echo $hasChildItems ? 'collapsed' : ''; ?>" <?php if ($hasChildItems): ?> href="#"
                            data-toggle="collapse" data-target="#collapse<?php echo $parentMenuID; ?>"
                            aria-expanded="<?php echo ($isActiveParent || $expandCollapse) ? 'true' : 'false'; ?>"
                            aria-controls="collapse<?php echo $parentMenuID; ?>" <?php else: ?>
                            href="<?php echo $parentItem['MenuLink']; ?>" <?php endif; ?>>
                        <i class="<?php echo $parentItem['MenuIcon']; ?>"></i>
                        <span>
                            <?php echo $parentItem['MenuNama']; ?>
                        </span>
                    </a>
                    <?php if ($hasChildItems): ?>
                        <div id="collapse<?php echo $parentMenuID; ?>"
                            class="collapse <?php echo ($isActiveParent || $expandCollapse) ? 'show' : ''; ?>"
                            aria-labelledby="heading<?php echo $parentMenuID; ?>" data-parent="#accordionSidebar">
                            <div class="bg-white py-2 collapse-inner rounded">
                                <?php foreach ($childItemsByParent[$parentMenuID] as $childItem): ?>
                                    <?php
                                    $childMenuID = $childItem['MenuID']; // Mendapatkan MenuID dari childItem
                                    ?>
                                    <a class="collapse-item <?php echo isActive($childItem['MenuLink']); ?>"
                                        href="<?php echo $childItem['MenuLink']; ?>">
                                        <?php echo $childItem['MenuNama']; ?>
                                    </a>
                                    <?php
                                    if (isActive($childItem['MenuLink'])) {
                                        $_SESSION['activeMenuID'] = $childMenuID; // Mengatur activeMenuID dengan childMenuID
                                    }
                                    ?>
                                <?php endforeach; ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </li>
                <?php
            }
        }
    }
    ?>
    <!-- Sidebar Toggler (Sidebar) -->
    <!-- <div class=" text-center d-none d-md-inline">
        <button class="rounded-circle border-0" id="sidebarToggle"></button>
    </div> -->
</ul>
<!-- End of Sidebar -->