<?php
$currentPage = basename($_SERVER['PHP_SELF']);

// Jika diperlukan saja 
$breadcrumbs = array(
    'Dashboard' => 'dashboard.php',
    'Home' => 'home.php',
    'About' => 'about.php',
    'Product' => 'product.php',
    'New Arrival' => 'new-arrival.php',
    'Users' => '#'
);
?>

<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <?php
        foreach ($breadcrumbs as $title => $link) {
            $isActive = ($link == '#') ? 'active' : '';

            if ($currentPage == $link) {
                echo '<li class="breadcrumb-item ' . $isActive . '">' . $title . '</li>';
            } else {
                echo '<li class="breadcrumb-item"><a href="' . $link . '">' . $title . '</a></li>';
            }
        }
        ?>
    </ol>
</nav>