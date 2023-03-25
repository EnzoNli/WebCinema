<?php

class ConnexionDB
{
    private $db;

    function __construct($chemin_vers_db)
    {
        $file = $chemin_vers_db . "/cinema.db";
        $file2 = $chemin_vers_db . "/cinema.sql";
        if (!file_exists($file)) {
            try {
                $db = new PDO("sqlite:" . $file);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $db->exec(file_get_contents($file2));
                $st = $db->prepare('INSERT INTO Utilisateur VALUES (?, ?)');
                $st->execute(array("TotorLeCastor", password_hash("CastorLeTotor", PASSWORD_DEFAULT)));
                $st->execute(array("Enzo", password_hash("a1b2c3", PASSWORD_DEFAULT)));
                $st->execute(array("Zoze", password_hash("abricot", PASSWORD_DEFAULT)));
                $this->db = $db;

                $this->noter_un_film("TotorLeCastor", 315162, 5, "J'ai passé un excellent moment
Les personnages sont haut en couleur et tous charismatiques. Bon choix.
L'animation est terrible surtout les combats qui sont ultra dynamiques (on sent l'influence japonaise)." );
                $this->noter_un_film("Enzo", 315162, 5, "Un film incroyablement beau et très drôle ! Cela faisait très longtemps, pour ma part, que je n'avais pas autant rigolé dans une salle de cinéma ! Juste époustouflant les graphismes et un scénario très très bien mené ! Je vous conseille vivement d'aller le voir !");
                $this->noter_un_film("Zoze", 315162, 5, "Ce film est une pure merveille. On en prend plein les yeux du début à la fin. Tout est très bien rythmé. Vous n'allez pas être déçu ! ");

                $this->noter_un_film("Enzo", 299536, 4, "Avengers : Infinity War est un incroyable spectacle qui devrait laisser tous les fans de Marvel bouche bée devant le travail effectué par Joe et Anthony Russo.");
                $this->noter_un_film("Zoze", 299536, 3, "Un spectacle visuel impeccable, mais sans surprises. ");

                $this->noter_un_film("TotorLeCastor", 80321, 3, "Pas le meilleur film de la saga, mais un très bon divertissement pour les petits et pour les grands. ");
                $this->noter_un_film("Zoze", 80321, 4, "Un véritable feu d'artifice ambulant, rempli de couleurs éclatantes, de personnages atypiques (avec une une vilaine méchamment drôle) et de scènes d'anthologie (spoiler:
), le tout servi par un rythme frénétique ne provoquant jamais l'ennui. Vous l'aurez compris : on en redemande!! ");
                $this->noter_un_film("Enzo", 80321, 2, "Invraisemblable déjà qu'ils parviennent à Monaco à la nage.
La suite ne l'est pas moins, et sans l'humour du 1 et du 2.
L'épisode de trop. ");
/*
        noter_un_film("TotorLeCastor", $movie_key, $note, $commentaire),
        noter_un_film("Zoze", $movie_key, $note, $commentaire),

        noter_un_film("Enzo", $movie_key, $note, $commentaire),
*/
                $this->noter_un_film("TotorLeCastor", 499701, 2, "Babouche est fidèle au dessin animé mais déçu de ne pas avoir assez vu Totor le castor.");
        /*
        noter_un_film("TotorLeCastor", $movie_key, $note, $commentaire),
        noter_un_film("Enzo", $movie_key, $note, $commentaire),

        noter_un_film("Zoze", $movie_key, $note, $commentaire),

        noter_un_film("TotorLeCastor", $movie_key, $note, $commentaire),
        noter_un_film("Zoze", $movie_key, $note, $commentaire)*/
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        } else {
            try {
                $db = new PDO("sqlite:" . $file);
                $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->db = $db;
            } catch (PDOException $e) {
                echo $e->getMessage();
            }
        }
    }

    function tryConnection($username, $password)
    {
        $query = "SELECT mdp FROM Utilisateur WHERE login_ = :username";
        $statement = $this->db->prepare($query, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
        $statement->execute(array(':username' => $username));
        $pwd_hash = $statement->fetchColumn();
        if (empty($pwd_hash)) {
            return false;
        }
        if (password_verify($password, $pwd_hash)) {
            return true;
        } else {
            return false;
        }
    }

    function userIsConnected($session)
    {
        if (isset($session['logged_in']) && $session['logged_in'] === true) {
            return true;
        }
        return false;
    }

    function getDB()
    {
        return $this->db;
    }

    function noter_un_film($login_, $movie_key, $note, $commentaire) {
        //global $connexion;
        $film = json_decode(getMovie($movie_key), true);
        $titre_film = $film['title'];
        $date_sortie = $film['release_date'];
        $genres = $film['genres'];
        $acteurs = getActorsName($movie_key);

        try {
            $this->getDB()->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->getDB()->beginTransaction();

            $sql = 'INSERT INTO Film (api_movie_id, titre, date_sortie) 
                    SELECT :movie_key, :titre_film, date(:date_sortie)
                    WHERE NOT EXISTS (SELECT 1 FROM Film WHERE api_movie_id = :movie_key );
                    ';

            $st = $this->getDB()->prepare($sql);
            $st->bindParam(':movie_key', $movie_key, PDO::PARAM_INT);
            $st->bindParam(':titre_film', $titre_film, PDO::PARAM_STR);
            $st->bindParam(':date_sortie', $date_sortie, PDO::PARAM_STR, 10);
            $st->execute();

            foreach ($genres as $key => $value) {
                $sql = 'INSERT INTO Genre (api_genre_id, nom_genre) 
                        SELECT :genre_key' . $key . ', :nom_genre' . $key . '
                        WHERE NOT EXISTS (SELECT 1 FROM Genre WHERE api_genre_id = :genre_key' . $key . ');';

                $st = $this->getDB()->prepare($sql);
                $st->bindParam(':genre_key' . $key,  $value['id'], PDO::PARAM_INT);
                $st->bindParam(':nom_genre' . $key, $value['name'], PDO::PARAM_STR);
                $st->execute();

                $sql = 'INSERT INTO Appartenir (api_genre_id, api_movie_id) 
                        SELECT :genre_key' . $key . ', :movie_key
                        WHERE NOT EXISTS (SELECT 1 FROM Appartenir WHERE api_movie_id = :movie_key 
                            AND api_genre_id = :genre_key' . $key . ');';

                $st = $this->getDB()->prepare($sql);
                $st->bindParam(':movie_key', $movie_key, PDO::PARAM_INT);
                $st->bindParam(':genre_key' . $key,  $value['id'], PDO::PARAM_INT);
                $st->execute();
            }

            foreach ($acteurs as $key => $value) {
                $sql = 'INSERT INTO Acteur (api_acteur_id, nom_acteur) 
                        SELECT :acteur_key' . $key . ', :nom_acteur' . $key . '
                        WHERE NOT EXISTS (SELECT 1 FROM Acteur WHERE api_acteur_id = :acteur_key' . $key . ');';

                $st = $this->getDB()->prepare($sql);
                $st->bindParam(':acteur_key' . $key, $value['id'], PDO::PARAM_INT);
                $st->bindParam(':nom_acteur' . $key, $value['name'], PDO::PARAM_STR);
                $st->execute();

                $sql = 'INSERT INTO Jouer (api_acteur_id, api_movie_id) 
                        SELECT :acteur_key' . $key . ', :movie_key
                        WHERE NOT EXISTS (SELECT 1 FROM Jouer WHERE api_movie_id = :movie_key
                            AND api_acteur_id = :acteur_key' . $key . ');';

                $st = $this->getDB()->prepare($sql);
                $st->bindParam(':movie_key', $movie_key, PDO::PARAM_INT);
                $st->bindParam(':acteur_key' . $key, $value['id'], PDO::PARAM_INT);
                $st->execute();
            }

            $sql = 'INSERT INTO Noter (login_, api_movie_id, note, commentaire) 
                    SELECT :login_ , :movie_key , :note , :commentaire
                    WHERE NOT EXISTS (SELECT 1 FROM Noter WHERE api_movie_id = :movie_key AND login_ = :login_ );
                    ';

            $st = $this->getDB()->prepare($sql);
            $st->bindParam(':movie_key', $movie_key, PDO::PARAM_INT);
            $st->bindParam(':login_', $login_, PDO::PARAM_STR);
            $st->bindParam(':note', $note, PDO::PARAM_INT);
            $st->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
            $st->execute();

            $this->getDB()->commit();
        } catch (Exception $e) {
            $this->getDB()->rollBack();
            echo "Failed: " . $e->getMessage() . 'À la ligne ' . $e->getLine();
        }
    }

}

?>