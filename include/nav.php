<?php

require_once("db_connexion.php");

if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

$connexion = new ConnexionDB("database");


if (isset($_GET['deco'])) {
    session_destroy();
    header('Location: ./index.php');
    exit;
}

if ($connexion->userIsConnected($_SESSION)) {
    $username = $_SESSION['username'];
}
?>


<link rel="stylesheet" href="css/nav.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" />


<nav>
    <div class="logo">
        Le Cinéma des Zous
    </div>
    <?php if (isset($username)) { ?>
        <div class="nav-items">
            <li><a href="/index.php">Accueil</a></li>
            <li><a href="#">Films notés</a></li>
            <li><a href="pages/recherche_avancee.php">Recherche avancée</a></li>
        </div>




        <form action="#">
            <div id="res_recherche">
                <input type="search" class="search-data" id="recherche" placeholder="Rechercher un film dans TMDB" autocomplete="off" required>
                <div class="liste_recherche">
                </div>
            </div>
        </form>






        <div class="dropdown-perso">
            <button class="dropbtn-perso">
                <?php echo $username; ?> <i class="fa fa-caret-down"></i>
            </button>
            <div class="dropdown-content-perso">
                <a href="#">Profil</a>
                <a href="?deco=1" id="deco">Se déconnecter</a>
            </div>
        </div>
    <?php } else { ?>
        <div class="nav-items">
            <li style="color: white">Connectez vous pour accèder aux fonctionnalités du site</li>
        </div>
        <div class="dropdown-perso">
            <button class="dropbtn-perso">Invité <i class="fa fa-caret-down"></i></button>
            <div class="dropdown-content-perso">
                <a href="pages/login.php">Connexion</a>
            </div>
        </div>
    <?php } ?>
</nav>


<script>
    $(document).ready(function(){
        $('#recherche').keyup(function(){
            jQuery.ajax({
                type: "POST",
                url: "include/requeteAjaxJs.php",
                data: {functionname: 'getRecherche', arguments: [$(this).val()]}
            }).done(function(reponse){
                $(".liste_recherche").html(reponse);
            });
        });
    });
</script>