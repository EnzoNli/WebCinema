<?php
include_once("include/nav.php");
include_once("include/base_html.php");
$nav = new Navbar("");

echo afficher_entete("css/index.css");
?>
<header>

    <?php $nav->afficheNavbar(); ?>
</header>
<main>
    <?php include_once("include/slider.php"); ?>
</main>

<?php afficher_pied() ?>