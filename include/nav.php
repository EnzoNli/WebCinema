<?php

require_once("db_connexion.php");

class Navbar{

    private $connexion;
    private $username;
    private $chemin_actu;
    function __construct($chemin_actu)
    {
        $this->chemin_actu = $chemin_actu;

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        if (isset($_GET['deco'])) {
            session_destroy();
            if($chemin_actu != "pages"){
                header("Location: ./index.php");
            }else{
                header("Location: ../index.php");
            }
            exit;
        }

        if($chemin_actu == "pages"){
            $this->connexion = new ConnexionDB("../database");
        }else{
            $this->connexion = new ConnexionDB("database");
        }
        
        if ($this->connexion->userIsConnected($_SESSION)) {
            $this->username = $_SESSION['username'];
        }
    }

    function afficheNavbar(){
        $remplacement1 = ($this->chemin_actu == "pages") ? ("../") : ("");
        $remplacement2 = ($this->chemin_actu == "pages") ? ("") : ("pages/");
        echo "
        <link rel=\"stylesheet\" href=\"" . $remplacement1 . "css/nav.css\">
        <link rel=\"stylesheet\" href=\"https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css\" />
        <nav>
            <div class=\"logo\">
                Le Cinéma des Zous
            </div>";
        if(!empty($this->username)){
            echo "
            <div class=\"nav-items\">
                <li><a href=\"" . $remplacement1 . "index.php\">Accueil</a></li>
                <li><a href=\"#\">Films notés</a></li>
                <li><a href=\"" . $remplacement2 . "recherche_avancee.php\">Recherche avancée</a></li>
            </div>

            <form action=\"#\">
                <div id=\"res_recherche\">
                    <input type=\"search\" class=\"search-data\" id=\"recherche\" placeholder=\"Rechercher un film dans TMDB\" autocomplete=\"off\" required>
                    <div class=\"liste_recherche\">
                    </div>
                </div>
            </form>

            <div class=\"dropdown-perso\">
                <button class=\"dropbtn-perso\">"
                    . $this->username . "<i class=\"fa fa-caret-down\"></i>
                </button>
                <div class=\"dropdown-content-perso\">
                    <a href=\"#\">Profil</a>
                    <a href=\"?deco=1\" id=\"deco\">Se déconnecter</a>
                </div>
            </div>
            ";
        }else{
            echo "
            <div class=\"nav-items\">
                <li style=\"color: white\">Connectez vous pour accèder aux fonctionnalités du site</li>
            </div>
            <div class=\"dropdown-perso\">
                <button class=\"dropbtn-perso\">Invité <i class=\"fa fa-caret-down\"></i></button>
                <div class=\"dropdown-content-perso\">
                    <a href=\"" . $remplacement2 . "login.php\">Connexion</a>
                </div>
            </div>";
        }

        echo "</nav>";
        $this->activeRechercheAPI();
    }


    function activeRechercheAPI(){
        $remplacement1 = ($this->chemin_actu == "pages") ? ("../") : ("");
        echo "
        <script>
            $(document).ready(function(){
                $('#recherche').keyup(function(){
                    jQuery.ajax({
                        type: \"POST\",
                        url: \"" . $remplacement1 . "include/requeteAjaxJs.php\",
                        data: {functionname: 'getRecherche', arguments: [$(this).val(), \"" . $this->chemin_actu ."\", \"" . $this->chemin_actu ."\"]}
                    }).done(function(reponse){
                        $(\".liste_recherche\").html(reponse);
                    });
                });
            });
        </script>
        ";
    }
}
?>