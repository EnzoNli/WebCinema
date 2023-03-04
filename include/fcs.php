<?php

require_once("db_connexion.php");

$connexion = new ConnexionDB("../database");

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

// verifier que l'annee correspondant à debut <= annee fin
// sinon ??

// dans un autre fichier avec help pour afficher html

function afficher_form() {
    $chaine = '<form id="form" action="????.php" method="post">
    ';
    $chaine .= afficher_select_trier();
    $chaine .= '<input type="text" name="titre" id="titre" placeholder="Titre de film">
    ';
    $chaine .= afficher_select_annee("debut");
    $chaine .= afficher_select_annee("fin");
    $chaine .= afficher_select_genre();
    $chaine .= '<input type="text" name="acteur" id="acteur" placeholder="Acteur">
    ';
    $chaine .= '<br>
                <input type="submit" name="submit" value="Rechercher">
                </form>
                ';
    return $chaine;
}

function afficher_select_trier() {
    $chaine = '<select name="trier" id="trier" title="Trier par">
    ';
    $chaine .= '<option value="sans" selected> -- Trier par -- </option>
    ';
    $chaine .= '<option value="+populaire">Les plus populaires</option>
    ';
    $chaine .= '<option value="-populaire">Les moins populaires</option>
    ';
    $chaine .= '<option value="+notes">Les mieux notés</option>
    ';
    $chaine .= '<option value="-notes">Les moins bien notés</option>
    ';
    $chaine .= '<option value="+date">Les plus récents</option>
    ';
    $chaine .= '<option value="-date">Les moins récents</option>
    ';
    $chaine .= '<option value="+titre">Ordre alphabétique du titre</option>
    ';
    $chaine .= '</select>
    ';
    return $chaine;
}

function afficher_select_genre() {
    global $connexion;
    $chaine = '<select name="genre" id="genre" title="Sélectionner un genre">
    ';
    $chaine .= '<option value="sans" selected> -- Genre -- </option>
    ';
    $sql = 'SELECT nom_genre FROM Genre ORDER BY nom_genre';
    $result = $connexion->getDB()->query($sql);
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $chaine .= '<option value="';
        $chaine .= $row['nom_genre'];
        $chaine .= '">';
        $chaine .= $row['nom_genre'];
        $chaine .= '</option>
        ';
    }
    $chaine .= '</select>
    ';
    return $chaine;
}

function afficher_select_annee($marqueur) { // réutiliser avec "debut" et "fin" (entre ... et ...)
    // de 1895:défaut à 2023:défaut
    $an = 2023;
    $chaine = '<select name="' . $marqueur . '" id="' . $marqueur . '" title="Trier par">
    ';
    while ($an > 1894) {
        $chaine .= '<option value="' . $an . '">' . $an . '</option>
        ';
        $an -= 1;
    }
    $chaine .= '</select>
    ';
    return $chaine;
}

echo afficher_form();
