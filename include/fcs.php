<?php

require_once("db_connexion.php");

$connexion = new ConnexionDB(".");

// 1 filtrer

// 2 trier

// % pour pattern matching 

// comment pouvoir réutiliser pour tous les filtres? select exists as ?
function filter_titre($pm) { // pattern matching
    global $connexion;
    $sql = 'SELECT api_movie_id
            FROM Film
            WHERE titre LIKE \'%:pm%\''; // ESCAPE à ajouter
    $st = $connexion->getDB()->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $st->execute(array(':pm' => $pm));
    return $st->fetchAll();
}

function filtrer_genre($g) {
    global $connexion;
    $sql = 'SELECT api_movie_id
            FROM Film NATURAL JOIN Genre
            WHERE nom_genre = :nom_genre';
    $st = $connexion->getDB()->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $st->execute(array(':nom_genre' => $g));
    return $st->fetchAll();
}

// debut et fin sont des années 
// -> transformer début en 1er janvier minuit 
// -> transformer fin en 31 décembre 
// comment sont rentrés les dates dans l'api ????
function filtrer_annee($debut, $fin) {
    global $connexion;
    $sql = 'SELECT api_movie_id
            FROM Film
            WHERE date_sortie >= \':debut-01-01\' AND date_sortie<=\':fin-12-31\';';
    $st = $connexion->getDB()->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $st->execute(array(':debut' => $debut, ':fin' => $fin));
    return $st->fetchAll();
}

// dans un autre fichier avec help pour afficher html
function afficher_select_trier() {
    $chaine = '<select name="trier" id="trier" title="Trier par">\n';
    $chaine .= '<option value="+populaire">Les plus populaires</option>\n';
    $chaine .= '<option value="-populaire">Les moins populaires</option>\n';
    $chaine .= '<option value="+notes">Les mieux notés</option>\n';
    $chaine .= '<option value="-notes">Les moins bien notés</option>\n';
    $chaine .= '<option value="+date">Les plus récents</option>\n';
    $chaine .= '<option value="-date">Les moins récents</option>\n';
    $chaine .= '<option value="+titre">Ordre alphabétique du titre</option>\n';
    $chaine .= '</select>\n';
    return $chaine;
}

function afficher_select_genre() {
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
    return $chaine;
}

function afficher_select_annee($marqueur) { // réutiliser avec "debut" et "fin" (entre ... et ...)
}
