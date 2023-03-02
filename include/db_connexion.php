<?php

class ConnexionDB
{
    private $db;

    function __construct()
    {
        $file = getcwd() . "/database/cinema.db";
        $file2 = getcwd() . "/database/cinema.sql";
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
}



?>