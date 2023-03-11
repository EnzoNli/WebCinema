<?php

include_once("../include/fcs_api.php");
include_once("../include/formulaire.php");
include_once("../include/res_recherche.php");
include_once("../include/base_html.php");

echo afficher_entete("../css/liste_film.css");

echo afficher_form();

//afficher_liste($tableau);
echo afficher_un_film(315162);
echo afficher_un_film(12201);
echo afficher_un_film(424775);

echo afficher_pied();
