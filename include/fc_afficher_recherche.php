<?php

include_once("fcs_api.php");
include_once("fcs_pour_page_film.php");

setlocale(LC_TIME, 'fr_FR.UTF-8', 'fra');

function afficher_note($note, $bdOuApi) {
    $nb_etoiles = floor($note);
    $ch = '<div class="boite_note">
    ';
    $ch .= '<p class="texte_note">Note de ' . $bdOuApi . ' : <span>
    ';
    for ($i = 0; $i < $nb_etoiles; $i++) {
        $ch .= '<img class="etoile" src="../images/etoile.png" alt=""></img>
        ';
    }
    for ($i = 0; $i < 5 - $nb_etoiles; $i++){
        $ch .= '<img class="etoile" src="../images/etoilevide.png" alt=""></img>
        ';
    }
    $ch .= ' ' . $note;
    $ch .= '</span></p></div>
    ';
    return $ch;
}

function afficher_un_film($movie_key) {
    $film = json_decode(getMovie($movie_key), true);
    // image
    $img = getCheminVersAfficheOuBackdrop(4, $film['poster_path'], "include");
    // titre_fr
    $titre = $film['title'];
    // date de sortie -> en mode fr
    $date = $film['release_date']; // à transformer en 16 décembre 2021..., si null ?
    // De :
    $de = 'chat';
    // Avec :
    $avec = array("un", "deux", "trois");
    // Genres : 
    $genres = $film['genres'];
    // synopsis
    $synopsis = $film['overview'];
    // NOTE API
    if ($film['vote_count'])
        $note_api = $film['vote_average'];
    else
        $note_api = "--";
    // NOTE BD
    if (boolFilmExiste($movie_key))
        $note_db = getMoyenne($movie_key)['moyenne'];
    else
        $note_db = "--";

    $ch = '<div class="unFilm">
    ';
    $ch .= '<div class="row">
    ';

    // IMAGE
    $ch .= '<div class="gauche">
    ';
    $ch .= '<img class="image" src="' . $img . '" alt="' . $titre . '" width="310" height="420">
    ';
    $ch .= '</div>
    '; // gauche

    $ch .= '<div class="droite">
    ';

    // DESCRIPTION : TITRE + DATE + DE + AVEC + GENRES
    $ch .= '<div class="description">
    ';
    $ch .= '<h2>' . $titre . '</h2>
    ';
    $ch .= '<div class="precision">
    ';
    if ($date != null)
        $ch .= '<span class="date">' . strftime("%e %B %Y ", strtotime($date)) . '</span>
        ';
    $ch .= '<div class="de">' . $de . '</div>
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
    $ch .= genereStringGenres($genres);

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
    $ch .= '</div>
    '; // droite
    $ch .= '</div>
    '; // row

    // NOTES
    $ch .= '<div class="notes">
                ';
    $ch .= afficher_note($note_api, "l'API");
    $ch .= afficher_note($note_db, "la base des Zous");
    $ch .= '</div>
    ';

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
