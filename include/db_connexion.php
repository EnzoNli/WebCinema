<?php

include_once("fcs_api.php");
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
                $this->noter_un_film("Enzo", 315162, 5, "Un film incroyablement beau et très drôle ! Cela faisait très longtemps, pour ma part, que je n'avais pas autant rigolé dans une salle de cinéma ! 
Juste époustouflant les graphismes et un scénario très très bien mené ! Je vous conseille vivement d'aller le voir !");
                $this->noter_un_film("Zoze", 315162, 5, "Ce film est une pure merveille. On en prend plein les yeux du début à la fin. Tout est très bien rythmé. Vous n'allez pas être déçu ! ");

                $this->noter_un_film("Enzo", 299536, 4, "Avengers : Infinity War est un incroyable spectacle qui devrait laisser tous les fans de Marvel bouche bée devant le travail effectué par Joe et Anthony Russo.");
                $this->noter_un_film("Zoze", 299536, 3, "Un spectacle visuel impeccable, mais sans surprises. ");

                $this->noter_un_film("TotorLeCastor", 80321, 3, "Pas le meilleur film de la saga, mais un très bon divertissement pour les petits et pour les grands. ");
                $this->noter_un_film("Zoze", 80321, 4, "Un véritable feu d'artifice ambulant, rempli de couleurs éclatantes, de personnages atypiques (avec une une vilaine méchamment drôle) et de scènes d'anthologie, 
le tout servi par un rythme frénétique ne provoquant jamais l'ennui. Vous l'aurez compris : on en redemande!! ");
                $this->noter_un_film("Enzo", 80321, 2, "Invraisemblable déjà qu'ils parviennent à Monaco à la nage.
La suite ne l'est pas moins, et sans l'humour du 1 et du 2.
L'épisode de trop. ");

                $this->noter_un_film("TotorLeCastor", 499701, 2, "Babouche est fidèle au dessin animé mais déçu de ne pas avoir assez vu Totor le castor.");
        
                $this->noter_un_film("TotorLeCastor", 634649, 2, "Alors comment dire .... évidemment nous sommes en présence ici du plus gros fan service pour un film Spiderman il s'agit peut être même du meilleur 
film depuis la version de Sam Rémy néanmoins on reste frustré car les bande annonce nous a presque tout dévoiler et les surprises ont était globalement gâché. la photo est globalement très moyenne et on a très peu de \"jolie plan\" 
qu'il nous reste en tête a la fin du film. la réalisation également de Jon Watts est malheureusement raté ");
                $this->noter_un_film("Enzo", 634649, 3, "Spiderman version multiverse ou end game ... cela permet de mettre des effets spéciaux énormes, néanmoins cela n a pas donné pour autant plus de contenu au scénario comme on pouvait d y attendre. Pour le grand spectacle sur grand écran pourquoi pas ? 
Mais pas vraiment passionnant quand à l intrigue que j ai trouvé décevante ");

                $this->noter_un_film("Zoze", 69868, 4, "Frais et très agréable. 
        Le monde de la musique symphonique est en voie de disparition... 
        alors pourquoi ne pas inculquer à nos enfants cette riche et belle musique ? 
        Les critiques de presse me font hurler de rire... ah ça, pour vanter les mérites 
        d'une grosse production vide de sens et de valeurs, y'a du monde, mais dès que l'on 
        trouve un tant soit peu de couleur, de douceur et de vérité... y'a plus personne. 
        Alors pour moi, c'est 4 étoiles bien méritées !!!" );

        $this->noter_un_film("Enzo", 4935, 5, "Comme tout les autres films du même réalisateur, c'est un chef d'oeuvre quelque chose d'incomparable où se laisse porter par l'étrangeté de l'univers, la beauté des scènes et des dialogues. ");
        $this->noter_un_film("Zoze", 4935, 4, "Voilà un film que j’ai bien fait de revoir. Car entre sa magnifique animation, son histoire tendre et émouvante, ses sympathiques personnages ainsi que sa très belle bande originale, ce long-métrage de Hayao Miyazaki se trouve être une œuvre d’une grande sensibilité, magique et profondément poétique. Clairement un des plus beaux films du cinéma d’animation qu’il m’ait été donné de visionner jusqu’à maintenant. ");
        
        $this->noter_un_film("Enzo", 361743, 5, "Enfin un film qui sort du lot .. 
        même si certains flashback reviennent dans ce film .. pour rappeler l'ancien film .. 
        les effets et la photographie sont as coupé le souffle .. cramponné au fauteuil ont en 
        prend plein les yeux.. aucun temps mort .. avec un soupçon de comique.. une bande sons 
        fidèles comme il y as 30 ans ..Tom Cruise tiens incroyablement sont rôle très à cœur .. 
        splendide.. sachant qu'il réalise presque toute ses cascades lui même.. 
        cette fois ci c'est des G qu'il as fait subir à son corps.. sachant que presque tout 
        les comédiens sont montés à bord d'un F14 et ont tous vomis.. et surtout un belle 
        hommage à la fin Tony Scott le frère de ridley et aussi Tom a voulu mettre val kilmer à 
        l'honneur sachant qu'il est lui même atteint d'un cancer .. tout les ingrédients d'un 
        bon film y sont réuni .. foncez et accrocher vous et versez votre petite larme.. et 
        ressortez de la salle avec la banane !! 
        Tout la joie d'un bon film au cinéma ");

        $this->noter_un_film("Zoze", 149870, 5, "
Le Vent se lève : Et voila, Hayao Miyazaki tire sa révérence et nous livre son ultime œuvre. 
Il prend sa retraite bien mérité d’une merveilleuse façon. 
Car, il dit au revoir à son art, sa créativité et imagination et d’une bien belle manière. 
Parce que, il nous prouve une dernière fois sa maitrise et son talent unique. 
Ce film est une déclaration d’amour à l’aviation et surtout aux rêves que peut avoir un enfant 
jusqu'à son âge adulte : ne jamais cessais d’y croire. Mais, c’est aussi une sincère et 
touchante histoire d’amour entre l’ingénieur d’avion et son amour d’enfance. 
Et la fin est très émouvante et conclu avec brio les sujets du film : l’amour et les rêves. 
Et puis, les personnages sont attachants car on aimées les suives dans leur aventure. 
Et bien sur, il y a toujours le petit message de fonds (comme dans tous les Miyazaki) : 
je vous laisse le découvrir car chacun y trouve son message. ");

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
                    SELECT :login_ , :movie_key , :note , :commentaire';

            $st = $this->getDB()->prepare($sql);
            $st->bindParam(':movie_key', $movie_key, PDO::PARAM_INT);
            $st->bindParam(':login_', $login_, PDO::PARAM_STR);
            $st->bindParam(':note', $note, PDO::PARAM_INT);
            $st->bindParam(':commentaire', $commentaire, PDO::PARAM_STR);
            $st->execute();

            $this->getDB()->commit();
            return 0;
        } catch (PDOException $e) {
            $this->getDB()->rollBack();
            return $e->getCode();
        }
    }

}

?>