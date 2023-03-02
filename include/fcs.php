<?php

require_once("db_connexion.php");

$connexion = new ConnexionDB();

function select_genre() {
    global $connexion;
    $chaine = '<select name="genre" id="genre" title="Sélectionner un genre">\n';
    $sql = 'SELECT nom_genre FROM Genre ORDER BY nom_genre';
    $result = $connexion->getDB()->query($sql);
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $chaine .= '<option value="';
        $chaine .= $row['nom_genre'];
        $chaine .= '">';
        $chaine .= $row['nom_genre'];
        $chaine .= '</option>\n';
    }
    $chaine .= '</select>\n';
}

select_genre();
