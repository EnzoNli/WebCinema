<?php

require_once("db_connexion.php");

$connexion = new ConnexionDB("../database");

// 1 filtrer
// 2 trier

// verif fin < debut
// sinon ??

$trier = "sans"; // à la fin

function filtrer() {

    $debut = "2010"; // defautà check ?
    $prep_debut =  $debut . '-01-01';

    $fin = "2024"; // defaut ?
    $prep_fin = $fin . '-12-31';

    $titre = "star";
    $prep_titre = "%$titre%";
    print $prep_titre;

    $genre = "sans";

    $acteur = "";
    $prep_acteur = "%$acteur%";

    $noteSup = "10"; // val défaut
    $noteInf = "0"; // val défaut


    global $connexion;
    $sql = 'SELECT api_movie_id
            FROM Film
            WHERE date_sortie >= date(:debut) AND date_sortie<= date(:fin)'; // années

    if (strcmp("", $titre)) { // titre
        $sql .= ' INTERSECT ';
        $sql .= 'SELECT api_movie_id
                FROM Film
                WHERE titre LIKE :titre'; // ESCAPE à ajouter
    }
    if (strcmp("sans", $genre)) { // genre
        $sql .= " INTERSECT ";
        $sql .= "SELECT api_movie_id
                FROM Film NATURAL JOIN Appartenir NATURAL JOIN Genre
                WHERE nom_genre = :genre";
    }
    if (strcmp("", $acteur)) { // acteur
        $sql .= ' INTERSECT ';
        $sql .= 'SELECT api_movie_id
                FROM Film NATURAL JOIN Jouer NATURAL JOIN Acteur
                WHERE nom_acteur LIKE :acteur'; // ESCAPE à ajouter
    }

    print $sql;

    $st = $connexion->getDB()->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $st->bindParam(':debut', $prep_debut, PDO::PARAM_STR, 10);
    $st->bindParam(':fin', $prep_fin, PDO::PARAM_STR, 10);
    if (strcmp("", $titre)) {
        $st->bindValue(':titre', $prep_titre, PDO::PARAM_STR);
    }
    if (strcmp("sans", $genre)) {
        $st->bindParam(':genre', $genre, PDO::PARAM_STR);
    }
    if (strcmp("", $acteur)) {
        $st->bindParam(':acteur', $prep_acteur, PDO::PARAM_STR);
    }

    $st->execute();
    var_dump($st->fetchAll());
}








// dans un autre fichier avec help pour afficher html

function afficher_form() {
    $chaine = '<form id="form" action="" method="post">
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

filtrer();
