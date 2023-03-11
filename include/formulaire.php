<?php

include_once("db_connexion.php");
$connexion = new ConnexionDB("../database");

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
    $sql = 'SELECT api_genre_id,nom_genre FROM Genre ORDER BY nom_genre';
    $result = $connexion->getDB()->query($sql);
    while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
        $chaine .= '<option value="';
        $chaine .= $row['api_genre_id'];
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
