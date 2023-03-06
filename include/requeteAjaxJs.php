<?php

include_once("fcs_api.php");

$renvoi = "";

switch($_POST["functionname"]){ 
    case 'getRecherche': 
        $resp = json_decode(getRecherche($_POST['arguments'][0]), true);
        if(empty($resp['results'])){
            echo $renvoi;
        }else{
            foreach($resp['results'] as $movie){
                $renvoi .= "<a href=\"#\">";
                $renvoi .= "<div class=\"affiche-res\">";
                $renvoi .= "<img class=\"image-res\" src=\"" . getCheminVersAffiche(1, $movie['poster_path']) . "\" alt=\"\">";
                $renvoi .= "<h2 class=\"titre-res\">" . $movie['title'] . "</h2>";
                $renvoi .= "</div></a>";
            }
            echo $renvoi;
        }
}   