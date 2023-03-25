<?php

include_once("fcs_api.php");
include_once("fcs_bd.php");
include_once("res_recherche.php");
$connexion = new ConnexionDB("../database");

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
        $connexion->noter_un_film($_POST['arguments'][0], $_POST['arguments'][1], $_POST['arguments'][2], htmlentities($_POST['arguments'][3]));
        echo "Le commentaire a bien été pris en compte";
        break;
    case 'rechercheDB':
        echo afficher_liste_assoc(filtrer_trier($_POST['arguments'][0], $_POST['arguments'][1], $_POST['arguments'][2], $_POST['arguments'][3],
        $_POST['arguments'][4], $_POST['arguments'][5], $_POST['arguments'][6], $_POST['arguments'][7]));
        break;
    
    case 'rechercheAvanceeAPI':
        if(empty($_POST['arguments'])){
            echo afficher_liste(json_decode(getTopRatedMovies(), true)['results'], true);
            break;
        }

        $params = [
            'language' => 'fr-FR'
        ];

        if(!empty($_POST['arguments'][0])) {
            $params['sort_by'] = $_POST['arguments'][0];
        }
        if(!empty($_POST['arguments'][1])) {
            $tabKeywords = explode(' ', $_POST['arguments'][1]);
            $tabIdKeywords = array();
            foreach($tabKeywords as $keyword){
                $res = json_decode(getFirstKeyword($keyword), true);
                if(!empty($res['results'])){
                    array_push($tabIdKeywords, $res['results'][0]['id']);
                }
            }
            if(!empty($tabIdKeywords)){
                $params['with_keywords'] = implode(',', $tabIdKeywords);
            }else{
                break;
            }
        }
        if(!empty($_POST['arguments'][2])) {
            $params['with_genres'] = $_POST['arguments'][2];
        }
        
        if($_POST['arguments'][3] != "no") {
            $params['primary_release_date.gte'] = $_POST['arguments'][3];
        }

        if($_POST['arguments'][4] != "no") {
            $params['primary_release_date.lte'] = $_POST['arguments'][4];
        }

        

        echo afficher_liste(json_decode(getDiscover(http_build_query($params)), true)['results'], true);
        break;
}
