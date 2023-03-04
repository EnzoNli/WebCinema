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
                $renvoi .= $movie['title'];
                $renvoi .= "</a>";
            }
            echo $renvoi;
        }
}   