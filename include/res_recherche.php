<?php

include_once("fcs_api.php");
include_once("fcs_bd.php");

setlocale(LC_TIME, 'fr_FR.UTF-8', 'fra');

function afficher_note($note, $bdOuApi) {
    $nb_etoiles = floor($note);
    $ch = '<div class="boite_note">
    ';
    $ch .= '<p class="texte_note">Note de ' . $bdOuApi . ' : <span>
    ';
    for ($i = 0; $i < $nb_etoiles; $i++) {
        $ch .= '<img class="etoile" src="../images/etoile.png" alt="étoile jaune"></img>
        ';
    }
    for ($i = 0; $i < 5 - $nb_etoiles; $i++) {
        $ch .= '<img class="etoile" src="../images/etoilevide.png" alt="étoile grise"></img>
        ';
    }
    $ch .= ' ' . $note;
    $ch .= '</span></p></div>
    ';
    return $ch;
}

function afficher_pas_note($note, $bdOuApi) {
    $ch = '<div class="boite_note">
    ';
    $ch .= '<p class="texte_note">Note de ' . $bdOuApi . ' : <span>
    ';
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
    $date = $film['release_date'];
    // Avec :
    $acteurs = getActorsName($movie_key);
    // Genres : 
    $genres = $film['genres'];
    // synopsis
    $synopsis = $film['overview'];

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
    $ch .= '<div class="avec">Avec :
    ';
    $i = 0;
    $act = '';
    while ($i < 3 && $i < count($acteurs)) {
        $act .= '<span classe="avec">' . $acteurs[$i]["name"] . ',</span>
        ';
        $i++;
    }
    $ch .= $act;
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

    // NOTE API
    if ($film['vote_count']) {
        $note_api = $film['vote_average'];
        $ch .= afficher_note(round(floatval($note_api / 2), 2), "l'API");
    } else {
        $note_api = "--";
        $ch .= afficher_pas_note($note_api, "l'API");
    }
    // NOTE BD
    if (boolFilmExiste($movie_key)) {
        $note_db = getMoyenne($movie_key);
        $ch .= afficher_note(round($note_db, 2), "la base des Zous");
    } else {
        $note_db = "--";
        $ch .= afficher_pas_note($note_db, "la base des Zous");
    }

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
        $ch .= afficher_un_film($value['api_movie_id']);
        $ch .= '</li>
        ';
    }

    $ch .= '</ul>';
    return $ch;
}
