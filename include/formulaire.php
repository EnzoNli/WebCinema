<?php

include_once("db_connexion.php");
$connexion = new ConnexionDB("../database");

function afficher_form() {
    $chaine = '<form action="" method="post">
    ';
    $chaine .= afficher_select_trier();
    $chaine .= '<div class="entrees">
                <label for="genre">Entrer un mot-clef du titre</label>
                <br>
                <input type="text" name="titre" id="titre" placeholder="Titre de film">
                </div>
    ';
    $chaine .= afficher_select_genre();
    $chaine .= '<div class="entrees">
                <label for="genre">Entrer un nom d\'acteur</label>
                <br>
                <input type="text" name="acteur" id="acteur" placeholder="Acteur">
                </div>
                <div class="entrees">
                <label for="debut">Date de sortie</label>
                <br>
                <span>Entre </span>
    ';
    $chaine .= afficher_select_annee("debut");
    $chaine .= '<span> et </span>';
    $chaine .= afficher_select_annee("fin");
    $chaine .= '</div>
                <div class="entrees">
                <label for="inf">Note moyenne</label>
                <br>
                <span style="font-size:1.5vh">(dans la db des Zous)</span>
                <br>
                <span>min </span>
    ';
    $chaine .= afficher_select_note("inf");
    $chaine .= '<span> et max </span>';
    $chaine .= afficher_select_note("sup");
    $chaine .= '</div>
            </form>
    ';
    return $chaine;
}

function afficher_select_trier() {
    $chaine = '<div class="entrees">
            <label for="trier">Trier par</label>
            <br>
    ';
    $chaine .= '<select name="trier" id="trier" title="Trier par">
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
    $chaine .= '</select>
                </div>
    ';
    return $chaine;
}

function afficher_select_genre() {
    global $connexion;
    $chaine = '<div class="entrees">
            <label for="genre">Sélectionner un genre</label>
            <br>
    ';
    $chaine .= '<select name="genre" id="genre" title="Sélectionner un genre">
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
                </div>
    ';
    return $chaine;
}

function afficher_select_annee($marqueur, $max = 2023, $min = 1894) {
    $min -= 1;
    $chaine = '<label for="trier">Trier par</label>
    <br>
    ';
    $an = $max;
    $chaine = '<select name="' . $marqueur . '" id="' . $marqueur . '" title="Trier par">
    ';
    $chaine .= '<option value="no">----</option>
    ';
    while ($an > $min) {
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
    $chaine .= '<option value="no">--</option>
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
