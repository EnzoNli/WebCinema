<?php

include_once("fcs_api.php");

switch($_POST["functionname"]){ 
    case 'getRecherche': 
        echo getRecherche($_POST['arguments'][0]);
}   