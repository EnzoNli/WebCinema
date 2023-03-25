<?php

include_once("fcs_api.php");
include_once("db_connexion.php");
$connexion = new ConnexionDB("../database");

function boolFilmExiste($movie_key) {
    global $connexion;
    $st = $connexion->getDB()->prepare('SELECT COUNT(*) FROM Film WHERE api_movie_id = ? ;');
    $st->execute(array($movie_key));
    if ($st->fetchColumn() != 0)
        return true;
    return false;
}

function getMoyenne($movie_key) {
    global $connexion;
    $st = $connexion->getDB()->prepare('SELECT avg(note) as moyenne FROM Noter WHERE api_movie_id = ? ;');
    $st->execute(array($movie_key));
    return $st->fetch(PDO::FETCH_ASSOC)['moyenne'];
}

function getCommentaires($movie_key) {
    global $connexion;
    $st = $connexion->getDB()->prepare('SELECT login_, note, commentaire FROM Noter WHERE api_movie_id = ? ;');
    $st->execute(array($movie_key));
    return $st->fetchAll(PDO::FETCH_ASSOC);
}

function filtrer_trier($trier, $debut, $fin, $titre, $genre, $acteur, $noteSup, $noteInf) {
    global $connexion;

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
                WHERE api_genre_id = :genre";
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
    return $st->fetchAll(PDO::FETCH_ASSOC);
}
