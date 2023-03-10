<?php

require_once("fcs_pour_page_film.php");
require_once("db_connexion.php");
$connexion = new ConnexionDB("../database");

// verif debut < fin
// note inf < sup

function afficher_form() {
    $chaine = '<form id="form" action="" method="post">
    ';
    $chaine .= afficher_api_ou_db();
    $chaine .= afficher_select_trier();
    $chaine .= '<input type="text" name="titre" id="titre" placeholder="Titre de film">
    ';
    $chaine .= afficher_select_annee("debut");
    $chaine .= afficher_select_annee("fin");
    $chaine .= afficher_select_genre();
    $chaine .= '<input type="text" name="acteur" id="acteur" placeholder="Acteur">
    ';
    $chaine .= afficher_select_note("inf");
    $chaine .= afficher_select_note("sup");
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

function afficher_select_annee($marqueur) {
    $an = 2023;
    $chaine = '<select name="' . $marqueur . '" id="' . $marqueur . '" title="Trier par">
    ';
    $chaine .= '<option value="no"> -- </option>
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

function afficher_select_note($marqueur) {
    $n = 5;
    $chaine = '<select name="' . $marqueur . '" id="' . $marqueur . '" title="Trier par">
    ';
    $chaine .= '<option value="no"> -- </option>
    ';
    while ($n >= 0) {
        $chaine .= '<option value="' . $n . '">' . $n . '</option>
    ';
        $n -= 1;
    }
    $chaine .= '</select>
    ';
    return $chaine;
}

function afficher_api_ou_db() {
    $ch = '<input type="radio" id="sqlite" name="db" value="sqlite" checked>
    ';
    $ch .= '<label for="sqlite">La base de données des Zous</label>
    ';
    $ch .= '<input type="radio" id="api" name="db" value="api">
    ';
    $ch .= '<label for="api">The Movie DataBase</label>
    ';
    return $ch;
}

function filtrer_trier() {
    global $connexion;

    $trier = "+date";
    $debut = 'no';
    $fin = 'no';
    $titre = '';
    $genre = 'sans';
    $acteur = '';
    $noteSup = '0';
    $noteInf = '1';

    $prep_debut = $debut . '-01-01';
    $prep_fin = $fin . '-12-31';
    $prep_titre = "%$titre%";
    $prep_acteur = "%$acteur%";

    $sql = 'SELECT api_movie_id
            FROM Film ';

    switch ($trier) {
        case '+notes':
        case '-notes':
            $sql .= 'NATURAL JOIN NoteMoyenne ';
            break;
        case '+populaire':
        case '-populaire':
            $sql .= 'NATURAL JOIN NbNotes ';
            break;
    }

    $sql .= 'WHERE api_movie_id IN ('; // tri

    $sql .= 'SELECT api_movie_id
            FROM Film';

    if (strcmp('', $titre)) { // titre 
        $sql .= ' INTERSECT ';
        $sql .= 'SELECT api_movie_id
                FROM Film
                WHERE titre LIKE :titre'; // ESCAPE à ajouter 
    }
    if (strcmp('sans', $genre)) { // genre 
        $sql .= " INTERSECT ";
        $sql .= "SELECT api_movie_id
                FROM Film NATURAL JOIN Appartenir NATURAL JOIN Genre
                WHERE nom_genre = :genre";
    }
    if (strcmp('', $acteur)) { // acteur 
        $sql .= ' INTERSECT ';
        $sql .= 'SELECT api_movie_id
                FROM Film NATURAL JOIN Jouer NATURAL JOIN Acteur
                WHERE nom_acteur LIKE :acteur'; // ESCAPE à ajouter 
    }
    if (strcmp('no', $debut)) {
        $sql .= ' INTERSECT ';
        $sql .= 'SELECT api_movie_id
                FROM Film
                WHERE date_sortie >= date(:debut)';
    }
    if (strcmp('no', $fin)) {
        $sql .= ' INTERSECT ';
        $sql .= 'SELECT api_movie_id
                FROM Film
                WHERE date_sortie<= date(:fin)';
    }
    if (strcmp('no', $noteInf)) {
        $sql .= ' INTERSECT ';
        $sql .= 'SELECT api_movie_id
                FROM NoteMoyenne
                WHERE moyenne >= :inf';
    }
    if (strcmp('no', $noteSup)) {
        $sql .= ' INTERSECT ';
        $sql .= 'SELECT api_movie_id
                FROM NoteMoyenne
                WHERE moyenne <= :sup';
    }

    switch ($trier) {
        case 'sans':
        case '+date':
            $sql .= ') ORDER BY date_sortie desc'; // ok 
            break;
        case '-date':
            $sql .= ') ORDER BY date_sortie asc'; // ok
            break;
        case 'titre':
            $sql .= ') ORDER BY titre asc'; // ok 
            break;
        case '+populaire':
            $sql .= ') ORDER BY nb desc';
            break;
        case '-populaire':
            $sql .= ') ORDER BY nb asc';
            break;
        case '+notes':
            $sql .= ') ORDER BY moyenne desc'; // ?? 
            break;
        case '-notes':
            $sql .= ') ORDER BY moyenne asc'; // ?? 
            break;
    }

    $st = $connexion->getDB()->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));

    if (strcmp("no", $debut))
        $st->bindParam(':debut', $prep_debut, PDO::PARAM_STR, 10);

    if (strcmp("no", $fin))
        $st->bindParam(':fin', $prep_fin, PDO::PARAM_STR, 10);

    if (strcmp("", $titre))
        $st->bindParam(':titre', $prep_titre, PDO::PARAM_STR);

    if (strcmp("sans", $genre))
        $st->bindParam(':genre', $genre, PDO::PARAM_STR);

    if (strcmp("", $acteur))
        $st->bindParam(':acteur', $prep_acteur, PDO::PARAM_STR);

    if (strcmp('no', $noteInf))
        $st->bindParam(':inf', $noteInf, PDO::PARAM_INT);

    if (strcmp('no', $noteSup))
        $st->bindParam(':sup', $noteSup, PDO::PARAM_INT);

    $st->execute();
    return $st->fetch(PDO::FETCH_ASSOC);
}
