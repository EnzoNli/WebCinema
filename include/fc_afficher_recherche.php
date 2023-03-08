<?php

include_once("fcs_api.php");
include_once("fcs_pour_page_film.php");

function afficher_note($note, $bdOuApi) {
    $ch = '<div class="boite_note">
    ';
    $ch .= '<a>Note de ' . $bdOuApi . '</a>
    ';
    $ch .= '<div class="etoiles">
        ';
    for ($i = 0; $i < 10; $i++) {
        $ch .= '<div class="image_etoile"></div>
        ';
    }
    $ch .= '<div class="note">' . $note . '</div>
    ';
    return $ch;
}

function afficher_un_film($movie_key) {
    $film = json_decode(getMovie($movie_key), true);
    print_r($film);
    // image
    $img = getCheminVersAfficheOuBackdrop(4, $film['poster_path'], "include");
    // titre_fr
    $titre = $film['title'];
    // date de sortie -> en mode fr
    $date = $film['release_date']; // à transformer en 16 décembre 2021...
    // De :
    $de = 'chat';
    // Avec :
    $avec = array("un", "deux", "trois");
    // Genres : 
    $genres = $film['genres'];
    // synopsis
    $synopsis = $film['overview'];

    // NOTE API
    $note_api = "--";
    // NOTE BD
    if (boolFilmExiste($movie_key))
        $note_db = getMoyenne($movie_key);
    else
        $note_db = "--";

    // IMAGE
    $ch = '<figure>
    ';
    $ch .= '<img class="image" src="' . $img . '" alt="' . $titre . '" width="310" height="420">
    ';
    $ch .= '</figure>
    ';

    // DESCRIPTION : TITRE + DATE + DE + AVEC
    $ch .= '<div class="description">
    ';
    $ch .= '<h2>' . $titre . '</h2>
    ';
    $ch .= '<div class="precision">
    ';
    $ch .= '<span class="date>' . $date . '</span>
    ';
    $ch .= '<span class="de>' . $de . '</span>
    ';
    $ch .= '<div class="avec">
    ';
    foreach ($avec as $k => $v) {
        $ch .= '<span classe="avec">' . $v . '</span>
    ';
    }
    $ch .= '</div>
    '; // avec
    $ch .= '<div class="genres">
    ';
    foreach ($genres as $k => $v) {
        $ch .= '<span classe="genres">' . $v['name'] . '</span>
    ';
    }
    $ch .= '</div>
    '; // genres
    $ch .= '</div>
    '; // precision
    $ch .= '</div>
    '; // description

    // SYNOPSIS
    $ch .= '<div class="synopsis">
    ';
    $ch .= $synopsis;
    $ch .= '</div>
    '; // synopsis

    // NOTES
    $ch .= '<div class="notes">
                ';
    $ch .= afficher_note($note_api, "l'API");
    $ch .= afficher_note($note_db, "la base des Zous");
    $ch .= '</div>
    ';

    return $ch;
}


function afficher_liste($tableau) { // nombre de résultats trouvés 
    $ch = '<ul>
    ';

    foreach ($tableau as $key => $value) {
        $ch .= '<li>
        ';
        $ch .= afficher_un_film($value);
        $ch .= '</li>
        ';
    }

    $ch .= '</ul>';
    return $ch;
}

/*
// afficher_entete();
// echo afficher_form();
if (isset($_POST['submit'])) {
    $liste = filtrer_trier();
    afficher_liste($liste);
    // gérer pagination
}
*/

echo afficher_un_film(315162);
