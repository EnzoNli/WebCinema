<?php

require_once("db_connexion.php");

$connexion = new ConnexionDB("../database");

// verif fin < debut
// sinon ??

function noter_un_film() {
    global $connexion;

    $genres = array();
    $acteurs = array();

    try {
        $connexion->getDB()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connexion->getDB()->beginTransaction();

        $sql = 'IF NOT EXISTS(SELECT api_movie_id FROM Film WHERE api_movie_id = :movie_key)
                BEGIN
                    INSERT INTO Film (api_movie_id, titre, date_sortie) 
                    VALUES (:movie_key, :titre_film, :date_sortie) 
                ';

        foreach ($genres as $key => $value) {
            $sql .= 'IF NOT EXISTS(SELECT api_genre_id FROM Genre WHERE api_genre_id = :genre_key' . $key . ')
                        BEGIN
                            INSERT INTO Genre (api_genre_id, nom_genre) 
                            VALUES (:genre_key' . $key . ', :nom_genre' . $key . ')
                        END

                    INSERT INTO Appartenir (api_genre_id, api_movie_id)
                    VALUES (:genre_key' . $key . ', :movie_key)
                    ';
        }

        foreach ($acteurs as $key => $value) {
            $sql .= 'IF NOT EXISTS(SELECT api_acteur_id FROM Acteur WHERE api_acteur_id = :acteur_key' . $key . ')
                        BEGIN
                            INSERT INTO Acteur (api_acteur_id, nom_acteur) 
                            VALUES (:acteur_key' . $key . ', :nom_acteur' . $key . ')
                        END
                    INSERT INTO Jouer (api_acteur_id, api_movie_id) 
                    VALUES (:acteur_key' . $key . ',:movie_key)
                    ';
        }

        $sql .= 'END
                IF NOT EXISTS (SELECT * FROM Noter WHERE api_movie_id = :movie_key AND login_ = :login_)
                BEGIN 
                    INSERT INTO Noter (login_, api_movie_id, note, commentaire)
                    VALUES (:login_, :movie_key, :note, :commentaire)
                END
                ';

        $st = $connexion->getDB()->prepare($sql);

        $st->bindParam(':movie_key', $movie_key, PDO::PARAM_INT);
        $st->bindParam(':titre_film', $titre_film, PDO::PARAM_STR);
        $st->bindParam(':date_sortie', $date_sortie, PDO::PARAM_STR, 10);
        $st->bindParam(':login_', $login_, PDO::PARAM_STR);
        $st->bindParam(':note', $note, PDO::PARAM_INT);
        $st->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);

        foreach ($genres as $key => $value) {
            $genre_id = $value['id'];
            $nom_genre = $value['nom'];
            $st->bindParam(':genre_key' . $key, $genre_id, PDO::PARAM_INT);
            $st->bindParam(':nom_genre' . $key, $nom_genre, PDO::PARAM_STR);
        }

        foreach ($acteurs as $key => $value) {
            $acteur_id = $value['id'];
            $nom_acteur = $value['nom'];
            $st->bindParam(':acteur_key' . $key, $acteur_id, PDO::PARAM_INT);
            $st->bindParam(':nom_acteur' . $key, $nom_acteur, PDO::PARAM_STR);
        }

        $st->execute();
        $connexion->getDB()->commit();
    } catch (Exception $e) {
        $connexion->getDB()->rollBack();
        echo "Failed: " . $e->getMessage();
    }
}





function filtrer() {
    global $connexion;

    $trier = "+date"; // à la fin
    $debut = '2010'; // defautà check ?
    $fin = '2024'; // defaut ?
    $titre = 'star';
    $genre = 'sans';
    $acteur = '';
    $noteSup = '10'; // val défaut
    $noteInf = '0'; // val défaut

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
            FROM Film
            WHERE date_sortie >= date(:debut) AND date_sortie<= date(:fin)'; // années 

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
    switch ($trier) {
        case '+date':
            $sql
                .= ') ORDER BY date_sortie desc'; // ok 
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
    echo $sql;
    $st = $connexion->getDB()->prepare($sql, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
    $st->bindParam(':debut', $prep_debut, PDO::PARAM_STR, 10);
    $st->bindParam(':fin', $prep_fin, PDO::PARAM_STR, 10);
    if (strcmp("", $titre)) {
        $st->bindParam(':titre', $prep_titre, PDO::PARAM_STR);
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
// garder la dernière valeur envoyer par defaut

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
    $chaine .= '<option value="+date" selected> -- Trier par -- </option>
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
