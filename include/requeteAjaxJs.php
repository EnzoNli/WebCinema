<?php

include_once("fcs_api.php");
include_once("fcs_bd.php");
include_once("res_recherche.php");

$renvoi = "";

switch ($_POST["functionname"]) {
    case 'getRecherche':
        $chemin_film = ($_POST['arguments'][1] == "pages") ? ("film.php?id_movie=") : ("pages/film.php?id_movie=");
        $resp = json_decode(getRecherche($_POST['arguments'][0]), true);
        if (empty($resp['results'])) {
            echo $renvoi;
        } else {
            foreach ($resp['results'] as $movie) {
                $renvoi .= "<a href=\"" . $chemin_film . $movie['id'] . "\">";
                $renvoi .= "<div class=\"affiche-res\">";
                $renvoi .= "<img class=\"image-res\" src=\"" . getCheminVersAfficheOuBackdrop(1, $movie['poster_path'], $chemin_film) . "\" alt=\"\">";
                $renvoi .= "<h2 class=\"titre-res\">" . $movie['title'] . "</h2>";
                $renvoi .= "</div></a>";
            }
            echo $renvoi;
        }
        break;
    case 'noteFilm':
        noter_un_film($_POST['arguments'][0], $_POST['arguments'][1], $_POST['arguments'][2], $_POST['arguments'][3]);
        echo "Le commentaire a bien été pris en compte";
        break;
    case 'rechercheDB':
        echo afficher_liste(filtrer_trier($_POST['arguments'][0], $_POST['arguments'][1], $_POST['arguments'][2], $_POST['arguments'][3],
        $_POST['arguments'][4], $_POST['arguments'][5], $_POST['arguments'][6], $_POST['arguments'][7]));
        break;
}
