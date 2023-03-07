<?php

require_once("db_connexion.php");

$connexion = new ConnexionDB("../database");

function noter_un_film() {
    global $connexion;

    $movie_key = 34;
    $titre_film = "Kaloo dans l'espace";
    $date_sortie = '2021-12-01';
    $login_ = "Zoze";
    $note = 8;
    $commentaire = 'Olak a trouvé ça trop beau !';
    $genres = array(array("id" => 333, "nom" => "OlakEtKlou"), array("id" => 404, "nom" => "espace"));
    $acteurs = array(array("id" => 626, "nom" => "Olak le dino"), array("id" => 404, "nom" => "Kaloo le klou"));

    try {
        $connexion->getDB()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connexion->getDB()->beginTransaction();

        $sql = 'INSERT INTO Film (api_movie_id, titre, date_sortie) 
                SELECT :movie_key, :titre_film, date(:date_sortie)
                WHERE NOT EXISTS (SELECT 1 FROM Film WHERE api_movie_id = :movie_key );
                ';

        $st = $connexion->getDB()->prepare($sql);
        $st->bindParam(':movie_key', $movie_key, PDO::PARAM_INT);
        $st->bindParam(':titre_film', $titre_film, PDO::PARAM_STR);
        $st->bindParam(':date_sortie', $date_sortie, PDO::PARAM_STR, 10);
        $st->execute();

        foreach ($genres as $key => $value) {
            $sql = 'INSERT INTO Genre (api_genre_id, nom_genre) 
                    SELECT :genre_key' . $key . ', :nom_genre' . $key . '
                    WHERE NOT EXISTS (SELECT 1 FROM Genre WHERE api_genre_id = :genre_key' . $key . ');';

            $st = $connexion->getDB()->prepare($sql);
            $st->bindParam(':genre_key' . $key,  $value['id'], PDO::PARAM_INT);
            $st->bindParam(':nom_genre' . $key, $value['nom'], PDO::PARAM_STR);
            $st->execute();

            $sql = 'INSERT INTO Appartenir (api_genre_id, api_movie_id) 
                    SELECT :genre_key' . $key . ', :movie_key
                    WHERE NOT EXISTS (SELECT 1 FROM Appartenir WHERE api_movie_id = :movie_key 
                        AND api_genre_id = :genre_key' . $key . ');';

            $st = $connexion->getDB()->prepare($sql);
            $st->bindParam(':movie_key', $movie_key, PDO::PARAM_INT);
            $st->bindParam(':genre_key' . $key,  $value['id'], PDO::PARAM_INT);
            $st->execute();
        }

        foreach ($acteurs as $key => $value) {
            $sql = 'INSERT INTO Acteur (api_acteur_id, nom_acteur) 
                    SELECT :acteur_key' . $key . ', :nom_acteur' . $key . '
                    WHERE NOT EXISTS (SELECT 1 FROM Acteur WHERE api_acteur_id = :acteur_key' . $key . ');';

            $st = $connexion->getDB()->prepare($sql);
            $st->bindParam(':acteur_key' . $key, $value['id'], PDO::PARAM_INT);
            $st->bindParam(':nom_acteur' . $key, $value['nom'], PDO::PARAM_STR);
            $st->execute();

            $sql = 'INSERT INTO Jouer (api_acteur_id, api_movie_id) 
                    SELECT :acteur_key' . $key . ', :movie_key
                    WHERE NOT EXISTS (SELECT 1 FROM Jouer WHERE api_movie_id = :movie_key
                        AND api_acteur_id = :acteur_key' . $key . ');';

            $st = $connexion->getDB()->prepare($sql);
            $st->bindParam(':movie_key', $movie_key, PDO::PARAM_INT);
            $st->bindParam(':acteur_key' . $key, $value['id'], PDO::PARAM_INT);
            $st->execute();
        }

        $sql = 'INSERT INTO Noter (login_, api_movie_id, note, commentaire) 
                SELECT :login_ , :movie_key , :note , :commentaire
                WHERE NOT EXISTS (SELECT 1 FROM Noter WHERE api_movie_id = :movie_key AND login_ = :login_ );
                ';

        $st = $connexion->getDB()->prepare($sql);
        $st->bindParam(':movie_key', $movie_key, PDO::PARAM_INT);
        $st->bindParam(':login_', $login_, PDO::PARAM_STR);
        $st->bindParam(':note', $note, PDO::PARAM_INT);
        $st->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
        $st->execute();

        $connexion->getDB()->commit();
    } catch (Exception $e) {
        $connexion->getDB()->rollBack();
        echo "Failed: " . $e->getMessage() . 'At line ' . $e->getLine();
    }
}

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
    return $st->fetch(PDO::FETCH_ASSOC);
}

function getCommentaires($movie_key) {
    global $connexion;
    $st = $connexion->getDB()->prepare('SELECT login_, note, commentaire FROM Noter WHERE api_movie_id = ? ;');
    $st->execute(array($movie_key));
    return $st->fetch(PDO::FETCH_ASSOC);
}

var_dump(getMoyenne(34));
var_dump(getCommentaires(34));
