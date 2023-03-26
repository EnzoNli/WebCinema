<?php

include_once("../include/disallowGuest.php");
include_once("../include/fcs_api.php");
include_once("../include/formulaire.php");
include_once("../include/res_recherche.php");
include_once("../include/base_html.php");
include_once("../include/base_html.php");
include_once("../include/nav.php");
$nav = new Navbar("pages");

afficher_entete("../css/liste_film.css");

$nav->afficheNavbar(); ?>

<main>
    <div id="formDB">
        <h1>Recherche API</h1>
        <?php echo afficher_form_api(); ?>
    </div>
    <div id="res"></div>
    <script src="../js/recherche_api.js"></script>
</main>


<?php afficher_pied() ?>